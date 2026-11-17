<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TopikTa extends Model
{
    use HasFactory;

    protected $table = 'topik_ta';

    protected $fillable = [
        'dosen_id',
        'judul_topik',
        'deskripsi',
        'bidang',
        'prodi',
        'kuota',
        'status',
        'tahap1_selesai',
    ];

    protected $casts = [
        'kuota' => 'integer',
        'tahap1_selesai' => 'boolean',
    ];

    /**
     * Dosen pemilik topik
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(DosenProfile::class, 'dosen_id');
    }

    /**
     * Daftar proposal yang diajukan mahasiswa untuk topik ini
     */
    public function proposals(): HasMany
    {
        return $this->hasMany(ProposalTa::class, 'topik_ta_id');
    }

    /**
     * Scope untuk topik yang sudah dipublikasikan oleh Koordinator
     */
    public function scopeDipublikasikan(Builder $query): Builder
    {
        return $query->where('status', 'dipublikasikan');
    }

    /**
     * Scope untuk topik dalam draft
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope untuk topik yang sudah ditutup
     */
    public function scopeDitutup(Builder $query): Builder
    {
        return $query->where('status', 'ditutup');
    }

    public function isDipublikasikan(): bool
    {
        return $this->status === 'dipublikasikan';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isDitutup(): bool
    {
        return $this->status === 'ditutup';
    }

    public function jumlahProposal(): int
    {
        return $this->proposals()->count();
    }

    public function jumlahLolos(): int
    {
        return $this->proposals()->where('status', 'lolos')->count();
    }

    public function sisaKuota(): int
    {
        return max(0, $this->kuota - $this->jumlahLolos());
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'dipublikasikan' => ['label' => 'Dipublikasikan', 'class' => 'bg-success-subtle text-success border border-success-subtle'],
            'ditutup' => ['label' => 'Ditutup', 'class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle'],
            default => ['label' => 'Draft', 'class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle'],
        };
    }
}
