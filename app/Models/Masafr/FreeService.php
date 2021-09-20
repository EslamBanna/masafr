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
}
