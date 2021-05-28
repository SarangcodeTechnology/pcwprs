<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriyakalapMaasikPragati extends Model
{
    use HasFactory;
    protected $connection = 'pcwprs_data';
    protected $table = 'kriyakalap_maasik_pragati';
    protected $guarded = [];
    public function kriyakalapLakshya(){
        return $this->belongsTo(KriyakalapLakshya::class,'kriyakalap_lakshya_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function mahina(){
        return $this->belongsTo(Mahina::class,'mahina_id','id');
    }
}
