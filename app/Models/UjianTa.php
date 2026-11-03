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
        'dosen_penguji_1_id',
        'dosen_penguji_2_id',
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
            'dosen_penguji_1_id' => 'integer',
            'dosen_penguji_2_id' => 'integer',
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

    public function verifiedByKoordinator(): BelongsTo
    {
        return $this->verifiedByTa();
    }

    public function getJenisUjianLabelAttribute(): string
    {
        return match ($this->jenis_ujian) {
            'seminar_proposal' => 'Seminar Proposal',
            'seminar_ta_1' => 'Seminar TA 1',
            'seminar_hasil' => 'Seminar Hasil',
            'seminar_skripsi' => 'Seminar Tugas Akhir / Skripsi',
            default => str_replace('_', ' ', (string) $this->jenis_ujian),
        };
    }

    public function penguji1(): BelongsTo
    {
        return $this->belongsTo(DosenProfile::class, 'dosen_penguji_1_id');
    }

    public function penguji2(): BelongsTo
    {
        return $this->belongsTo(DosenProfile::class, 'dosen_penguji_2_id');
    }

    public function dosenPenguji1(): BelongsTo
    {
        return $this->penguji1();
    }

    public function dosenPenguji2(): BelongsTo
    {
        return $this->penguji2();
    }

    /**
     * Scope untuk antrean verifikasi berkas Teaching Assistant
     */
    public function scopeMenungguVerifikasi($query)
    {
        return $query->where('status_dokumen', 'menunggu_verifikasi');
    }

    /**
     * Scope untuk antrean plotting fasilitas dan ruangan BAAK
     */
    public function scopeMenungguFasilitas($query)
    {
        return $query->whereNotNull('tanggal_ujian')->whereNull('ruangan');
    }

    /**
     * Scope untuk antrean penetapan jadwal Koordinator TA
     */
    public function scopeSiapJadwal($query)
    {
        return $query->where('status_dokumen', 'berkas_lengkap')->whereNull('tanggal_ujian');
    }
}
