<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'department_id', 
        'code', 
        'name', 
        'sks', 
        'semester', 
        'is_active', 
    ];
}