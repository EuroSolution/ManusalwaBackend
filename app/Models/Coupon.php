<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Coupon extends Model
{
    protected $table = "coupons";

    protected $fillable = [
        'user_id', 'code', 'value', 'type', 'expiration_date', 'status', 'min_order','all_users'
    ];

    public function couponUsers(){
        return $this->hasMany(CouponUser::class, 'coupon_id', 'id');
    }

    public function couponUser(){
        return $this->hasMany(CouponUser::class, 'coupon_id', 'id')
            ->where('user_id', Auth::id());
    }

    public static function validateCoupon($couponCode, $orderAmt){
        $isValidCoupon = false;
        $couponError = false;
        $couponErrorMsg = "Coupon Validated";
        $discount = 0;

        if ($couponCode != null || $couponCode != ''){
            $coupon = Coupon::with('couponUser')
                ->whereHas('couponUser', function ($q){
                    $q->where('user_id', Auth::id());//->where('availed', 0);
                })->where('code', $couponCode)->whereStatus(1)->first();

            if ($coupon != null){
                if($coupon->expiration_date != null && $coupon->expiration_date < date('Y-m-d')){
                    $couponError = true;
                    $couponErrorMsg = "Voucher has been Expired.";
                }
                if ($coupon->min_order > $orderAmt){
                    $couponError = true;
                    $couponErrorMsg = "Minimum order amount must be ".$coupon->min_order;
                }
                if ($coupon->couponUser[0]->used >= $coupon->couponUser[0]->usage){
                    $couponError = true;
                    //$couponErrorMsg = "Voucher limit has reached.";
                    $couponErrorMsg = "Invalid Voucher";
                }
            }else{
                $couponError = true;
                $couponErrorMsg = "Invalid Voucher";
            }
        }else{
            $couponError = true;
            $couponErrorMsg = "Invalid Voucher";
        }

        if ($couponError == false){
            if ($coupon->type == 'value'){
                $discount = $coupon->value;
            }else{
                $discount = ($orderAmt /100) * $coupon->value;
            }
            $isValidCoupon = true;
        }

        return array('isValidCoupon' => $isValidCoupon, 'couponErrorMsg' => $couponErrorMsg,
            'discount' => $discount, 'voucher' => $coupon ?? null);

    }

    public static function availedVoucher($couponId){
        $data = CouponUser::where('coupon_id', $couponId)->where('user_id', Auth::id())->first();
        if ($data != null){
            $data->used = $data->used+1;
            $data->save();
            if ($data->usage == $data->used){
                $data->availed = 1;
                $data->save();
            }
            return true;
        }
        return false;
    }
}
