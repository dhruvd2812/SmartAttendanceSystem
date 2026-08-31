<?php

namespace App\Models;

use App\Support\PersonName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
        'faculty_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden Attributes
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Display Name
    |--------------------------------------------------------------------------
    |
    | Some accounts store an email address in the `name` column, so never echo
    | it raw. See App\Support\PersonName for the formatting rules.
    |
    */

    public function getDisplayNameAttribute(): string
    {
        $name = PersonName::human($this->attributes['name'] ?? null, '');

        if ($name !== '') {
            return $name;
        }

        return PersonName::human($this->attributes['email'] ?? null, 'User');
    }

    /*
    |--------------------------------------------------------------------------
    | Initial (for avatar bubbles)
    |--------------------------------------------------------------------------
    */

    public function getInitialAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->display_name, 0, 1));
    }

    /*
    |--------------------------------------------------------------------------
    | Student Relationship
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Faculty Relationship
    |--------------------------------------------------------------------------
    */

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
}
