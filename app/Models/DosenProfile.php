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
}
