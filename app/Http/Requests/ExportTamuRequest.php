<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportTamuRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'jenis' => $this->input('jenis', 'all'),
        ]);
    }

    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'jenis' => ['required', Rule::in(['all', 'bulan'])],
            'bulan' => ['required_if:jenis,bulan', 'nullable', 'date_format:Y-m'],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis.required' => 'Pilih periode ekspor data tamu.',
            'jenis.in' => 'Pilihan periode ekspor tidak valid.',
            'bulan.required_if' => 'Pilih bulan yang akan diekspor.',
            'bulan.date_format' => 'Format bulan ekspor tidak valid.',
        ];
    }
}
