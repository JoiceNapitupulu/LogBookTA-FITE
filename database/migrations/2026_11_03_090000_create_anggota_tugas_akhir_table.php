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
        Schema::create('anggota_tugas_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhir')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa_profiles')->cascadeOnDelete();
            $table->timestamps();

            // 1 mahasiswa hanya boleh tergabung dalam 1 Tugas Akhir aktif
            $table->unique('mahasiswa_id');
            // Menjamin pasangan tugas_akhir_id dan mahasiswa_id unik
            $table->unique(['tugas_akhir_id', 'mahasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_tugas_akhir');
    }
};
