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
        Schema::create('dosen_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nidn', 20)->unique();
            $table->string('kode_dosen', 10)->unique();
            $table->string('prodi');
            $table->boolean('is_koordinator_ta')->default(false);
            $table->boolean('is_kaprodi')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_profiles');
    }
};
