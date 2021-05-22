<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traimaasik extends Model
{
    use HasFactory;
    protected $connection = "pcwprs_data";
    protected $table = "traimaasik";
    public $timestamps = false;
    public function mahina(){
        return $this->hasMany(Mahina::class,'traimaasik_id');
    }
}
