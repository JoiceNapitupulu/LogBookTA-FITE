<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    /**
     * Relasi ke baris data keanggotaan kelompok Tugas Akhir
     */
    public function anggotaKelompok(): HasMany
    {
        return $this->hasMany(AnggotaTugasAkhir::class, 'tugas_akhir_id');
    }

    /**
     * Relasi ke seluruh mahasiswa anggota kelompok Tugas Akhir (1 - 3 mahasiswa)
     */
    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(
            MahasiswaProfile::class,
            'anggota_tugas_akhir',
            'tugas_akhir_id',
            'mahasiswa_id'
        )->withTimestamps();
    }

    /**
     * Hitung total anggota yang tergabung dalam Tugas Akhir ini (1 - 3 mahasiswa)
     */
    public function jumlahAnggota(): int
    {
        if ($this->relationLoaded('anggota')) {
            return $this->anggota->count();
        }

        return $this->anggota()->count();
    }

    /**
     * Cek apakah kelompok Tugas Akhir ini masih dapat menambah anggota (maksimal 3)
     */
    public function canAddAnggota(): bool
    {
        return $this->jumlahAnggota() < 3;
    }

    /**
     * Cek apakah mahasiswa tertentu merupakan anggota dari Tugas Akhir ini
     *
     * @param int|string|\App\Models\MahasiswaProfile|\App\Models\User|null $mahasiswaId
     */
    public function isAnggota($mahasiswaId): bool
    {
        if (! $mahasiswaId) {
            return false;
        }

        if ($mahasiswaId instanceof User) {
            $mahasiswaId = $mahasiswaId->mahasiswaProfile?->id;
            if (! $mahasiswaId) {
                return false;
            }
        } elseif ($mahasiswaId instanceof MahasiswaProfile) {
            $mahasiswaId = $mahasiswaId->id;
        }

        if ($this->relationLoaded('anggota')) {
            return $this->anggota->contains('id', $mahasiswaId);
        }

        return $this->anggota()->where('mahasiswa_id', $mahasiswaId)->exists();
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

        return $query->whereIn('prodi', ['S1 Informatika', 'S1 Sistem Informasi', 'S1 Teknik Elektro']);
    }
}
