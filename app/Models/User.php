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

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
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
}
