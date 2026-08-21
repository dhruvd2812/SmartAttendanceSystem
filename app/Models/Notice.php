<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'posted_by',
        'role',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}