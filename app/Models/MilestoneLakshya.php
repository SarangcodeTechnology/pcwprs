<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilestoneLakshya extends Model
{
    use HasFactory;
    protected $connection = 'pcwprs_data';
    protected $table = 'milestone_lakshya';
    protected $guarded = ['id'];
    public function aayojana(){
        return $this->belongsTo(Aayojana::class,'aayojana_id');
    }

}
