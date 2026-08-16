<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $table = 'attendance_sessions';

    protected $fillable = [
        'subject_id',
        'faculty_id',
        'lecture_date',
        'start_time',
        'end_time',
        'lecture_name',
        'qr_token',
        'qr_expires_at',
        'status',
    ];

    protected $casts = [
        'lecture_date' => 'date',
        'qr_expires_at' => 'datetime',
    ];

    /**
     * Subject for this lecture.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Faculty who conducted the lecture.
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Attendance records for this lecture.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(
            Attendance::class,
            'attendance_session_id'
        );
    }
}