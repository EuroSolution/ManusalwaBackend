<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Deal;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        return $this->success($user);
    }

    public function edit(Request $request){
        $user = Auth::user();
        if($request->method() == 'POST'){
            $validate  = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required'
            ]);

            if($validate->fails()){
                return $this->error($validate->errors());
            }

            $user->name  = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;

            $user->save();

            return $this->success([],"Data Updated Succefully");
        }
    }

    public function vouchers(Request $request){
        $vouchers = Coupon::whereHas('couponUsers', function ($q){
            $q->where('user_id', Auth::id())->where('availed', 0);
        })->where('expiration_date', '>=', date('Y-m-d'))->get();
        return $this->success($vouchers);
    }

    public function orders(Request $request){
        $orders = Order::with('orderItems', 'orderItems.product','orderItems.orderItemAddons', 'orderItems.orderItemAttributes', 'coupon')
            ->where('user_id', Auth::id())
            ->whereNotIn('order_status', ['Completed', 'Cancelled'])
            ->orderBy('id', 'DESC')->get();
        $completedOrders =  Order::with('orderItems', 'orderItems.product','orderItems.orderItemAddons', 'orderItems.orderItemAttributes', 'coupon')
            ->where('user_id', Auth::id())
            ->where('order_status', 'Completed')
            ->orderBy('id', 'DESC')->get();
        $cancelledOrders =  Order::with('orderItems', 'orderItems.product','orderItems.orderItemAddons', 'orderItems.orderItemAttributes', 'coupon')
            ->where('user_id', Auth::id())
            ->where('order_status', 'Cancelled')
            ->orderBy('id', 'DESC')->get();
        return $this->success(array(
            'active_orders' => $orders,
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders
        ));
    }

    public function getOrderById($id){
        $order = Order::with('orderItems', 'orderItems.product','orderItems.orderItemAddons', 'orderItems.orderItemAttributes')
            ->where('id', $id)->first();
        if ($order != null){
            $normalItems = array();
            $orderDetail = array(
                'order_no' => $order->order_number,
                'subtotal' => $order->subtotal,
                'tax' => $order->tax,
                'delivery_fee' => $order->delivery_fee,
                'discount' => $order->discount,
                'total_amount' => $order->total_amount,
                'address' => $order->address,
                'order_type' => $order->order_type,
                'notes' => $order->notes,
                'gst_percent' => $order->gst_percent,
            );
            $dealId = "--";
            $dealsArray = array();
            $dealItemsArray = array();
            $dealDataArray = array();
            $dealReference = '';
            $dealIndex = 0;
            $previousDealId = 0;
            $previousOrderQty = 0;

            //echo "<pre>";
            //print_r($order->orderItems->toArray());
            //die;

            foreach($order->orderItems as $key => $item){

                if(!$dealReference && !empty($item->reference_no)){
                    $dealReference = $item->reference_no;
                }

                if ($item->deal_id != null){

                    if ($dealReference != $item->reference_no){

                        $dealId = $previousDealId ?? $item->deal_id;

                        $deal =  Deal::withTrashed()->select('id', 'name', 'description', 'price', 'image')
                            ->where('id',$dealId )->first();

                        if($deal){
                            $dealDataArray = array(
                                'id' => $deal->id,
                                'name' => $deal->name,
                                'description' => $deal->description,
                                'price' => $deal->price,
                                'image' => $deal->image,
                                'quantity' => $previousOrderQty ?? $item->quantity,
                                'dealItems' => $dealItemsArray
                            );
                        }
                        $orderDetail['orderItems']['deals'][$dealIndex]= $dealDataArray ;
                        $dealItemsArray = [];
                        $dealReference = $item->reference_no;
                        $dealIndex += 1;
                        $dealItemsArray[] = $item;
                        $previousDealId = $item->deal_id;
                        $previousOrderQty = $item->quantity;
                    }else{
                        $previousDealId = $item->deal_id;
                        $previousOrderQty = $item->quantity;
                        $dealItemsArray[] = $item;
                    }

                    if ((count($order->orderItems) - 1) == $key){

                        $deal =  Deal::withTrashed()->select('id', 'name', 'description', 'price', 'image')
                            ->where('id',$item->deal_id)->first();

                        $dealDataArray = array(
                            'id' => $deal->id,
                            'name' => $deal->name,
                            'description' => $deal->description,
                            'price' => $deal->price,
                            'image' => $deal->image,
                            'quantity' => $item->quantity,
                            'dealItems' => $dealItemsArray
                        );

                        $orderDetail['orderItems']['deals'][$dealIndex]= $dealDataArray ;
                        $dealItemsArray = [];
                        $dealReference = $item->reference_no;
                        $dealIndex += 1;
                    }

                }else{
                    $normalItems[] = $item;
                }
            }

            if(!isset($orderDetail['orderItems']['deals']) && empty($orderDetail['orderItems']['deals'])){
                $orderDetail['orderItems']['deals'] = array();
            }
            $orderDetail['orderItems']['normalItems'] = $normalItems;
            return $this->success($orderDetail);
        }else{
            return $this->error('Order Not Found');
        }
    }
}
