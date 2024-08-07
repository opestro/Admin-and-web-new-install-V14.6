<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryManCashCollectRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|gt:0',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => translate('amount_is_required'),
            'amount.numeric' => translate('amount_must_be_numeric'),
            'amount.gt' => translate('amount_must_be_greater_than_zero'),
        ];
    }

}
