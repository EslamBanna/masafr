<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatNotification extends Model
{
    use HasFactory;
    protected $table = 'chat_notifications';
    protected $fillable = ['user_id','masafr_id','notification_code'];
}
