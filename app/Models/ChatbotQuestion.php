<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotQuestion extends Model
{
    protected $table = 'chatbot_questions';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'keywords',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}