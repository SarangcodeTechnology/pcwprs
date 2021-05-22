<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aayojana extends Model
{
    use HasFactory;
    protected $connection = 'pcwprs_data';
    protected $table = 'aayojana';
    protected $guarded = [];
    public function aarthikBarsa(){
        return $this->belongsTo(AarthikBarsa::class,'aarthik_barsa_id');
    }
}
