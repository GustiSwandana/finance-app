<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1000'],
            'wallet_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
