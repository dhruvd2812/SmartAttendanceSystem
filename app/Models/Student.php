<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'enrollment_no',
        'first_name',
        'last_name',
        'email',
        'gender',
        'dob',
        'mobile',
        'address',
        'department_id',
        'semester',
        'academic_year',
        'photo',
        'status',
    ];


    /*
    |--------------------------------------------------------------------------
    | Full Name
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        return trim(
            $this->first_name . ' ' . $this->last_name
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Department
    |--------------------------------------------------------------------------
    */

    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->hasOne(
            User::class,
            'student_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    public function attendances()
    {
        return $this->hasMany(
            Attendance::class,
            'student_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Student Classes
    |--------------------------------------------------------------------------
    */

    public function studentClasses()
    {
        return $this->hasMany(
            StudentClass::class,
            'student_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    |
    | Student has many subjects through student_classes.
    |
    */

    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'student_classes',
            'student_id',
            'subject_id'
        );
    }
}
