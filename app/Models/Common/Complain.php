<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory;

    protected $table = 'complains';
    protected $fillable = ['type', 'user_id','masafr_id','subject','attach','status'];

    public function complainList(){
        return $this->hasMany(ComplainList::class,'complain_id','id');
    }

}
