<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujian_ta', function (Blueprint $table) {
            $table->string('status_persetujuan_pembimbing', 30)
                ->default('menunggu_verifikasi')
                ->after('status_dokumen');
            $table->text('catatan_persetujuan_pembimbing')->nullable()->after('status_persetujuan_pembimbing');
            $table->unsignedInteger('batch_pengajuan')->default(1)->after('catatan_persetujuan_pembimbing');
            $table->timestamp('pengajuan_ulang_setelah')->nullable()->after('batch_pengajuan');
        });

        DB::table('ujian_ta')
            ->where('status_dokumen', 'berkas_lengkap')
            ->update(['status_persetujuan_pembimbing' => 'diizinkan']);
    }

    public function down(): void
    {
        Schema::table('ujian_ta', function (Blueprint $table) {
            $table->dropColumn([
                'status_persetujuan_pembimbing',
                'catatan_persetujuan_pembimbing',
                'batch_pengajuan',
                'pengajuan_ulang_setelah',
            ]);
        });
    }
};
