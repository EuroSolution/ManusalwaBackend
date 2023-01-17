<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index(Request $request){
        try {
            if (request()->ajax()) {
                return datatables()->of(Coupon::orderBy('id', 'desc')->get())
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="coupon/show/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="Edit" href="coupon/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.coupon.index');
    }

    public function show(Request $request, $id){
        $coupon = Coupon::with('couponUsers', 'couponUsers.user')->findOrFail($id);
        return view('admin.coupon.show',compact('coupon'));
    }

    public function add(Request $request){

        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'code' => 'required|string',
                'value' => 'required|numeric',
                'type' => 'required|string',
                'usage' => 'required|numeric',
            ]);

            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            $coupon = Coupon::create([
                'code' => $request->input('code'),
                'value' => $request->input('value'),
                'type' => $request->input('type') ?? 'value',
                'expiration_date' => $request->input('expiration_date') ?? null,
                'min_order' => $request->input('min_order'),
                'status' => 1,
                'all_users' => ($request->input('allUsers'))?1:0
            ]);

            if($request->input('allUsers')){
                $allCustomers = User::all()->where('role_id', 2);
                foreach($allCustomers as $customer){
                    CouponUser::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $customer->id,
                        'availed' => 0,
                        'usage' => $request->input('usage')
                    ]);
                }
            }else{
                if ($request->has('customers') && !empty($request->input('customers'))){
                    foreach ($request->input('customers') as $customer){
                        CouponUser::create([
                            'coupon_id' => $coupon->id,
                            'user_id' => $customer,
                            'availed' => 0,
                            'usage' => $request->input('usage')
                        ]);
                    }
                 }
            }



            /*if ($request->has('customers') && $request->input('customers') != null && $request->input('customers') != ""){
                foreach ($request->input('customers') as $userID){
                    if ($userID != null  && $userID != 0){
                        $customer = Customers::where('user_id', $userID)->first();
                        Notification::create([
                            'data' => 'You are awarded with a new coupon, see in the coupons section.',
                            'order_id' => null,
                            'type' => 'coupon',
                            'receiver_id' => $customer->id,
                        ]);
                    }
                }
            }*/
            return redirect(route('admin.coupons'))->with('success', 'Coupon Added');
        }
        $customers = User::where('role_id', 2)->get();
        return view('admin.coupon.create', compact('customers'));
    }

    public function edit(Request $request, $id){
        $coupon = Coupon::with('couponUsers')->findOrFail($id);
        $customers = User::where('role_id', 2)->get();

        $notExistCustomers = [];
        foreach($customers as $user){
            $existUser = 0;
            foreach($coupon->couponUsers as $cp){
                $existUser = 1;
                if($user->id==$cp->user_id){
                    $existUser = 0;
                    break;
                }
            }

            if($existUser){

                $notExistCustomers[] = $user->id;
            }
        }

        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'code' => 'required|string',
                'value' => 'required|numeric',
                'type' => 'required|string',
                'usage' => 'required|numeric'
            ]);

            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            $coupon->code = $request->input('code');
            $coupon->value = $request->input('value');
            $coupon->type = $request->input('type') ?? 'value';
            $coupon->expiration_date = $request->input('expiration_date') ?? null;
            $coupon->min_order = $request->input('min_order');
            $coupon->all_users = ($request->input('allUsers'))?1:0;
            $coupon->save();

            $updateData = array('usage' => $request->input('usage'));
            $usage = $coupon->couponUsers[0]->usage;

            if ($usage < $request->input('usage')){
                $updateData['availed'] = 0;
            }

            $couponUsers = CouponUser::where('coupon_id', $id)->get();
            if($request->input('allUsers')){
                $couponUsers->each(function($couponUser) use($request, $updateData){
                    $couponUser->update($updateData);
                });

                foreach($notExistCustomers as $notExistCustomer){
                    CouponUser::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $notExistCustomer,
                        'availed' => 0,
                        'usage' => $request->input('usage')
                    ]);
                }

            }else{
                if ($request->has('customers') && !empty($request->input('customers'))){
                    $customerIds = array();
                    foreach ($couponUsers as $couponUser){
                        $customerIds[] = $couponUser->user_id;
                    }
                    $removedUsers = array_diff($customerIds, $request->input('customers'));
                    foreach ($removedUsers as $removedUser){
                        if (in_array($removedUser, $customerIds)){
                            CouponUser::where('coupon_id', $id)->where('user_id', $removedUser)->delete();
                        }
                    }

                    foreach ($request->input('customers') as $customer){
                        CouponUser::updateOrCreate(
                            [
                                'coupon_id' => $coupon->id,
                                'user_id' => $customer
                            ],
                            $updateData
                        );
                    }
                }
            }

            return redirect()->back()->with('success', 'Coupon Updated');
        }

        $userIds = array();
        if ($coupon->couponUsers != null){
            foreach ($coupon->couponUsers as $cu){
                $userIds[] = $cu->user_id;
                if($cu->usage <= $cu->used){
                    $cu->availed = 1;
                    $cu->save();
                }
            }
        }
        $userIds = (!empty($userIds)) ? implode(',', $userIds) : "";
        $customers = User::where('role_id', 2)->get();
        $couponUser = null;
        foreach($coupon->couponUsers as $couponUsers){
            $couponUser = $couponUsers;
            break;
        }
        // dd($couponUsers);
        return view('admin.coupon.edit',compact('coupon', 'customers', 'userIds','couponUser'));
    }

    public function destroy($id){
        $content = Coupon::find($id);
        if($content){
            $content->delete();
            CouponUser::where('coupon_id', $id)->delete();
            echo 1;
        }else{
            echo 0;
        }
    }

    public function changeStatus(Request $request, $id){
        $coupon = Coupon::find($id);
        if(empty($coupon)){
            return 0;
        }
        $status = $coupon->status;
        if($status == 0){
            $status = 1;
        }else{
            $status = 0;
        }
        if($request->method() == 'POST'){
            $coupon->status = $status;
            $coupon->save();
        }
        return 1;
    }
}
