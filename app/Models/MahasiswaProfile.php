<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function tugasAkhir(): HasOne
    {
        return $this->hasOne(TugasAkhir::class, 'mahasiswa_id');
    }
}
