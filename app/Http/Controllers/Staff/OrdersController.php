<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;


class OrdersController extends Controller
{
    public function dashboard(){
        $newOrders = Order::where('order_status', 'Pending')->count();
        $customers = User::where('role_id', 2)->count();
        $products = Product::count();

        return view('staff.dashboard', compact('newOrders'));

    }
    public function index(Request $request){

        try {
            if (request()->ajax()) {
                $sqlQuery = Order::with('user', 'coupon');
                if($request->get('status')){
                    $sqlQuery->where('order_status', $request->get('status'));
                }
                return datatables()->of($sqlQuery->orderBy('created_at','desc')->get())
                    ->addIndexColumn()
                    ->addColumn('user', function ($data) {
                        return $data->user->name ?? $data->name ?? "Customer_".$data->user_id;
                    })->addColumn('total_amount', function ($data) {
                        return ($data->total_amount) ?? '';
                    })->addColumn('coupon', function ($data) {
                        return $data->coupon->code ?? '--';
                    })->addColumn('order_date', function ($data) {
                        return date('d-M-Y H:i:s', strtotime($data->created_at)) ?? '';
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
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="order-show/' . $data->id . '" class="btn btn-dark btn-sm">
                                <i class="fas fa-eye"></i>
                                </a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i></button>';
                    })->rawColumns(['user', 'status', 'total_amount', 'order_date', 'coupon', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('staff/dashboard')->with('error', 'SomeThing Went Wrong baby');
        }

        return view('staff.order.index', compact('request'));
    }

    public function show($id){
        $order = Order::where('id', $id)->with('orderItems', 'user', 'orderItems.product', 'payment', 'coupon')
            ->firstOrFail();
        return view('staff.order.show', compact('order'));
    }

    public function changeOrderStatus(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();

        if ($order != null) {

            $order->update(['order_status' => $request->val]);
            return true;
        } else {
            return false;
        }
    }


    public function destroy($id){
        $content = Order::find($id);
        if ($content != null){
            $content->delete();
            return true;
        }
        return false;
    }

    public function showNotification(){
        try {
            if (request()->ajax()) {
                return datatables()->of(Notification::orderBy('id', 'desc')->get())
                    ->addIndexColumn()
                    ->addColumn('date', function ($data) {
                        return $data->created_at->format('Y-m-d');
                    })->rawColumns(['date'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/staff/dashboard')->with('error', $ex->getMessage());
        }
        return view('staff.notifaction');
    }
}
