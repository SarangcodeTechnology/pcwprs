<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahina extends Model
{
    use HasFactory;
    protected $connection = "pcwprs_data";
    protected $table = "mahina";
    public $timestamps = false;
    public function traimaasik(){
        return $this->belongsTo(Traimaasik::class,'traimaasik_id');
    }

    public function kriyakalapMaasikPragati(){
        return $this->hasMany(KriyakalapMaasikPragati::class,'mahina_id','id');
    }
}
