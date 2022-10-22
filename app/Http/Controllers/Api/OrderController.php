<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\OrderItemAttribute;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function checkout(Request $request){
        $validator = Validator::make($request->all(), [
//            'name' => 'required',
//            'phone' => 'required',
//            'email' => 'required',
            'address' => 'required'
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }
        $orderDetail = $this->createOrder($request);
        if ($orderDetail['status'] == true){
            return $this->success(
                array(
                    "order_number" => $orderDetail['order_number'],
                    "total_amount" => $orderDetail['total_amount'] ?? "",
                    "voucher" => $orderDetail['voucher'] ?? "",
                    "approximate_time" => $orderDetail['approximate_time'] ?? "",
                ),
                'Order Placed Successfully'
            );
        }else{
            return $this->error($orderDetail['msg']);
        }

    }

    public function validateVoucher(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'subtotal' => 'required'
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }

        $resp = Coupon::validateCoupon($request->code, $request->subtotal);
        if ($resp['isValidCoupon'] == true){
            return $this->success(array(
                'discount' => $resp['discount']
            ), 'Valid Voucher');
        }else{
            return $this->error($resp['couponErrorMsg']);
        }
    }

    private function createOrder($request){

        $cart = Cart::with('cartItems', 'cartItems.cartItemAddons', 'cartItems.cartAttributes')
            ->where('user_id', Auth::id())->orderBy('id', 'desc')->first();
        $discount = 0;
        $couponId = null;
        try {
            if ($cart->voucher_code != null){
                $resp = Coupon::validateCoupon($cart->voucher_code, $cart->subtotal);
                if ($resp['isValidCoupon'] == true){
                    $discount = $resp['discount'];
                    $couponId = $resp['voucher']->id;
                }else{
                    return array('status'=>false, 'msg'=>$resp['couponErrorMsg']);
                }
            }

            $today = date("Ymd");
            $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
            $order_no = $today . $rand;
            $response = array('status' => false);
            $orderDetail = DB::transaction(function () use($cart, $request, $order_no, $discount, $couponId){
                $total = $cart->subtotal + $cart->tax + $cart->delivery_fee - $discount;
                $order = Order::create([
                    'order_number' => $order_no,
                    'user_id' => Auth::id(),
                    'deal_id' => $cart->deal_id,
                    'subtotal' => $cart->subtotal,
                    'tax' => $cart->tax,
                    'discount' => $discount,
                    'delivery_fee' => $cart->delivery_fee,
                    'total_amount' => $total,
                    'name' => $request->name ?? Auth::user()->name,
                    'email' => $request->email ?? Auth::user()->email,
                    'phone' => $request->phone ?? Auth::user()->phone,
                    'address' => $request->address,
                    'nearest_landmark' => $request->nearest_landmark,
                    'location'  => $request->location,
                    'order_type'  => $request->order_type,
                    'notes' => $cart->notes ?? "",
                    'order_status' => 'Pending',
                    'coupon_id' => $couponId ?? null,
                    'is_deal' => $cart->is_deal,
                    'source' => $cart->source ?? null

                ]);
                foreach ($cart->cartItems as $cartItem){
                    $orderItem = OrderItem::create([
                        'order_id'  => $order->id,
                        'product_id' => $cartItem->product_id,
                        'price' => $cartItem->price,
                        'size' => $cartItem->size,
                        'quantity' => $cartItem->quantity,
                        'deal_id' => $cartItem->deal_id,
                        'deal_item_id' => $cartItem->deal_item_id,
                    ]);

                    if (!empty($cartItem->cartItemAddons) && $cartItem->cartItemAddons != null){
                        foreach ($cartItem->cartItemAddons as $cartItemAddon){
                            OrderItemAddon::create([
                                'order_item_id' => $orderItem->id,
                                'addon_item_id' => $cartItemAddon->addon_item_id,
                                'addon_group'   => $cartItemAddon->addon_group,
                                'addon_item'    => $cartItemAddon->addon_item,
                                'price'         => $cartItemAddon->price,
                                'quantity'      => $cartItemAddon->quantity,
                                'size'      => $cartItemAddon->size,
                            ]);
                        }
                    }
                    if (!empty($cartItem->cartAttributes) && $cartItem->cartAttributes != null){
                        foreach ($cartItem->cartAttributes as $cartAttribute){
                            OrderItemAttribute::create([
                                'order_item_id' => $orderItem->id,
                                'attribute_item_id' => $cartAttribute->attribute_item_id,
                                'attribute_name' => $cartAttribute->attribute_name,
                                'attribute_item_name' => $cartAttribute->attribute_item
                            ]);
                        }
                    }
                }
                Payment::create([
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                    'total_amount' => $order->total_amount,
                    'payment_method' => $request->payment_method,
                    'status' => $request->payment_method == 'Cash' ? 'Pending' : 'Completed',
                ]);
                Coupon::availedVoucher($couponId);
                return $order;
            });
            $response['status'] = true;
            $response['order_number'] = $order_no;
            $response['total_amount'] = number_format($orderDetail->total_amount, 2, '.', '');
            $response['voucher'] = ($orderDetail->discount > 0) ? $cart->voucher_code : "No Voucher";
            $response['approximate_time'] = "30 Minutes";

            $cart->delete();
            Notification::sendNotification(1, "New Order", "Order No. $order_no",
                "Order", $orderDetail->id, 'Order');
            try{
                $messageBody = "Thank you for your order. \nYour Order Number is $order_no \n\n ".env('APP_NAME');
                $this->sendMessageToClient(Auth::user()->phone, $messageBody);
                $this->sendPushNotification(env('APP_NAME'), 'Order placed', [], [Auth::id()]);
                $this->sendPushNotification('New Order', 'Order No#'.$order_no, [], [1]);
            }catch (\Exception $exception){}
            return $response;
        }catch (\Exception $ex){
            return  array('status' => false, 'msg' => $ex->getMessage());
        }
    }
}
