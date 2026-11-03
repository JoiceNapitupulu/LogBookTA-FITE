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
        Schema::table('ujian_ta', function (Blueprint $table) {
            $table->foreignId('dosen_penguji_1_id')
                ->nullable()
                ->after('tanggal_ujian')
                ->constrained('dosen_profiles')
                ->nullOnDelete();

            $table->foreignId('dosen_penguji_2_id')
                ->nullable()
                ->after('dosen_penguji_1_id')
                ->constrained('dosen_profiles')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ujian_ta', function (Blueprint $table) {
            $table->dropForeign(['dosen_penguji_1_id']);
            $table->dropColumn('dosen_penguji_1_id');
            $table->dropForeign(['dosen_penguji_2_id']);
            $table->dropColumn('dosen_penguji_2_id');
        });
    }
};
