<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AarthikBarsa extends Model
{
    use HasFactory;
    protected $connection = 'pcwprs_data';
    protected $table = 'aarthik_barsa';

    public function getDateAttribute(){
        return date("Y/m/d", strtotime($this->created_at));
    }
    public function aayojana(){
        return $this->hasMany(Aayojana::class,'aarthik_barsa_id');
    }
    protected $appends = ['date'];
}
