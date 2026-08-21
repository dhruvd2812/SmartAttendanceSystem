<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentClass extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Database Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'student_classes';


    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'student_id',
        'subject_id',
        'academic_year',
        'semester',
    ];


    /*
    |--------------------------------------------------------------------------
    | Student Relationship
    |--------------------------------------------------------------------------
    */

    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Subject Relationship
    |--------------------------------------------------------------------------
    */

    public function subject(): BelongsTo
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id',
            'id'
        );
    }
}