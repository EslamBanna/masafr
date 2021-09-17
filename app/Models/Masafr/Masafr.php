<?php

namespace App\Models\Masafr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masafr extends Model
{
    use HasFactory;

    protected $table = 'masafr';
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'id_Photo',
        'gender',
        'phone',
        'validation_code',
        'active',
        'active_try',
        'national_id_number',
        'nationality',
        'car_id',
        'car_name',
        'car_model',
        'car_number',
        'driving_license_photo'
    ];


}
