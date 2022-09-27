<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'attribute_item_id', 'attribute_id'
    ];
    public $timestamps = false;

    public function attribute(){
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    public function attributeItem(){
        return $this->hasOne(AttributeItem::class, 'id', 'attribute_item_id');
    }
}
