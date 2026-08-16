<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    /**
     * Database table.
     */
    protected $table = 'students';

    /**
     * Primary key.
     */
    protected $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected $fillable = [
        'enrollment_no',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'mobile',
        'email',
        'address',
        'department_id',
        'semester',
        'academic_year',
        'photo',
        'qr_unique_id',
        'status',
    ];

    /**
     * Student belongs to a Department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'id'
        );
    }

    /**
     * Student has one User account.
     */
    public function user(): HasOne
    {
        return $this->hasOne(
            User::class,
            'student_id',
            'id'
        );
    }

    /**
 * Student has many attendance records.
 */
public function attendances(): HasMany
{
    return $this->hasMany(
        Attendance::class,
        'student_id',
        'id'
    );
}
/**
 * Student has many class assignments.
 */
public function studentClasses(): HasMany
{
    return $this->hasMany(
        StudentClass::class,
        'student_id',
        'id'
    );
}
}