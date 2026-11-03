<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLogbookReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isDosen() || auth()->user()->isKaprodi());
    }

    public function rules(): array
    {
        return [
            'status_verifikasi' => ['required', 'in:pending,revisi,disetujui'],
            'catatan_dosen' => ['nullable', 'string'],
            'file_revisi_dosen' => ['nullable', 'file', 'mimes:pdf,docx,zip', 'max:25600'],
        ];
    }
}
