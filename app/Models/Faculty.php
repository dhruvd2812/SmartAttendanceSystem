<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'designation',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}