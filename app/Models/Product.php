<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id', 'name', 'description', 'slug', 'image', 'status', 'price', 'type'
    ];

    public function category(){
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function sizes(){
        return $this->hasMany(ProductSize::class, 'product_id', 'id');
    }

    public function addons(){
        return $this->hasMany(ProductAddon::class, 'product_id', 'id');
    }

    public function productAttributes(){
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id');
    }

    public function addonGroups(){
        return $this->hasManyThrough(AddonGroup::class,AddonItem::class,  'addon_group_id', 'category_id', 'category_id');
    }

    public function addonItems()
    {
        return $this->hasManyThrough(AddonItem::class, AddonGroup::class, 'category_id', 'addon_group_id', 'category_id', 'id');
    }

    public function attributeItems()
    {
        return $this->hasManyThrough(AttributeItem::class, Attribute::class, 'category_id', 'attribute_id', 'category_id', 'id');
    }

    public function attributes(){
        return $this->hasMany(Attribute::class, 'category_id', 'category_id');
    }

    public function getProductionsByCategoryId($categoryId ,$page = 1, $limit = 10){

        $start = ($page > 1) ? $limit*($page-1) : 0;

        return $this->with('sizes')->withCount('sizes', 'addonItems as addons_count', 'attributeItems as attributes_count')
            ->where('category_id',$categoryId)
            ->skip($start)
            ->take($limit)
            ->get();
    }
}
