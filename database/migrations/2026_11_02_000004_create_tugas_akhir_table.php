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
        Schema::create('tugas_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa_profiles')->cascadeOnDelete();
            $table->foreignId('dosen_pembimbing_1_id')->nullable()->constrained('dosen_profiles')->nullOnDelete();
            $table->foreignId('dosen_pembimbing_2_id')->nullable()->constrained('dosen_profiles')->nullOnDelete();
            $table->string('judul', 255);
            $table->text('abstrak')->nullable();
            $table->string('prodi');
            $table->string('tahun_ajaran', 10);
            $table->enum('status', ['proposal', 'pengerjaan', 'sempro', 'seminar_ta', 'sidang_akhir', 'selesai', 'revisi'])->default('proposal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_akhir');
    }
};
