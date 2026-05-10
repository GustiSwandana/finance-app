<?php

namespace App\Http\Requests\Imports;

use Illuminate\Foundation\Http\FormRequest;

class StoreImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'wallet_id' => ['required', 'integer'],
            'transactions' => ['nullable', 'array'],
            'transactions.*' => ['string'],
        ];
    }
}
