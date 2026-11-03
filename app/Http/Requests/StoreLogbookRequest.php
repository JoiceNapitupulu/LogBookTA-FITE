<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isMahasiswa() || auth()->user()->isDosen());
    }

    public function rules(): array
    {
        return [
            'tugas_akhir_id' => ['required', 'exists:tugas_akhir,id'],
            'tanggal' => ['required', 'date'],
            'bab_progress' => ['required', 'in:BAB 1,BAB 2,BAB 3,BAB 4,BAB 5,Pengujian,Lainnya'],
            'deskripsi_kegiatan' => ['required', 'string'],
            'kendala' => ['nullable', 'string'],
            'rencana_berikutnya' => ['nullable', 'string'],
            'file_dokumen_mhs' => ['nullable', 'file', 'mimes:pdf,docx,zip', 'max:25600'],
        ];
    }
}
