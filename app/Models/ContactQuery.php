<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactQuery extends Model
{
    use HasFactory;
    protected $table = "contact_queries";
    protected $fillable = [
        'name', 'email', 'message', 'status'
    ];
}
