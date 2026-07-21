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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('enrollment_no')->unique();

            $table->string('first_name');

            $table->string('last_name');

            $table->enum('gender', ['Male', 'Female']);

            $table->date('dob')->nullable();

            $table->string('mobile')->nullable();

            $table->string('email')->nullable();

            $table->text('address')->nullable();

            $table->unsignedBigInteger('department_id');

            $table->integer('semester');

            $table->string('academic_year')->nullable();

            $table->string('photo')->nullable();

            $table->string('qr_unique_id')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
