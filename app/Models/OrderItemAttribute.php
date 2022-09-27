<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id', 'attribute_item_id', 'attribute_name', 'attribute_item_name'
    ];
    public $timestamps = false;
}
