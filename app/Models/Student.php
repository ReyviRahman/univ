<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}