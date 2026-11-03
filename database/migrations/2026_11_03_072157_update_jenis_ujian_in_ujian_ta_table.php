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
        // 1. Drop check constraint if exists (PostgreSQL)
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE ujian_ta DROP CONSTRAINT IF EXISTS ujian_ta_jenis_ujian_check");

        // 2. Map existing legacy values to 4 official FITE exam stages
        \Illuminate\Support\Facades\DB::table('ujian_ta')
            ->where('jenis_ujian', 'sempro')
            ->update(['jenis_ujian' => 'seminar_proposal']);

        \Illuminate\Support\Facades\DB::table('ujian_ta')
            ->where('jenis_ujian', 'seminar_ta')
            ->update(['jenis_ujian' => 'seminar_ta_1']);

        \Illuminate\Support\Facades\DB::table('ujian_ta')
            ->where('jenis_ujian', 'sidang_akhir')
            ->update(['jenis_ujian' => 'seminar_skripsi']);

        // 3. Add new check constraint with 4 official exam stages
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE ujian_ta ADD CONSTRAINT ujian_ta_jenis_ujian_check CHECK (jenis_ujian IN ('seminar_proposal', 'seminar_ta_1', 'seminar_hasil', 'seminar_skripsi'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE ujian_ta DROP CONSTRAINT IF EXISTS ujian_ta_jenis_ujian_check");

        \Illuminate\Support\Facades\DB::table('ujian_ta')
            ->where('jenis_ujian', 'seminar_proposal')
            ->update(['jenis_ujian' => 'sempro']);

        \Illuminate\Support\Facades\DB::table('ujian_ta')
            ->where('jenis_ujian', 'seminar_ta_1')
            ->update(['jenis_ujian' => 'seminar_ta']);

        \Illuminate\Support\Facades\DB::table('ujian_ta')
            ->where('jenis_ujian', 'seminar_hasil')
            ->update(['jenis_ujian' => 'seminar_ta']);

        \Illuminate\Support\Facades\DB::table('ujian_ta')
            ->where('jenis_ujian', 'seminar_skripsi')
            ->update(['jenis_ujian' => 'sidang_akhir']);

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE ujian_ta ADD CONSTRAINT ujian_ta_jenis_ujian_check CHECK (jenis_ujian IN ('sempro', 'seminar_ta', 'sidang_akhir'))");
    }
};
