<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 'product_id', 'price', 'size', 'quantity', 'deal_id', 'deal_item_id'
    ];

    public function cartItemAddons(){
        return $this->hasMany(CartItemAddon::class, 'cart_item_id', 'id');
    }

    public function cartAttributes(){
        return $this->hasMany(CartAttribute::class, 'cart_item_id', 'id');
    }
}
