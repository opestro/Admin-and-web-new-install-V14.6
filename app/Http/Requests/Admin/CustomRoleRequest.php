<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Traits\ResponseHandler;
use Illuminate\Support\Carbon;

/**
 * Class YourModel
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class CustomRoleRequest extends Request
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
            'name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translate('the_Role_field_is_required!'),
        ];
    }
}
