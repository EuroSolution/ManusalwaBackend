<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index(Request $request){
        try {
            if (request()->ajax()) {
                return datatables()->of(Order::with('user', 'coupon')->orderBy('created_at','desc')->get())
                    ->addIndexColumn()
                    ->addColumn('user', function ($data) {
                        return $data->name ?? $data->user->name ?? "Customer_".$data->user_id;
                    })->addColumn('total_amount', function ($data) {
                        return ($data->total_amount) ?? '';
                    })->addColumn('order_date', function ($data) {
                        return date('d-M-Y H:i:s', strtotime($data->created_at)) ?? '';
                    })->addColumn('coupon', function ($data) {
                        return $data->coupon->code ?? '--';
                    })->addColumn('status', function ($data) {
                        if ($data->order_status == 'Pending') {
                            return '<span class="badge badge-secondary">Pending</span>';
                        } elseif ($data->order_status == 'Cancelled') {
                            return '<span class="badge badge-danger">Cancelled</span>';
                        } elseif ($data->order_status == 'Processing') {
                            return '<span class="badge badge-primary">Processing</span>';
                        } elseif ($data->order_status == 'Delivered') {
                            return '<span class="badge badge-info">Delivered</span>';
                        } elseif ($data->order_status == 'Completed') {
                            return '<span class="badge badge-success">Completed</span>';
                        }else{
                            return "";
                        }
                    })->addColumn('change_status', function ($data) {
                        return '<select name="order_status" id="order_status" class="form-control" data-order_id="'.$data->id.'">
                                    <option value="">Change Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>';
                    })->addColumn('action', function ($data) {
                        return '
                                <a title="View" href="order/show/' . $data->id . '" class="btn btn-dark btn-sm">
                                <i class="fas fa-eye"></i>
                                </a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i></button>';
                    })->rawColumns(['user', 'status', 'total_amount', 'coupon', 'order_date', 'action','change_status'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('dashboard')->with('error', 'SomeThing Went Wrong baby');
        }
        return view('admin.order.index');
    }

    public function show($id){
        $order = Order::where('id', $id)->with('orderItems', 'user', 'orderItems.product', 'orderItems.orderItemAddons',
            'orderItems.orderItemAttributes', 'payment', 'coupon')
            ->firstOrFail();
        $dealsArray = array();
        $dealItemsArray = array();
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
        );
        $dealId = "--";
        foreach($order->orderItems as $item){
            if ($item->deal_id != null){
                if ($dealId != $item->deal_id){
                    $dealId = $item->deal_id;
                    $deal =  Deal::withTrashed()->select('id', 'name', 'description', 'price', 'image')
                        ->where('id',$item->deal_id)->first();
                    $dealsArray = array(
                        'id' => $deal->id,
                        'name' => $deal->name,
                        'description' => $deal->description,
                        'price' => $deal->price,
                        'image' => $deal->image
                    );
                    $dealItemsArray[] = $item;
                }else{
                    $dealItemsArray[] = $item;
                }
            }else{
                $normalItems[] = $item;
            }
        }

        $orderItems = array();
        if (!empty($dealItemsArray)){
            $dealsArray['dealItems'] = $dealItemsArray;
            $orderItems['deals'][] = $dealsArray;
        }else{
            $orderItems['deals'] = array();
        }

        $orderItems['normalItems'] = $normalItems; //dd($orderItems);
        return view('admin.order.show', compact('order', 'orderItems'));
    }

    public function destroy($id){
        $content = Order::find($id);
        if ($content != null){
            $content->delete();
            return true;
        }
        return false;
    }

    public function changeOrderStatus(Request $request, $id)
    {
        $order = Order::with('user')->where('id', $id)->first();

        if ($order != null) {
//            if ($request->val == 'delivered'){
//                $orderData = $order->first();
//                Notification::create([
//                    'data' => 'Order has been delivered',
//                    'order_id' => $id,
//                    'type' => 'order',
//                    'receiver_id' => $orderData->customer_id,
//                ]);
//            }elseif ($request->val == 'in_process'){
//                $orderData = $order->first();
//                Notification::create([
//                    'data' => 'Order has been moved to In Process from Refund',
//                    'order_id' => $id,
//                    'type' => 'order',
//                    'receiver_id' => $orderData->customer_id,
//                ]);
//            }
            $this->sendPushNotification('Pizzeria Roma', 'Order Status Updated to '.$request->val, [], [$order->user->id]);
            $order->update(['order_status' => $request->val]);
            return true;
        } else {
            return false;
        }
    }


}
