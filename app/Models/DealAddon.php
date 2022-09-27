<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealAddon extends Model
{
    use HasFactory;
    protected $fillable = [
        'deal_id', 'addon_group_id', 'addon_item_id', 'addon_group_name', 'addon_item_name', 'quantity'
    ];
}
