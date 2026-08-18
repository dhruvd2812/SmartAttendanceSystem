<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Department extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Database Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'departments';


    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'department_name',
        'code',
        'department_code',
        'hod_name',
        'email',
        'phone',
        'description',
    ];


    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    |
    | One Department has many Students.
    |
    */

    public function students(): HasMany
    {
        return $this->hasMany(
            Student::class,
            'department_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Faculties
    |--------------------------------------------------------------------------
    |
    | One Department has many Faculty members.
    |
    */

    public function faculties(): HasMany
    {
        return $this->hasMany(
            Faculty::class,
            'department_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    |
    | One Department has many Subjects.
    |
    */

    public function subjects(): HasMany
    {
        return $this->hasMany(
            Subject::class,
            'department_id',
            'id'
        );
    }
}