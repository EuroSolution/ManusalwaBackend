<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItemAddon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_item_id', 'addon_item_id', 'addon_group', 'addon_item', 'price', 'quantity'
    ];
}
