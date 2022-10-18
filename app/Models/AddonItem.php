<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'addon_group_id', 'name', 'description', 'price', 'discounted_price', 'image'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'price',
        'discounted_price',
        'description'
    ];

    public function addonGroup(){
        return $this->hasOne(AddonGroup::class, 'id', 'addon_group_id');
    }

    public function addonSizes(){
        return $this->hasMany(AddonSize::class, 'addon_item_id', 'id');
    }
}
