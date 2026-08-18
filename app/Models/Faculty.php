<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'faculty_name',
        'employee_id',
        'email',
        'phone',
        'department_id',
    ];


    /*
    |--------------------------------------------------------------------------
    | Department Relationship
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
    | User Relationship
    |--------------------------------------------------------------------------
    |
    | One faculty has one login user.
    |
    */

    public function user()
    {
        return $this->hasOne(
            User::class,
            'faculty_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Students Relationship
    |--------------------------------------------------------------------------
    |
    | Faculty can access students from their department.
    |
    */

    public function students()
    {
        return $this->hasMany(
            Student::class,
            'department_id',
            'department_id'
        );
    }
}