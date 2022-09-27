<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'cart_count', 'subtotal', 'tax', 'delivery_fee', 'discount', 'total_amount',
        'voucher_code', 'notes', 'is_deal', 'deal_id'
    ];

    public function cartItems(){
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }
}
