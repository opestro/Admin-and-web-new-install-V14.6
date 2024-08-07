<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
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
class AddFundBonusCategoriesUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => translate('The_id_field_is_required'),
        ];
    }

}
