<?php

namespace App\Http\Requests\Admin;

use App\Traits\ResponseHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

/**
 * Class Attribute
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class AttributeRequest extends FormRequest
{
    use ResponseHandler;

    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.0' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translate('the_name_field_is_required!'),
            'name.0.required' => translate('the_name_field_is_required!'),
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (is_null($this['name'][array_search('en', $this['lang'])])) {
                    $validator->errors()->add(
                        'name', translate('name_field_is_required') . '!'
                    );
                }
            }
        ];
    }

}
