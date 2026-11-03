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
        Schema::create('mahasiswa_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nim', 20)->unique();
            $table->enum('prodi', ['S1 Informatika', 'S1 Sistem Informasi', 'S1 Teknik Komputer']);
            $table->year('angkatan');
            $table->boolean('is_ta')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_profiles');
    }
};
