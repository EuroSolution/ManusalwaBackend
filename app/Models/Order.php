<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_number', 'user_id', 'subtotal', 'tax', 'delivery_fee', 'discount', 'total_amount',
        'notes', 'name', 'email', 'phone', 'address', 'nearest_landmark', 'location', 'order_type',
        'order_status', 'coupon_id', 'is_deal', 'deal_id'
    ];

    public function orderItems(){
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function payment(){
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    public function coupon(){
        return $this->hasOne(Coupon::class, 'id', 'coupon_id');
    }
}
