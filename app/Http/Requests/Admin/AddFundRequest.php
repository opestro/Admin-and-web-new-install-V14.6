<?php

namespace App\Http\Requests\Admin;

use App\Traits\ResponseHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;

/**
 * Class Bonus
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $bonus_type
 * @property float $bonus_amount
 * @property float $min_add_money_amount
 * @property float $max_bonus_amount
 * @property Carbon $start_date_time
 * @property Carbon $end_date_time
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class AddFundRequest extends FormRequest
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
            'customer_id'       =>  'exists:users,id',
            'amount'            =>  'numeric|min:.01|max:10000000',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.exists' => translate('The_selected_customer_does_not_exist'),
            'amount.numeric' => translate('The_amount_must_be_a_numeric_value'),
            'amount.min' => translate('The_amount_must_be_at_least_0.01'),
            'amount.max' => translate('The_amount_cannot_exceed_10000000'),
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $this->errorProcessor($validator)]));
    }

}
