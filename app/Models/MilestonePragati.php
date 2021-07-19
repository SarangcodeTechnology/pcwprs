<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilestonePragati extends Model
{
    use HasFactory;
    protected $connection = 'pcwprs_data';
    protected $table = 'milestone_pragati';
    protected $guarded = [];
    public function milestoneLakshya(){
        return $this->belongsTo(MilestoneLakshya::class,'milestone_lakshya_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function mahina(){
        return $this->belongsTo(Mahina::class,'mahina_id','id');
    }
}
