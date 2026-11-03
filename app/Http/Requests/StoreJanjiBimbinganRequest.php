<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJanjiBimbinganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isMahasiswaTa() || auth()->user()->isDosen());
    }

    public function rules(): array
    {
        return [
            'tugas_akhir_id' => ['nullable', 'exists:tugas_akhir,id'],
            'dosen_id' => ['required', 'exists:dosen_profiles,id'],
            'tanggal_waktu' => ['required', 'date'],
            'topik_pembahasan' => ['required', 'string', 'max:255'],
            'metode' => ['required', 'in:offline,online'],
            'lokasi_atau_link' => ['nullable', 'string', 'max:255'],
        ];
    }
}
