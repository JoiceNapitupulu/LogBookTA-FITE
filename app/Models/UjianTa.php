<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UjianTa extends Model
{
    use HasFactory;

    protected $table = 'ujian_ta';

    protected $fillable = [
        'tugas_akhir_id',
        'jenis_ujian',
        'file_persyaratan',
        'status_dokumen',
        'verified_by_ta_id',
        'catatan_verifikasi_ta',
        'tanggal_ujian',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'link_zoom',
        'status_ujian',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_ujian' => 'date',
        ];
    }

    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function verifiedByTa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_ta_id');
    }
}
