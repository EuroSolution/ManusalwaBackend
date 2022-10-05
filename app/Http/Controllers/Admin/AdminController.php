<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Models\Notification;
use App\Notifications\SendPushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard(){
        $newOrders = Order::where('order_status', 'Pending')->count();
        $customers = User::where('role_id', 2)->count();
        $products = Product::count();

        if(Auth::user()->role_id == 3){
            return view('staff.dashboard', compact('newOrders'));
        }
        return view('admin.dashboard', compact('newOrders', 'customers', 'products'));
    }

    public function login(Request $request){
        if(Auth::check()){
            if(Auth::user()->role_id == 1){
                return redirect('/dashboard');
            }
            elseif(Auth::user()->role_id == 3){
                return redirect('/staff/dashboard');
            }
            else{
                return view('admin.login');
            }
        }
        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            $user = User::where('email', $request->input('email'))->first();
            if ($user != null){
                if (Hash::check($request->input('password'), $user->password)) {
                    Auth::login($user);
                    $user->update(['fcm_token'=>$request->token]);
                    if($user->role_id == 3){
                        return redirect('/staff/dashboard');
                    }
                    return redirect('/dashboard');
                }else{
                    return back()->withErrors(['password' => 'invalid email or password']);
                }
            }else{
                return back()->withErrors(['password' => 'invalid email or password']);
            }
        }
        return view('admin.login');
    }

    public function setting(Request $request){
        $content = Setting::firstOrFail();
        if ($request->method() == 'POST'){
            $content->title = $request->input('title') ?? 'MannuSalwa';
            $content->email = $request->input('email');
            $content->phone = $request->input('phone');
            $content->address = $request->input('address');
            $content->facebook = $request->input('facebook');
            $content->twitter = $request->input('twitter');
            $content->instagram = $request->input('instagram');
            $content->currency = $request->input('currency');
            $content->tax = $request->input('tax') ?? 0;
            $content->android_app_url = $request->input('android_app_url');
            $content->android_app_version = $request->input('android_app_version');
            $content->android_force_update = $request->input('android_force_update') ?? 0;
            $content->ios_app_url = $request->input('ios_app_url');
            $content->ios_app_version = $request->input('ios_app_version');
            $content->ios_force_update = $request->input('ios_force_update') ?? 0;
            $content->save();
            try{
                if ($request->file('logo')) {
                    $fileName = time() . '-' . $request->file('logo')->getClientOriginalName();
                    $filePath = $request->file('logo')->path();
                    $imageUrl = $this->uploadImageIK($fileName, $filePath, 'setting');
                    $content->logo = $imageUrl ?? null;
                }
                if ($request->file('qr_code_image')) {
                    $fileNameQr = time() . '-' . $request->file('qr_code_image')->getClientOriginalName();
                    $filePathQr = $request->file('qr_code_image')->path();
                    $imageUrlQr = $this->uploadImageIK($fileNameQr, $filePathQr, 'setting');
                    $content->qr_code_image = $imageUrlQr ?? null;
                }
                $content->save();
            }catch (\Exception $ex){
                return redirect()->back()->with('error', 'Exception in while uploading image');
            }
            return redirect()->back()->with('success', 'Site Setting Updated Successfully');
        }
        return view('admin.setting', compact('content'));
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
            return redirect('/dashboard')->with('error', $ex->getMessage());
        }
        return view('admin.notifaction');
    }

    public function updateNotification(Request $request){
        $notification = Notification::find($request->id??0);
        if ($notification != null){
            $notification->read_at = date('Y-m-d H:i:s');
            $notification->save();
            return true;
        }
        return false;
    }

    public function updateToken(Request $request){
        try{
            $request->user()->update(['fcm_token'=>$request->token]);
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }

    public function sendNotification(){
        $resp = $this->sendPushNotification('Test', 'Test Notification');
        return $resp;
    }
}
