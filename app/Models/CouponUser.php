<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'coupon_id', 'user_id', 'availed'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public $timestamps = false;
}
