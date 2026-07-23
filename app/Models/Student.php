<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    /**
     * Table Name
     */
    protected $table = 'students';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * Mass Assignable Fields
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
     * Department Relationship
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}