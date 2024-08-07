<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $icon
 * @property int $parent_id
 * @property int $position
 * @property int $home_status
 * @property int $priority
 */
class SubCategoryAddRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'priority' => 'required',
            'parent_id'=>'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translate('category_name_is_required'),
            'priority.required' => translate('category_priority_is_required'),
            'parent_id.required' => translate('Main_Category_is_required'),
        ];
    }

}
