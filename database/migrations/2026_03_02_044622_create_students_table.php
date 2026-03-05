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
            $table->foreignId('user_id')->nullable()->constrained('users')->unique(); // Relasi ke Login
            $table->foreignId('department_id')->constrained('departments'); // Relasi ke Prodi
            $table->foreignId('advisor_id')->nullable()->constrained('lecturers'); // Dosen Wali (PA)
            $table->string('nim', 20)->unique();
            $table->string('name');
            $table->string('pob')->nullable(); // Tempat Lahir
            $table->date('dob')->nullable();   // Tanggal Lahir
            $table->enum('gender', ['L', 'P']);       // L/P
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->year('entry_year');        // Angkatan (2023, 2024)
            $table->enum('status', ['registered', 'non_active', 'active', 'leave', 'graduated', 'dropout'])->default('registered');
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
