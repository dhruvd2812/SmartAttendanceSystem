<?php

namespace App\Models;

use App\Support\PersonName;
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
    | Display Name
    |--------------------------------------------------------------------------
    |
    | Faculty records created from a login account can end up holding an email
    | address in `faculty_name`, so never echo it raw.
    |
    */

    public function getDisplayNameAttribute(): string
    {
        return PersonName::human(
            $this->attributes['faculty_name'] ?? null,
            'Faculty Member'
        );
    }


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