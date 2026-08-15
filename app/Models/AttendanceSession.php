<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    protected $table = 'attendance_sessions';

    protected $fillable = [
        'faculty_id',
        'subject_id',
        'department_id',
        'semester',
        'session_date',
        'starts_at',
        'expires_at',
        'token',
        'status',
    ];

    protected $casts = [
        'session_date' => 'date',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(
            Faculty::class,
            'faculty_id'
        );
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(
            Attendance::class,
            'attendance_session_id'
        );
    }
}