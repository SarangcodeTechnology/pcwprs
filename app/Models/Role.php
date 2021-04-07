<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $hidden = ['pivot'];

    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function getDateAttribute(){
        return date("Y/m/d", strtotime($this->created_at));
    }
    protected $appends = ['date'];

}
