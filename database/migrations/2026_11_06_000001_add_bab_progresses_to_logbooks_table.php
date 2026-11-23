<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE logbooks DROP CONSTRAINT IF EXISTS logbooks_bab_progress_check');
            DB::statement("ALTER TABLE logbooks ADD CONSTRAINT logbooks_bab_progress_check CHECK (bab_progress::text IN ('BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'BAB 6', 'BAB 7', 'Pengujian', 'Lainnya'))");
            Schema::table('logbooks', function (Blueprint $table) {
                $table->json('bab_progresses')->nullable()->after('bab_progress');
            });
        } else {
            Schema::table('logbooks', function (Blueprint $table) {
                $table->enum('bab_progress', ['BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'BAB 6', 'BAB 7', 'Pengujian', 'Lainnya'])->change();
                $table->json('bab_progresses')->nullable()->after('bab_progress');
            });
        }

        DB::table('logbooks')
            ->whereNull('bab_progresses')
            ->orderBy('id')
            ->chunkById(100, function ($logbooks) {
                foreach ($logbooks as $logbook) {
                    DB::table('logbooks')
                        ->where('id', $logbook->id)
                        ->update(['bab_progresses' => json_encode([$logbook->bab_progress])]);
                }
            });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            Schema::table('logbooks', function (Blueprint $table) {
                $table->dropColumn('bab_progresses');
            });
            DB::statement('ALTER TABLE logbooks DROP CONSTRAINT IF EXISTS logbooks_bab_progress_check');
            DB::statement("ALTER TABLE logbooks ADD CONSTRAINT logbooks_bab_progress_check CHECK (bab_progress::text IN ('BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'Pengujian', 'Lainnya'))");
        } else {
            Schema::table('logbooks', function (Blueprint $table) {
                $table->dropColumn('bab_progresses');
                $table->enum('bab_progress', ['BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'Pengujian', 'Lainnya'])->change();
            });
        }
    }
};
