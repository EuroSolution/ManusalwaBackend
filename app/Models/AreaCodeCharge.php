<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaCodeCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_code', 'address', 'delivery_charge', 'min_amount'
    ];

    public $timestamps = false;
}
