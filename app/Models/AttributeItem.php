<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'attribute_id', 'name', 'image'
    ];

    public function attribute(){
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }
}
