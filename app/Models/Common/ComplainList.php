<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common\Complain;
class ComplainList extends Model
{
    use HasFactory;
    protected $table = 'complain_lists';
    protected $fillable = ['type','subject','attach','complain_id'];

    public function Complain(){
        return $this->belongsTo(Complain::class,'complain_id','id');
    }
}
