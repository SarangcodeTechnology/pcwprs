<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilestoneReport extends Model
{
    use HasFactory;
    protected $connection = 'pcwprs_data';
    protected $table = 'milestone_reports';
    protected $guarded = [];
}
