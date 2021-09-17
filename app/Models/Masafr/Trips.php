<?php

namespace App\Models\Masafr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    use HasFactory;
    protected $table = 'trips';
    protected $fillable = [
        'type_of_trips',
        'type_of_services',
        'only_women',
        'from_place',
        'to_place',
        'start_date',
        'end_date',
        'description',
        'active'
    ];
}
