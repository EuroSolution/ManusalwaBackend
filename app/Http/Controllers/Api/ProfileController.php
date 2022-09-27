<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
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
            $q->where('user_id', Auth::id());
        })->get();

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
            ->where('id', $id)->get();
        if ($order != null){
            return $this->success($order);
        }
        return $this->error("Order Not Found");
    }
}
