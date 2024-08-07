<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Bonus
 * @property int $id
 * @package App\Models
 */
class AddFundBonusCategoriesDeleteRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => translate('The_id_field_is_required'),
        ];
    }

}
