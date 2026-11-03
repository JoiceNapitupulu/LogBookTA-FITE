<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipTugasAkhir extends Model
{
    use HasFactory;

    protected $table = 'arsip_tugas_akhir';

    protected $fillable = [
        'tugas_akhir_id',
        'judul',
        'penulis',
        'nim',
        'prodi',
        'tahun_lulus',
        'nama_dospem_1',
        'nama_dospem_2',
        'kata_kunci',
        'abstrak',
        'file_dokumen',
        'uploaded_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'tahun_lulus' => 'integer',
        ];
    }

    public function tugasAkhir(): BelongsTo
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
