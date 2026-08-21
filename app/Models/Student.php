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
        'department_id',
        'semester',
        'status',
    ];


    /*
    |--------------------------------------------------------------------------
    | Accessor: Full Name
    |--------------------------------------------------------------------------
    |
    | Allows us to use:
    |
    | $student->full_name
    |
    */

    public function getFullNameAttribute()
    {
        return trim(
            $this->first_name . ' ' . $this->last_name
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Department Relationship
    |--------------------------------------------------------------------------
    |
    | A student belongs to one department.
    |
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
    | User Relationship
    |--------------------------------------------------------------------------
    |
    | Each student has one login account.
    |
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
    | Attendance Relationship
    |--------------------------------------------------------------------------
    |
    | A student can have many attendance records.
    |
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
    | Student Classes Relationship
    |--------------------------------------------------------------------------
    |
    | A student can be enrolled in multiple classes/subjects.
    |
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
    | Subjects Relationship
    |--------------------------------------------------------------------------
    |
    | This gives direct access to the student's subjects
    | through the student_classes table.
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