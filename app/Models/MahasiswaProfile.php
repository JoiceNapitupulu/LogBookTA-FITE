<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class MahasiswaProfile extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa_profiles';

    protected $fillable = [
        'user_id',
        'nim',
        'prodi',
        'angkatan',
        'is_ta',
    ];

    protected function casts(): array
    {
        return [
            'is_ta' => 'boolean',
            'angkatan' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke keanggotaan kelompok Tugas Akhir (pivot)
     */
    public function anggotaTugasAkhir(): HasOne
    {
        return $this->hasOne(AnggotaTugasAkhir::class, 'mahasiswa_id');
    }

    /**
     * Alias relasi keanggotaan kelompok
     */
    public function keanggotaan(): HasOne
    {
        return $this->hasOne(AnggotaTugasAkhir::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke TugasAkhir melalui tabel pivot anggota_tugas_akhir
     */
    public function tugasAkhirKelompok(): BelongsToMany
    {
        return $this->belongsToMany(
            TugasAkhir::class,
            'anggota_tugas_akhir',
            'mahasiswa_id',
            'tugas_akhir_id'
        )->withTimestamps();
    }

    /**
     * Relasi langsung legacy berdasarkan kolom mahasiswa_id di tabel tugas_akhir
     */
    public function tugasAkhirDirect(): HasOne
    {
        return $this->hasOne(TugasAkhir::class, 'mahasiswa_id');
    }

    /**
     * Relasi tugas akhir mahasiswa melalui tabel pivot anggota_tugas_akhir
     */
    public function tugasAkhir(): HasOneThrough
    {
        return $this->hasOneThrough(
            TugasAkhir::class,
            AnggotaTugasAkhir::class,
            'mahasiswa_id',
            'id',
            'id',
            'tugas_akhir_id'
        );
    }
}
