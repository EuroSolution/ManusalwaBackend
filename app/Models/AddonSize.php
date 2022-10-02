<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonSize extends Model
{
    use HasFactory;
    protected $fillable = [
        'addon_item_id', 'size', 'price', 'discounted_price'
    ];
    public $timestamps = false;
}
