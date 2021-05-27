<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;
    protected $connection = "pcwprs_data";
    protected $table = "submissions";

    public function requestedBy(){
        return $this->belongsTo(User::class,'requested_by','id');
    }
    public function submittedBy(){
        return $this->belongsTo(User::class,'submitted_by','id');
    }
    public function mahina(){
        return $this->belongsTo(Mahina::class,'mahina_id','id');
    }
    public function traimaasik(){
        return $this->belongsTo(Traimaasik::class,'traimaasik_id','id');
    }
    public function aayojana(){
        return $this->belongsTo(Aayojana::class,'aayojana_id','id')->with('aarthikBarsa');;
    }

    public function kaaryalaya(){
        return $this->belongsTo(Kaaryalaya::class,'kaaryalaya_id','id');
    }




}
