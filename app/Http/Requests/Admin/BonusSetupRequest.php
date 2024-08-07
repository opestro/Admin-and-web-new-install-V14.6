<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;


/**
 * Class YourModel
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
class BonusSetupRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'                 =>   'required',
            'bonus_type'            =>   'required',
            'bonus_amount'          =>   'required|numeric|min:.01',
            'min_add_money_amount'  =>   'required|numeric|min:.01',
            'start_date_time'       =>   'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => translate('The_title_field_is_required.'),
            'bonus_type.required' => translate('The_bonus_type_field_is_required.'),
            'bonus_amount.required' => translate('The_bonus_amount_field_is_required.'),
            'bonus_amount.numeric' => translate('The_bonus_amount_must_be_a_numeric_value.'),
            'bonus_amount.min' => translate('The_bonus_amount_must_be_at_least_0.01.'),
            'min_add_money_amount.required' => translate('The_minimum_add_money_amount_field_is_required.'),
            'min_add_money_amount.numeric' => translate('The_minimum_add_money_amount_must_be_a_numeric_value.'),
            'min_add_money_amount.min' => translate('The_minimum_add_money_amount_must_be_at_least_0.01.'),
            'start_date_time.required' => translate('The_start_date_and_time_field_is_required.'),
        ];
    }

}
