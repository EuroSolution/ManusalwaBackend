<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string', //|regex:/(0)[0-9]{10}/
            'password' => 'required'
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user != null){
            if (Hash::check($request->password, $user->password)) {
                if ($user->status == 1){
                    Auth::login($user);
                }else{
                    return $this->error("Your Account is InActive", 200, ['resend_otp' => true]);
                }
            }else{
                return $this->error("Invalid Password");
            }
        }else{
            return $this->error("Invalid Credentials");
        }

        $user->api_token =  auth()->user()->createToken('API Token')->plainTextToken;
        $user->save();
        return $this->success($user);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email', //|unique:users
            'phone' => 'required|unique:users',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }
        $otpToken = rand(10000, 99999);
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "phone" => $request->phone,
            "otp" => $otpToken,
            "fcm_token" => $request->fcm_token ?? null
        ]);

        $token = $user->createToken('API Token')->plainTextToken;
        $user->api_token = $token;
        $user->save();
        try{
            $messageBody = env('APP_NAME')."\nOTP token is:$otpToken";
            $this->sendMessageToClient($request->phone, $messageBody);
            //return $this->error($msg);
            $mailData = array(
                'to' => $user->email,
                'text' => $messageBody
            );
            Mail::send('emails.send-otp', $mailData, function ($message) use($mailData){
                $message->to($mailData['to'])->subject("Registration Token");
            });

        }catch (\Exception $ex){
            $this->error($ex->getMessage());
        }

        return $this->success(array("otp" => $otpToken, "api_token" => $token));
    }

    public function resendOtpToken(Request $request){
        if (Auth::check()){
            $user = Auth::user();
        }else{
            $validator = Validator::make($request->all(), [
                'phone' => 'required'
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }
            $user = User::where('phone', $request->phone)->first();
        }
        if ($user != null){
            $otpToken = rand(10000, 99999);
            $user->otp = $otpToken;
            $token = $user->createToken('API Token')->plainTextToken;
            $user->api_token = $token;
            $user->save();

            try{
                $messageBody = env('APP_NAME')."\nOTP token is:$otpToken";
                $this->sendMessageToClient($user->phone, $messageBody);
                $mailData = array(
                    'to' => $user->email,
                    'text' => $messageBody
                );
                Mail::send('emails.send-otp', $mailData, function ($message) use($mailData){
                    $message->to($mailData['to'])->subject("OTP Token");
                });
                return $this->success(array("otp" => $otpToken, "api_token" => $token));
            }catch (\Exception $ex){
                return $this->error($ex->getMessage());
            }
        }
        return $this->error('Invalid phone number', 200, ['phone' => 'Phone number is not registered']);
    }

    public function verifyToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp_token' => 'required',
            'api_token' => 'required'
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 429, [], $validator->errors());
        }
        if ($request->has('otp_token')) {
            $user = User::where("api_token", $request->api_token)->first();
            if (isset($user->otp) && $user->otp == $request->otp_token) {
                $user->api_token = $user->createToken('API Token')->plainTextToken;
                $user->status = 1;
                $user->save();
                Auth::login($user);
                return $this->success($user, 'Token Verified Successfully.');
            } else {
                return $this->error('Invalid OTP Token',422);
            }
        } else {
            return $this->error('OTP Token Required', 422);
        }
    }

    public function updateFcmToken(Request $request){
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required'
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }
        $user = Auth::user();
        $user->fcm_token = $request->fcm_token;
        $user->save();
        return $this->success($user, 'FCM Token Updated Successfully.');
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();

        return $this->success([], 'Successfully logged out');
    }

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user != null){
            $otpToken = rand(10000, 99999);
            $user->otp = $otpToken;
            $user->api_token = $user->createToken('API Token')->plainTextToken;
            $user->save();

            $data = array('otp' => $user->otp, 'api_token' => $user->api_token);
            try{
                $messageBody = env('APP_NAME')."\nOTP token is:$otpToken";
                $this->sendMessageToClient($user->phone, $messageBody);
                $mailData = array(
                    'to' => $user->email,
                    'text' => $messageBody
                );
                Mail::send('emails.send-otp', $mailData, function ($message) use($mailData){
                    $message->to($mailData['to'])->subject("OTP Token");
                });
                return $this->success($data);
            }catch (\Exception $ex){
                return $this->error($ex->getMessage());
            }
        }else{
            return $this->error('Your Phone is not registered. Please Signup', 200);
        }
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|confirmed',
            'api_token' => 'required',
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }
        $user = User::where('api_token', $request->api_token)->first();
        if ($user != null){
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->success([], 'Password Updated Successfully');
        }else{
            return $this->error('Invalid User');
        }
    }

    public function forgotPasswordWeb(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user != null){
            try{
                $user->api_token = $user->createToken('API Token')->plainTextToken;
                $user->save();
                $mailData = array(
                    'to' => $user->email,
                    'name' => $user->name,
                    'url' => env('WEB_URL').'reset-password?token='.$user->api_token
                );
                Mail::send('emails.password-reset', $mailData, function ($message) use($mailData){
                    $message->to($mailData['to'])->subject("Reset Password");
                });
                return $this->success([], "We have sent you a password reset email");
            }catch (\Exception $ex){
                return $this->error($ex->getMessage());
            }
        }else{
            return $this->error('Your Phone is not registered');
        }
    }

    public function unauthenticatedUser(){
        return $this->error('Unauthorized', 401);
    }
}
