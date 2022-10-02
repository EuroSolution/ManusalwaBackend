<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_id', 'product_id', 'price', 'size', 'quantity', 'deal_id', 'deal_item_id'
    ];

    public function orderItemAddons(){
        return $this->hasMany(OrderItemAddon::class, 'order_item_id', 'id');
    }
    public function orderItemAttributes(){
        return $this->hasMany(OrderItemAttribute::class, 'order_item_id', 'id');
    }

    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
