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
            'jenis_ujian' => ['required', 'in:sempro,seminar_ta,sidang_akhir'],
            'file_persyaratan' => ['required', 'file', 'mimes:pdf,zip,rar', 'max:35840'],
        ];
    }
}
