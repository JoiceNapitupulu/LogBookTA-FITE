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
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhir')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('bab_progress', ['BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'Pengujian', 'Lainnya']);
            $table->text('deskripsi_kegiatan');
            $table->text('kendala')->nullable();
            $table->text('rencana_berikutnya')->nullable();
            $table->string('file_dokumen_mhs')->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->string('file_revisi_dosen')->nullable();
            $table->enum('status_verifikasi', ['pending', 'revisi', 'disetujui'])->default('pending');
            $table->foreignId('verified_by_dosen_id')->nullable()->constrained('dosen_profiles')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
