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
class MailUpdateRequest extends Request
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
            "name" => 'required',
            "host" => 'required',
            "driver" => 'required',
            "port" => 'required',
            "username" => 'required',
            "email" => 'required',
            "encryption" => 'required',
            "password" => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translate('the_name_field_is_required!'),
            'host.required' => translate('the_host_field_is_required!'),
            'driver.required' => translate('the_driver_field_is_required!'),
            'port.required' => translate('the_port_field_is_required!'),
            'username.required' => translate('the_username_field_is_required!'),
            'email.required' => translate('the_email_field_is_required!'),
            'encryption.required' => translate('the_encryption_field_is_required!'),
            'password.required' => translate('the_password_field_is_required!'),
        ];
    }
}
