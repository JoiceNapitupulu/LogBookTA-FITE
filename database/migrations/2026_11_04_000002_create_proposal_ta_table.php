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
        Schema::create('proposal_ta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topik_ta_id')->constrained('topik_ta')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa_profiles')->cascadeOnDelete();
            $table->string('judul_proposal', 255);
            $table->text('ringkasan')->nullable();
            $table->string('file_proposal', 255)->nullable();
            $table->string('link_ecourse', 500)->nullable();
            $table->enum('status', ['diajukan', 'tahap1_direview', 'lolos', 'tidak_lolos'])->default('diajukan');
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('tanggal_keputusan')->nullable();
            $table->timestamps();

            // 1 Mahasiswa hanya dapat mengajukan 1 proposal untuk topik yang sama
            $table->unique(['topik_ta_id', 'mahasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_ta');
    }
};
