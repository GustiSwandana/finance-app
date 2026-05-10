<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceiptTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receipt' => ['required', 'image', 'max:4096'],
            'wallet_id' => ['required', 'integer'],
        ];
    }
}
