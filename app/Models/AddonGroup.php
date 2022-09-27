<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'image'
    ];

    public function addonItems(){
        return $this->hasMany(AddonItem::class, 'addon_group_id', 'id');
    }
}
