<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotification(Request $request){
        $resp = $this->sendPushNotification($request->title, $request->message, [$request->fcm_token]);
        if ($resp['success'] == true){
            $this->success([], "Notification Sent Successfully");
        }else{
            $this->error($resp['error']);
        }
    }
}
