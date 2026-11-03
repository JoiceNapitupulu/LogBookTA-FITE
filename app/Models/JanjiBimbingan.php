<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JanjiBimbingan extends Model
{
    use HasFactory;

    protected $table = 'janji_bimbingan';

    protected $fillable = [
        'tugas_akhir_id',
        'dosen_id',
        'inisiator',
        'tanggal_waktu',
        'topik_pembahasan',
        'metode',
        'lokasi_atau_link',
        'status',
        'alasan_penolakan',
        'catatan_hasil',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_waktu' => 'datetime',
        ];
    }

    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(DosenProfile::class, 'dosen_id');
    }
}
