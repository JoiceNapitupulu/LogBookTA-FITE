<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalTa extends Model
{
    use HasFactory;

    protected $table = 'proposal_ta';

    protected $fillable = [
        'topik_ta_id',
        'mahasiswa_id',
        'judul_proposal',
        'ringkasan',
        'file_proposal',
        'link_ecourse',
        'status',
        'catatan_dosen',
        'tanggal_keputusan',
    ];

    protected $casts = [
        'tanggal_keputusan' => 'datetime',
    ];

    public function topik(): BelongsTo
    {
        return $this->belongsTo(TopikTa::class, 'topik_ta_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaProfile::class, 'mahasiswa_id');
    }

    public function scopeDiajukan(Builder $query): Builder
    {
        return $query->where('status', 'diajukan');
    }

    public function scopeLolos(Builder $query): Builder
    {
        return $query->where('status', 'lolos');
    }

    public function scopeTidakLolos(Builder $query): Builder
    {
        return $query->where('status', 'tidak_lolos');
    }

    public function isLolos(): bool
    {
        return $this->status === 'lolos';
    }

    public function isTidakLolos(): bool
    {
        return $this->status === 'tidak_lolos';
    }

    public function isDiajukan(): bool
    {
        return $this->status === 'diajukan';
    }

    public function isTahap1Direview(): bool
    {
        return $this->status === 'tahap1_direview';
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'lolos' => [
                'label' => 'Proposal Lolos',
                'class' => 'bg-success-subtle text-success border border-success-subtle',
                'icon' => 'bi-check-circle-fill',
            ],
            'tidak_lolos' => [
                'label' => 'Tidak Lolos',
                'class' => 'bg-danger-subtle text-danger border border-danger-subtle',
                'icon' => 'bi-x-circle-fill',
            ],
            'tahap1_direview' => [
                'label' => 'Tahap 1 Direview',
                'class' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                'icon' => 'bi-journal-check',
            ],
            default => [
                'label' => 'Menunggu Review Dosen',
                'class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                'icon' => 'bi-clock-history',
            ],
        };
    }
}
