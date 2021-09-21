<?php

namespace App\Models\Common;

use App\Models\Masafr\Masafr;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $fillable = ['type','user_id','masafr_id','subject','wait','wait_subject'];

    public function User(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function Masafr(){
        return $this->belongsTo(Masafr::class,'masafr_id','id');
    }
}
