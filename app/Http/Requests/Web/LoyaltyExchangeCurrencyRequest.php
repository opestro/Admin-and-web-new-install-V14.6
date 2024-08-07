<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property integer $point
 */
class LoyaltyExchangeCurrencyRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'point' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'point.required' => translate('the_point_field_is_required'),
            'point.integer' => translate('the_point_field_must_be_an_integer'),
            'point.min' => translate('the_point_field_must_be_at_least_one'),
        ];
    }

}
