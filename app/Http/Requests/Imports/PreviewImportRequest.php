<?php

namespace App\Http\Requests\Imports;

use Illuminate\Foundation\Http\FormRequest;

class PreviewImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_files' => ['required', 'array', 'min:1', 'max:20'],
            'bank_files.*' => ['mimes:pdf,csv,txt,xlsx,xls', 'max:10240'],
            'bank_source' => ['required', 'string', 'in:auto,bca,mandiri,bri,generic'],
            'wallet_id' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'bank_files.max' => 'Maksimal 20 file mutasi dalam satu kali import.',
            'bank_files.*.mimes' => 'File mutasi harus berformat PDF, CSV, TXT, XLSX, atau XLS.',
            'bank_files.*.max' => 'Ukuran tiap file mutasi maksimal 10 MB.',
        ];
    }
}
