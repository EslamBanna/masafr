<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
{
    use HasFactory;

    protected $table = 'request_services';
    protected $fillable = [
        'user_id',
        'type_of_trips',
        'type_of_services',
        'from_place',
        'from_longitude',
        'from_latitude',
        'to_place',
        'to_longitude',
        'to_latitude',
        'max_day',
        'delivery_to',
        'photo',
        'description',
        'only_women',
        'have_insurance',
        'website_service',
        'number_of_passengers',
        'type_of_car',
        'on_progress'
    ];
}
