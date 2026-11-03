<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DosenProfile extends Model
{
    use HasFactory;

    protected $table = 'dosen_profiles';

    protected $fillable = [
        'user_id',
        'nidn',
        'kode_dosen',
        'prodi',
        'is_koordinator_ta',
        'is_kaprodi',
    ];

    protected function casts(): array
    {
        return [
            'is_koordinator_ta' => 'boolean',
            'is_kaprodi' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tugasAkhirPembimbing1(): HasMany
    {
        return $this->hasMany(TugasAkhir::class, 'dosen_pembimbing_1_id');
    }

    public function tugasAkhirPembimbing2(): HasMany
    {
        return $this->hasMany(TugasAkhir::class, 'dosen_pembimbing_2_id');
    }

    public function janjiBimbingan(): HasMany
    {
        return $this->hasMany(JanjiBimbingan::class, 'dosen_id');
    }

    public function logbooksVerified(): HasMany
    {
        return $this->hasMany(Logbook::class, 'verified_by_dosen_id');
    }

    /**
     * Memeriksa apakah dosen ini adalah pembimbing 1 atau 2 dari Tugas Akhir tertentu
     */
    public function isPembimbingOf(TugasAkhir $tugasAkhir): bool
    {
        return $tugasAkhir->dosen_pembimbing_1_id === $this->id
            || $tugasAkhir->dosen_pembimbing_2_id === $this->id;
    }

    /**
     * Mendapatkan query Tugas Akhir mahasiswa bimbingan dosen ini
     */
    public function tugasAkhirBimbingan()
    {
        return TugasAkhir::where(function ($q) {
            $q->where('dosen_pembimbing_1_id', $this->id)
              ->orWhere('dosen_pembimbing_2_id', $this->id);
        });
    }

    /**
     * Mendapatkan 1 Koordinator TA aktif untuk program studi tertentu
     */
    public static function getKoordinatorByProdi(string $prodi): ?self
    {
        return static::where('prodi', $prodi)
            ->where('is_koordinator_ta', true)
            ->first();
    }

    /**
     * Mendapatkan 1 Kaprodi aktif untuk program studi tertentu
     */
    public static function getKaprodiByProdi(string $prodi): ?self
    {
        return static::where('prodi', $prodi)
            ->where('is_kaprodi', true)
            ->first();
    }

    /**
     * Menetapkan dosen ini sebagai Koordinator TA di prodinya.
     * Aturan bisnis: Koor TA hanya ada 1 tiap prodi.
     * Dosen lain di prodi yang sama otomatis dinonaktifkan dari koordinator.
     */
    public function tetapkanSebagaiKoordinator(): void
    {
        // 1. Nonaktifkan koordinator lain di prodi yang sama
        static::where('prodi', $this->prodi)
            ->where('id', '!=', $this->id)
            ->where('is_koordinator_ta', true)
            ->update(['is_koordinator_ta' => false]);

        // 2. Aktifkan dosen ini sebagai koordinator
        $this->update(['is_koordinator_ta' => true]);
    }

    /**
     * Menonaktifkan dosen ini dari jabatan Koordinator TA.
     */
    public function nonaktifkanKoordinator(): void
    {
        $this->update(['is_koordinator_ta' => false]);
    }

    /**
     * Menetapkan dosen ini sebagai Kaprodi di prodinya.
     * Aturan bisnis: Kaprodi hanya ada 1 di prodi.
     * Kaprodi lain di prodi yang sama otomatis dinonaktifkan.
     */
    public function tetapkanSebagaiKaprodi(): void
    {
        // 1. Nonaktifkan kaprodi lain di prodi yang sama
        static::where('prodi', $this->prodi)
            ->where('id', '!=', $this->id)
            ->where('is_kaprodi', true)
            ->update(['is_kaprodi' => false]);

        // 2. Aktifkan dosen ini sebagai kaprodi
        $this->update(['is_kaprodi' => true]);
    }

    /**
     * Menonaktifkan dosen ini dari jabatan Kaprodi.
     */
    public function nonaktifkanKaprodi(): void
    {
        $this->update(['is_kaprodi' => false]);
    }
}
