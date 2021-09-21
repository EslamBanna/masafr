<?php

namespace App\Models\Common;

use App\Models\Masafr\Masafr;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = [
        'sender_type',
        'user_id',
        'masafr_id',
        'from_person',
        'to_person',
        'subject',
        'attach',
        'related_trip',
        'related_request_service'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function masafr()
    {
        return $this->belongsTo(Masafr::class, 'masafr_id', 'id');
    }
}
