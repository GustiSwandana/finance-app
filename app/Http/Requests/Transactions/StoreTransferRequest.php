<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_wallet_id' => ['required', 'integer', 'different:destination_wallet_id'],
            'destination_wallet_id' => ['required', 'integer', 'different:source_wallet_id'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
