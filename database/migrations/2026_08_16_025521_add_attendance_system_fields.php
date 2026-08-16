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
        /*
        |--------------------------------------------------------------------------
        | Subjects
        |--------------------------------------------------------------------------
        */

        Schema::table('subjects', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('code')->unique()->after('name');
            $table->foreignId('faculty_id')
                ->nullable()
                ->after('code')
                ->constrained('faculties')
                ->nullOnDelete();
            $table->foreignId('department_id')
                ->nullable()
                ->after('faculty_id')
                ->constrained('departments')
                ->nullOnDelete();
            $table->unsignedTinyInteger('semester')
                ->nullable()
                ->after('department_id');
            $table->text('description')
                ->nullable()
                ->after('semester');
        });


        /*
        |--------------------------------------------------------------------------
        | Student Classes
        |--------------------------------------------------------------------------
        */

        Schema::table('student_classes', function (Blueprint $table) {
            $table->foreignId('student_id')
                ->after('id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->after('student_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->string('academic_year')
                ->nullable()
                ->after('subject_id');

            $table->string('semester')
                ->nullable()
                ->after('academic_year');
        });


        /*
        |--------------------------------------------------------------------------
        | Attendance Sessions
        |--------------------------------------------------------------------------
        */

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->foreignId('subject_id')
                ->after('id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->foreignId('faculty_id')
                ->nullable()
                ->after('subject_id')
                ->constrained('faculties')
                ->nullOnDelete();

            $table->date('lecture_date')
                ->after('faculty_id');

            $table->time('start_time')
                ->nullable()
                ->after('lecture_date');

            $table->time('end_time')
                ->nullable()
                ->after('start_time');

            $table->string('lecture_name')
                ->nullable()
                ->after('end_time');

            $table->string('qr_token')
                ->nullable()
                ->unique()
                ->after('lecture_name');

            $table->timestamp('qr_expires_at')
                ->nullable()
                ->after('qr_token');

            $table->enum('status', ['active', 'closed'])
                ->default('active')
                ->after('qr_expires_at');
        });


        /*
        |--------------------------------------------------------------------------
        | Attendances
        |--------------------------------------------------------------------------
        */

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('student_id')
                ->after('id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('attendance_session_id')
                ->after('student_id')
                ->constrained('attendance_sessions')
                ->cascadeOnDelete();

            $table->enum('status', ['present', 'absent', 'late'])
                ->default('present')
                ->after('attendance_session_id');

            $table->timestamp('marked_at')
                ->nullable()
                ->after('status');

            $table->string('remarks')
                ->nullable()
                ->after('marked_at');

            $table->unique([
                'student_id',
                'attendance_session_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Attendances
        |--------------------------------------------------------------------------
        */

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendances_student_id_attendance_session_id_unique');

            $table->dropForeign(['student_id']);
            $table->dropForeign(['attendance_session_id']);

            $table->dropColumn([
                'student_id',
                'attendance_session_id',
                'status',
                'marked_at',
                'remarks',
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | Attendance Sessions
        |--------------------------------------------------------------------------
        */

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['faculty_id']);

            $table->dropColumn([
                'subject_id',
                'faculty_id',
                'lecture_date',
                'start_time',
                'end_time',
                'lecture_name',
                'qr_token',
                'qr_expires_at',
                'status',
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | Student Classes
        |--------------------------------------------------------------------------
        */

        Schema::table('student_classes', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['subject_id']);

            $table->dropColumn([
                'student_id',
                'subject_id',
                'academic_year',
                'semester',
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | Subjects
        |--------------------------------------------------------------------------
        */

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['department_id']);

            $table->dropUnique('subjects_code_unique');

            $table->dropColumn([
                'name',
                'code',
                'faculty_id',
                'department_id',
                'semester',
                'description',
            ]);
        });
    }
};