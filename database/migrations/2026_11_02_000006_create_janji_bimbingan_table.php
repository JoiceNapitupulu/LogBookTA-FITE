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
        Schema::create('janji_bimbingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhir')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('dosen_profiles')->cascadeOnDelete();
            $table->enum('inisiator', ['mahasiswa', 'dosen']);
            $table->dateTime('tanggal_waktu');
            $table->string('topik_pembahasan', 255);
            $table->enum('metode', ['offline', 'online']);
            $table->string('lokasi_atau_link')->nullable();
            $table->enum('status', ['menunggu_persetujuan', 'disetujui', 'ditolak', 'selesai'])->default('menunggu_persetujuan');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('janji_bimbingan');
    }
};
