<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItemAddon extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_item_id', 'addon_item_id', 'addon_group', 'addon_item', 'price', 'quantity'
    ];
}
