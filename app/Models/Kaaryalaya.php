<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaaryalaya extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'kaaryalaya';
    public function getDateAttribute(){
        return date("Y/m/d", strtotime($this->created_at));
    }
    protected $appends = ['date'];
}
