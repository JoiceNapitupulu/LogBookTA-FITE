<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        // 1. Lepas batasan lama
        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE ujian_ta DROP CONSTRAINT IF EXISTS ujian_ta_jenis_ujian_check");
        } elseif ($driver === 'mysql') {
            // ENUM di MySQL diubah jadi VARCHAR agar nilai lama dan baru bisa dipetakan
            DB::statement("ALTER TABLE ujian_ta MODIFY jenis_ujian VARCHAR(50) NOT NULL");
        }

        // 2. Petakan nilai lama ke 4 tahap resmi FITE
        DB::table('ujian_ta')->where('jenis_ujian', 'sempro')
            ->update(['jenis_ujian' => 'seminar_proposal']);

        DB::table('ujian_ta')->where('jenis_ujian', 'seminar_ta')
            ->update(['jenis_ujian' => 'seminar_ta_1']);

        DB::table('ujian_ta')->where('jenis_ujian', 'sidang_akhir')
            ->update(['jenis_ujian' => 'seminar_skripsi']);

        // 3. Pasang batasan baru (hanya Postgres)
        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE ujian_ta ADD CONSTRAINT ujian_ta_jenis_ujian_check CHECK (jenis_ujian IN ('seminar_proposal', 'seminar_ta_1', 'seminar_hasil', 'seminar_skripsi'))");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE ujian_ta DROP CONSTRAINT IF EXISTS ujian_ta_jenis_ujian_check");
        }

        DB::table('ujian_ta')->where('jenis_ujian', 'seminar_proposal')
            ->update(['jenis_ujian' => 'sempro']);

        DB::table('ujian_ta')->whereIn('jenis_ujian', ['seminar_ta_1', 'seminar_hasil'])
            ->update(['jenis_ujian' => 'seminar_ta']);

        DB::table('ujian_ta')->where('jenis_ujian', 'seminar_skripsi')
            ->update(['jenis_ujian' => 'sidang_akhir']);

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE ujian_ta ADD CONSTRAINT ujian_ta_jenis_ujian_check CHECK (jenis_ujian IN ('sempro', 'seminar_ta', 'sidang_akhir'))");
        }
    }
};