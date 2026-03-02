<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 
        'department_id',
        'advisor_id',
        'nim',
        'name',
        'pob',
        'dob',
        'gender',
        'phone',
        'address',
        'entry_year',
        'status',
    ];
}