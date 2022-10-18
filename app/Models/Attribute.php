<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'category_id'];

    public function attributeItems(){
        return $this->hasMany(AttributeItem::class, 'attribute_id', 'id');
    }

    public function category(){
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
