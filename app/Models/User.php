<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke profil mahasiswa jika role = mahasiswa
     */
    public function mahasiswaProfile(): HasOne
    {
        return $this->hasOne(MahasiswaProfile::class, 'user_id');
    }

    /**
     * Dapatkan instance TugasAkhir milik mahasiswa jika ada.
     * Mengambil melalui keanggotaan kelompok (pivot anggota_tugas_akhir),
     * dengan fallback ke relasi lama mahasiswa_id selama masa transisi.
     * Berlaku untuk SEMUA anggota kelompok, bukan hanya yang tercatat di tugas_akhir.mahasiswa_id.
     */
    public function tugasAkhir(): ?TugasAkhir
    {
        if (! $this->isMahasiswa() || ! $this->mahasiswaProfile) {
            return null;
        }

        // 1. Jika relation tugasAkhirKelompok sudah dimuat
        if ($this->mahasiswaProfile->relationLoaded('tugasAkhirKelompok') && $this->mahasiswaProfile->tugasAkhirKelompok->isNotEmpty()) {
            return $this->mahasiswaProfile->tugasAkhirKelompok->first();
        }

        // 2. Resolve lewat keanggotaan pivot (hasOneThrough)
        $ta = $this->mahasiswaProfile->tugasAkhir;
        if ($ta) {
            return $ta;
        }

        // 3. Fallback ke relasi lama lewat mahasiswa_id jika ada selama transisi
        return $this->mahasiswaProfile->tugasAkhirDirect;
    }

    /**
     * Relasi ke profil dosen jika role = dosen atau kaprodi
     */
    public function dosenProfile(): HasOne
    {
        return $this->hasOne(DosenProfile::class, 'user_id');
    }

    // Role helper methods
    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isMahasiswaTa(): bool
    {
        return $this->isMahasiswa() && ($this->mahasiswaProfile?->is_ta ?? false);
    }

    public function isMahasiswaNonTa(): bool
    {
        return $this->isMahasiswa() && !($this->mahasiswaProfile?->is_ta ?? false);
    }

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    public function isPembimbingOf(TugasAkhir $tugasAkhir): bool
    {
        return $this->isDosen() && ($this->dosenProfile?->isPembimbingOf($tugasAkhir) ?? false);
    }

    public function isKoordinator(): bool
    {
        return ($this->role === 'dosen' && ($this->dosenProfile?->is_koordinator_ta ?? false));
    }

    public function isKaprodi(): bool
    {
        return $this->role === 'kaprodi' || ($this->role === 'dosen' && ($this->dosenProfile?->is_kaprodi ?? false));
    }

    public function isTA(): bool
    {
        return $this->role === 'teaching_assistant';
    }

    public function isBaak(): bool
    {
        return $this->role === 'baak';
    }

    /**
     * Mendapatkan program studi user (mahasiswa atau dosen/kaprodi)
     */
    public function prodi(): ?string
    {
        if ($this->isMahasiswa()) {
            return $this->mahasiswaProfile?->prodi;
        }

        if ($this->dosenProfile) {
            return $this->dosenProfile->prodi;
        }

        return null;
    }

    /**
     * Mendapatkan program studi yang dipimpin jika user bertindak sebagai Kaprodi
     */
    public function kaprodiProdi(): ?string
    {
        if (! $this->isKaprodi()) {
            return null;
        }

        return $this->dosenProfile?->prodi;
    }
}
