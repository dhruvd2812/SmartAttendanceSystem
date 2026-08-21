<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'day',
        'start_time',
        'end_time',
        'subject_id',
        'faculty_id',
        'department_id',
        'semester',
        'room',
    ];

    /**
     * Timetable belongs to a Subject.
     */
    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }

    /**
     * Timetable belongs to a Faculty.
     */
    public function faculty()
    {
        return $this->belongsTo(
            Faculty::class,
            'faculty_id'
        );
    }

    /**
     * Timetable belongs to a Department.
     */
    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }
}