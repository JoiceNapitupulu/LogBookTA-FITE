<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TugasAkhir extends Model
{
    use HasFactory;

    protected $table = 'tugas_akhir';

    protected $fillable = [
        'mahasiswa_id',
        'dosen_pembimbing_1_id',
        'dosen_pembimbing_2_id',
        'judul',
        'abstrak',
        'prodi',
        'tahun_ajaran',
        'status',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaProfile::class, 'mahasiswa_id');
    }

    public function pembimbing1(): BelongsTo
    {
        return $this->belongsTo(DosenProfile::class, 'dosen_pembimbing_1_id');
    }

    public function pembimbing2(): BelongsTo
    {
        return $this->belongsTo(DosenProfile::class, 'dosen_pembimbing_2_id');
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(Logbook::class, 'tugas_akhir_id');
    }

    public function janjiBimbingan(): HasMany
    {
        return $this->hasMany(JanjiBimbingan::class, 'tugas_akhir_id');
    }

    public function ujianTa(): HasMany
    {
        return $this->hasMany(UjianTa::class, 'tugas_akhir_id');
    }

    public function arsip(): HasOne
    {
        return $this->hasOne(ArsipTugasAkhir::class, 'tugas_akhir_id');
    }

    /**
     * Scope filter prodi FITE
     */
    public function scopeFite(Builder $query, ?string $prodi = null): Builder
    {
        if ($prodi) {
            return $query->where('prodi', $prodi);
        }

        return $query->whereIn('prodi', ['S1 Informatika', 'S1 Sistem Informasi', 'S1 Teknik Komputer']);
    }
}
