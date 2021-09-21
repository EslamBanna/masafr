<?php

namespace App\Models\Masafr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    use HasFactory;
    protected $table = 'trips';
    protected $fillable = [
        'masafr_id',
        'type_of_trips',
        'type_of_services',
        'only_women',
        'from_place',
        'from_longitude',
        'from_latitude',
        'to_place',
        'to_longitude',
        'to_latitude',
        'start_date',
        'end_date',
        'description',
        'active',
        'negotiations',
        'on_progress'
    ];

    public function masafr(){
        return $this->belongsTo(Masafr::class,'masafr_id','id');
    }

    public function ways(){
        return $this->hasMany(TripWays::class,'trip_id','id');
    }

    public function days(){
        return $this->hasMany(TripDays::class,'trip_id','id');
    }


}
