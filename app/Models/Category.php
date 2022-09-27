<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id', 'name', 'slug', 'image'
    ];

    public function sub_category(){
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function parent_category(){
        return $this->hasMany(self::class, 'id', 'parent_id');
    }

    public function products(){
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
