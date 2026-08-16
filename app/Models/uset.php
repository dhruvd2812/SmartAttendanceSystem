<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id',
        'faculty_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * User belongs to a Student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id',
            'id'
        );
    }

    /**
     * User belongs to a Faculty.
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(
            Faculty::class,
            'faculty_id',
            'id'
        );
    }
}