<?php

namespace App\Models\Masafr;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masafr extends Authenticatable implements JWTSubject
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
        'country_code',
        'phone',
        'validation_code',
        'active',
        'active_try',
        'national_id_number',
        'nationality',
        'car_name',
        'car_model',
        'car_number',
        'car_image_east',
        'car_image_west',
        'car_image_north',
        'driving_license_photo',
        'trips_count',
        'bargains_count',
        'negative_points_count',
        'sms_notifications',
        'email_notifications',
        'balance'
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    // public function setPasswordAttribute($value){
    //     $this->attributes['password'] = bcrypt($value);
    // }

}
