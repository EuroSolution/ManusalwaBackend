<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_item_id', 'attribute_item_id', 'attribute_name', 'attribute_item'
    ];
}
