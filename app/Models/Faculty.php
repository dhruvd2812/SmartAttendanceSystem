<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = [
        'faculty_name',
        'employee_id',
        'email',
        'phone',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}