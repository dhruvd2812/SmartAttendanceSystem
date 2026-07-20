<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [

        'enrollment_no',

        'first_name',

        'last_name',

        'gender',

        'dob',

        'mobile',

        'email',

        'address',

        'department_id',

        'semester',

        'academic_year',

        'photo',

        'qr_unique_id',

        'status'

    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}