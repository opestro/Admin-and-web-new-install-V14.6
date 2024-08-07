<?php

namespace App\Http\Requests\Admin;

use App\Enums\SessionKey;
use App\Enums\UserRole;
use App\Traits\RecaptchaTrait;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class User
 *
 * @property string $email
 * @property string $password
 *
 * @package App\Models
 */
class LoginRequest extends FormRequest
{
    use RecaptchaTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:'.UserRole::ADMIN.','.UserRole::EMPLOYEE,
        ];
    }

    public function messages(): array
    {
        return [
            'required' => translate('the') . ' :attribute '.translate('field is required').'.'
        ];
    }

}
