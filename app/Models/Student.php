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
        'name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'department_id',
    ];


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
    | Each student has one login account in users table.
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
    */

    public function studentClasses()
    {
        return $this->hasMany(
            StudentClass::class,
            'student_id'
        );
    }
}