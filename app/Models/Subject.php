<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * Database table.
     */
    protected $table = 'subjects';

    /**
     * Mass assignable fields.
     */
    protected $fillable = [
        'name',
        'code',
        'faculty_id',
        'department_id',
        'semester',
        'description',
    ];

    /**
     * Subject belongs to a faculty.
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }

    /**
     * Subject belongs to a department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Subject has many student-class records.
     */
    public function studentClasses(): HasMany
    {
        return $this->hasMany(StudentClass::class, 'subject_id', 'id');
    }

    /**
     * Subject has many attendance sessions.
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