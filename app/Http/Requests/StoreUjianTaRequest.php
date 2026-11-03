<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUjianTaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswaTa();
    }

    public function rules(): array
    {
        return [
            'tugas_akhir_id' => ['nullable', 'exists:tugas_akhir,id'],
            'jenis_ujian' => ['required', 'in:seminar_proposal,seminar_ta_1,seminar_hasil,seminar_skripsi'],
            'file_persyaratan' => ['required', 'file', 'mimes:pdf,zip,rar', 'max:35840'],
        ];
    }
}
