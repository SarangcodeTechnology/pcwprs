<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriyakalapTraimaasikPragati extends Model
{
    use HasFactory;
    protected $connection = 'pcwprs_data';
    protected $table = 'kriyakalap_traimaasik_pragati';
    protected $guarded = [];
    public function kriyakalapLakshya(){
        return $this->belongsTo(KriyakalapLakshya::class,'kriyakalap_lakshya_id');
    }
}
