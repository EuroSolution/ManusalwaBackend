<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\SendPushNotification;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use ImageKit\ImageKit;
use Exception;
use Twilio\Rest\Client;


trait GeneralHelperTrait
{

    protected function requestValidate($request, $validation, array $messages = []) {
        $validator = Validator::make($request->all(), $validation, $messages);

        if ($validator->fails()) {
            $response = response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => [],
                'errors' => $validator->errors()
            ], 422);
            throw new HttpResponseException($response);
        }
        return $request->all();
    }

    protected function uploadImageIK($fileName, $filePath, $folder){
        $imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_ENDPOINT_URL')
        );

        $uploadFile = $imageKit->uploadFile([
            'file' => base64_encode(file_get_contents($filePath)),
            'fileName' => $fileName,
            'folder' => $folder
        ]);
        if ($uploadFile->error == null){
            return $uploadFile->result->url ?? null;
        }
        return null;
    }

    protected function getImageWithTransformation($imageUrl, $width=0, $height=0){
        try{

            $imageKit = new ImageKit(
                env('IMAGEKIT_PUBLIC_KEY'),
                env('IMAGEKIT_PRIVATE_KEY'),
                env('IMAGEKIT_ENDPOINT_URL')
            );
            return $imageKit->url([
                'src' => $imageUrl,
                'transformation' => [
                    [
                        'height' => $height,
                        'width' => $width
                    ]
                ]
            ]);
        }catch(\Exception $ex){

        }
    }

    protected function sendMessageToClient($receiverNumber, $message){
        try {
            $account_sid = env('TWILIO_ACCOUNT_SID');
            $auth_token = env('TWILIO_AUTH_TOKEN');
            $twilio_number = env('TWILIO_PHONE_NUMBER');

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message]);

            return array('success'=> true, 'message' => 'Message Sent Successfully');

        } catch (Exception $e) {
            return array('success'=> false, 'error' => $e->getMessage());
        }
    }

    protected function sendOtpToClient($receiverNumber, $message){
        try {
            $account_sid        = env('TWILIO_ACCOUNT_SID');
            $auth_token         = env('TWILIO_AUTH_TOKEN');
            $twilio_number      = env('TWILIO_PHONE_NUMBER');
            //$twilio_verify_sid  = getenv("TWILIO_VERIFY_SID");

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message]);

            /*$new_factor = $client->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($receiverNumber, "sms");*/

            return array('status'=> true, 'message' => 'OTP Sent Successfully');

        } catch (Exception $e) {
            return array('status'=> false, 'error' => $e->getMessage());
        }
    }

    protected function verifyClientOtp($receiverNumber, $code){
        try{
            $account_sid        = env('TWILIO_ACCOUNT_SID');
            $auth_token         = env('TWILIO_AUTH_TOKEN');
            $twilio_number      = env('TWILIO_PHONE_NUMBER');
            $twilio_verify_sid  = getenv("TWILIO_VERIFY_SID");

            $client = new Client($account_sid, $auth_token);

            $verified = $client->verify->v2->services($twilio_verify_sid)
                ->verificationChecks
                ->create(['code' => $code, 'to' => $receiverNumber]);

            return array('status' => true, 'message' => 'OTP Matched Successfully');
        }catch(Exception $ex){
            return array('status'=> false, 'error'=>$ex->getMessage());
        }
    }

    protected function sendPushNotification($title, $message, $fcmTokens=array(), $userIds=array()){
        try {
            $userFcmTokens = User::query();
            if (!empty($fcmTokens)){
                $userFcmTokens = $userFcmTokens->whereIn('fcm_token', $fcmTokens);
            }
            if (!empty($userIds)){
                $userFcmTokens = $userFcmTokens->whereIn('id', $fcmTokens);
            }
            $userFcmTokens = $userFcmTokens->whereNotNull('fcm_token')
                ->pluck('fcm_token')->toArray();

            Notification::send(null, new SendPushNotification($title, $message, $userFcmTokens));

            return array('success'=> true, 'message' => 'Notification Sent Successfully');
        }catch (Exception $ex){
            return array('success'=> false, 'error' => $ex->getMessage());
        }
    }

    public function itemSizes(){
        return array(
            'Normal',
            'Grob',
            'Familie',
            'Partry'
        );
    }
}
