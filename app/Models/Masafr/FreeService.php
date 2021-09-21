<?php

namespace App\Models\Masafr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeService extends Model
{
    use HasFactory;

    protected $table = 'free_services';
    protected $fillable = ['masafr_id','type','photo','description','active'];
    // public $timestamps = true;

    public function masafr(){
        return $this->belongsTo(Masafr::class,'masafr_id','id');
    }

    public function ways(){
        return $this->hasMany(FreeServicePlace::class,'free_service_id','id');
    }
}
