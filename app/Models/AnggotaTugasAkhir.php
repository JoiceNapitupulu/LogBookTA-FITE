<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaTugasAkhir extends Model
{
    use HasFactory;

    protected $table = 'anggota_tugas_akhir';

    protected $fillable = [
        'tugas_akhir_id',
        'mahasiswa_id',
    ];

    protected static function booted(): void
    {
        static::creating(function ($anggota) {
            $ta = TugasAkhir::find($anggota->tugas_akhir_id);
            if ($ta && $ta->jumlahAnggota() >= 3) {
                throw new \InvalidArgumentException('Maksimal anggota kelompok Tugas Akhir adalah 3 mahasiswa.');
            }
        });
    }

    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaProfile::class, 'mahasiswa_id');
    }
}
