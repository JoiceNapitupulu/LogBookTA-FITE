<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->enum('bab_progress', ['BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'BAB 6', 'BAB 7', 'Pengujian', 'Lainnya'])->change();
            $table->json('bab_progresses')->nullable()->after('bab_progress');
        });

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
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropColumn('bab_progresses');
            $table->enum('bab_progress', ['BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'Pengujian', 'Lainnya'])->change();
        });
    }
};
