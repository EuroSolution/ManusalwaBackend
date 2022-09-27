<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'receiver_id', 'title', 'data', 'read_at', 'type', 'source_id', 'source_model'
    ];

    public static function sendNotification($receiverId, $title, $data, $type, $modelId=0, $model=""){
        return Notification::create([
            'receiver_id' => $receiverId,
            'title' => $title,
            'data' => $data,
            'type' => $type,
            'source_id' => $modelId,
            'source_model' => $model ?? $type
        ]);
    }
}
