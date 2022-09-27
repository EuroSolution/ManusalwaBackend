<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'deal_id', 'product_id', 'category_id', 'product_name', 'category_name', 'size', 'quantity'
    ];
}
