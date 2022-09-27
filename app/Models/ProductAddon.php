<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'addon_item_id', 'price', 'discounted_price'
    ];
    public $timestamps = false;

    public function addon(){
        return $this->hasOne(AddonItem::class, 'id', 'addon_item_id');
    }
}
