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
        Schema::create('ujian_ta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhir')->cascadeOnDelete();
            $table->enum('jenis_ujian', ['seminar_proposal', 'seminar_ta_1', 'seminar_hasil', 'seminar_skripsi']);
            $table->string('file_persyaratan');
            $table->enum('status_dokumen', ['menunggu_verifikasi', 'berkas_lengkap', 'berkas_kurang'])->default('menunggu_verifikasi');
            $table->foreignId('verified_by_ta_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_verifikasi_ta')->nullable();
            $table->date('tanggal_ujian')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('ruangan', 100)->nullable();
            $table->string('link_zoom')->nullable();
            $table->enum('status_ujian', ['diajukan', 'terjadwal', 'lulus', 'revisi', 'tidak_lulus'])->default('diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_ta');
    }
};
