<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chatbot_questions', function (Blueprint $table) {

            $table->text('question')->after('id');

            $table->text('answer')->after('question');

            $table->string('category')->nullable()->after('answer');

            $table->text('keywords')->nullable()->after('category');

            $table->boolean('status')->default(true)->after('keywords');
        });
    }

    public function down(): void
    {
        Schema::table('chatbot_questions', function (Blueprint $table) {

            $table->dropColumn([
                'question',
                'answer',
                'category',
                'keywords',
                'status',
            ]);
        });
    }
};