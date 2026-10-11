<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkDeleteTamuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'target' => ['required', Rule::in(['all', 'range'])],
            'date_from' => ['required_if:target,range', 'nullable', 'date_format:Y-m-d'],
            'date_to' => ['required_if:target,range', 'nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
            'confirm_all' => ['exclude_unless:target,all', 'required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'target.required' => 'Pilih data tamu yang akan dihapus.',
            'target.in' => 'Pilihan penghapusan tidak valid.',
            'date_from.required_if' => 'Tanggal awal wajib diisi untuk penghapusan rentang.',
            'date_from.date_format' => 'Tanggal awal tidak valid.',
            'date_to.required_if' => 'Tanggal akhir wajib diisi untuk penghapusan rentang.',
            'date_to.date_format' => 'Tanggal akhir tidak valid.',
            'date_to.after_or_equal' => 'Tanggal akhir harus sama dengan atau setelah tanggal awal.',
            'confirm_all.accepted' => 'Konfirmasi penghapusan semua data terlebih dahulu.',
        ];
    }
}
