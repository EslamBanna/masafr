<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotifiactions extends Model
{
    use HasFactory;

    protected $table = 'admin_notifications';
    protected $fillable = ['type','person_id','subject'];
}
