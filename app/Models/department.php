<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    /**
     * Database Table
     */
    protected $table = 'departments';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * Mass Assignable Fields
     */
    protected $fillable = [
        'name',
        'code',
        'hod_name',
        'email',
        'phone',
        'description',
    ];

    /**
     * One Department has many Students
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'department_id', 'id');
    }
}