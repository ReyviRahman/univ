<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    protected $fillable = [
        'academic_year', 
        'semester_type',
        'is_active',
    ];
}
