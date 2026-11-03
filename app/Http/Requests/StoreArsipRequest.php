<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArsipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isTA();
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:20'],
            'prodi' => ['required', 'in:S1 Informatika,S1 Sistem Informasi,S1 Teknik Elektro'],
            'tahun_lulus' => ['required', 'integer', 'min:2010', 'max:2030'],
            'nama_dospem_1' => ['required', 'string', 'max:255'],
            'nama_dospem_2' => ['nullable', 'string', 'max:255'],
            'kata_kunci' => ['required', 'string', 'max:255'],
            'abstrak' => ['required', 'string'],
            'file_dokumen' => ['required', 'file', 'mimes:pdf', 'max:51200'],
        ];
    }
}
