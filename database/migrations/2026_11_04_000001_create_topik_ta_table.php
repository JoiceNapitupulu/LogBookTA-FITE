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
        Schema::create('topik_ta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosen_profiles')->cascadeOnDelete();
            $table->string('judul_topik', 255);
            $table->text('deskripsi')->nullable();
            $table->string('bidang', 100)->nullable();
            $table->string('prodi', 100)->default('S1 Sistem Informasi');
            $table->unsignedInteger('kuota')->default(1);
            $table->enum('status', ['draft', 'dipublikasikan', 'ditutup'])->default('draft');
            $table->boolean('tahap1_selesai')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topik_ta');
    }
};
