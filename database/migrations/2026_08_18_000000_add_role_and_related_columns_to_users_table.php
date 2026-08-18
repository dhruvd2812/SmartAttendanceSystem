<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('student')->after('password');
            }

            if (!Schema::hasColumn('users', 'student_id')) {
                $table->foreignId('student_id')
                    ->nullable()
                    ->after('role')
                    ->constrained('students')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'faculty_id')) {
                $table->foreignId('faculty_id')
                    ->nullable()
                    ->after('student_id')
                    ->constrained('faculties')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'faculty_id')) {
                $table->dropForeign(['faculty_id']);
                $table->dropColumn('faculty_id');
            }

            if (Schema::hasColumn('users', 'student_id')) {
                $table->dropForeign(['student_id']);
                $table->dropColumn('student_id');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
