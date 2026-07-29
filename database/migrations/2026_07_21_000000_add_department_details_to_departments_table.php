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
        Schema::table('departments', function (Blueprint $table) {

            // Add only missing columns

            if (!Schema::hasColumn('departments', 'department_code')) {
                $table->string('department_code')->nullable();
            }

            if (!Schema::hasColumn('departments', 'hod_name')) {
                $table->string('hod_name')->nullable();
            }

            if (!Schema::hasColumn('departments', 'department_email')) {
                $table->string('department_email')->nullable();
            }

            if (!Schema::hasColumn('departments', 'department_phone')) {
                $table->string('department_phone')->nullable();
            }

            if (!Schema::hasColumn('departments', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {

            $table->dropColumn([
                'department_code',
                'hod_name',
                'department_email',
                'department_phone',
                'description'
            ]);
        });
    }
};