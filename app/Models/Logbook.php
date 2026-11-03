<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
    use HasFactory;

    protected $table = 'logbooks';

    protected $fillable = [
        'tugas_akhir_id',
        'tanggal',
        'bab_progress',
        'deskripsi_kegiatan',
        'kendala',
        'rencana_berikutnya',
        'file_dokumen_mhs',
        'catatan_dosen',
        'file_revisi_dosen',
        'status_verifikasi',
        'verified_by_dosen_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(DosenProfile::class, 'verified_by_dosen_id');
    }
}
