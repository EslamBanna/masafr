<?php

namespace App\Models\Masafr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeServicePlace extends Model
{
    use HasFactory;
    protected $table = 'free_service_places';
    protected $fillable = ['free_service_id','place'];
}
