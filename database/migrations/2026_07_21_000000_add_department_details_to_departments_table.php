<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the fields used by the department form and listing.
     */
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->string('department_name', 100)->nullable()->after('id');
            $table->string('department_code', 10)->nullable()->unique()->after('department_name');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique(['department_code']);
            $table->dropColumn(['department_name', 'department_code']);
        });
    }
};
