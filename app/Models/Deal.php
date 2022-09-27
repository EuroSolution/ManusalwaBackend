<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name', 'description', 'price', 'image', 'slug'
    ];

    public function dealItems(){
        return $this->hasMany(DealItem::class, 'deal_id', 'id');
    }

    public function dealAddons(){
        return $this->hasMany(DealAddon::class, 'deal_id', 'id');
    }
}
