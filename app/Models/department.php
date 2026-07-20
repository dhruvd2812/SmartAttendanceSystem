<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'code',
        'hod_name',
        'email',
        'phone',
        'description'
    ];


    // Relationship With Students

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}