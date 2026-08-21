<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Database Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'subjects';


    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'code',
        'faculty_id',
        'department_id',
        'semester',
        'description',
    ];


    /*
    |--------------------------------------------------------------------------
    | Faculty Relationship
    |--------------------------------------------------------------------------
    */

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(
            Faculty::class,
            'faculty_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Department Relationship
    |--------------------------------------------------------------------------
    */

    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Student Classes Relationship
    |--------------------------------------------------------------------------
    */

    public function studentClasses(): HasMany
    {
        return $this->hasMany(
            StudentClass::class,
            'subject_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Attendance Sessions Relationship
    |--------------------------------------------------------------------------
    */

    public function attendanceSessions(): HasMany
    {
        return $this->hasMany(
            AttendanceSession::class,
            'subject_id',
            'id'
        );
    }
}