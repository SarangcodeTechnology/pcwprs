<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lock extends Model
{
    use HasFactory;
    public function kaaryalaya(){
        return $this->belongsTo(Kaaryalaya::class,'kaaryalaya_id');
    }
}
