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

    public function addonGroup(){
        return $this->hasOne(AddonGroup::class, 'id', 'addon_group_id');
    }
}
