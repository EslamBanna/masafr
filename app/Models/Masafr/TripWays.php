<?php

namespace App\Models\Masafr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripWays extends Model
{
    use HasFactory;

    protected $table = 'trip_ways';
    protected $fillable = [
        'trip_id',
        'place',
        'time'
    ];
}
