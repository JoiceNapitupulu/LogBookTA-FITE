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
        Schema::create('arsip_tugas_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->nullable()->constrained('tugas_akhir')->nullOnDelete();
            $table->string('judul', 255);
            $table->string('penulis', 255);
            $table->string('nim', 20);
            $table->string('prodi');
            $table->year('tahun_lulus');
            $table->string('nama_dospem_1', 255);
            $table->string('nama_dospem_2', 255)->nullable();
            $table->string('kata_kunci', 255);
            $table->text('abstrak');
            $table->string('file_dokumen');
            $table->foreignId('uploaded_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_tugas_akhir');
    }
};
