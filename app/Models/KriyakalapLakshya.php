<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriyakalapLakshya extends Model
{
    use HasFactory;
    protected $connection = 'pcwprs_data';
    protected $table = 'kriyakalap_lakshya';
    protected $guarded = ['id'];
    public function aayojana(){
        return $this->belongsTo(Aayojana::class,'aayojana_id');
    }

    public function getNameWithKriyakalapCodeAttribute()
    {
        return "{$this->kriyakalap_code} - {$this->name}";
    }

    public function maasikPragati(){
        return $this->hasOne(KriyakalapMaasikPragati::class,'kriyakalap_lakshya_id');
    }

    public function traimaasikPragati(){
        return $this->hasOne(KriyakalapTraimaasikPragati::class,'kriyakalap_lakshya_id');
    }

    protected $appends = ['name_with_kriyakalap_code'];
}
