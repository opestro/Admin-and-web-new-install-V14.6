<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $f_name
 * @property string $l_name
 * @property string $phone
 * @property string $image
 * @property string $email
 * @property $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property $created_at
 * @property $updated_at
 * @property string $street_address
 * @property string $country
 * @property string $city
 * @property string $zip
 * @property string $house_no
 * @property string $apartment_no
 * @property string|null $cm_firebase_token
 * @property bool $is_active
 * @property string|null $payment_card_last_four
 * @property string|null $payment_card_brand
 * @property string|null $payment_card_fawry_token
 * @property string|null $login_medium
 * @property string|null $social_id
 * @property bool $is_phone_verified
 * @property string|null $temporary_token
 * @property bool $is_email_verified
 * @property float $wallet_balance
 * @property float $loyalty_point
 * @property int $login_hit_count
 * @property bool $is_temp_blocked
 * @property $temp_block_time
 * @property string|null $referral_code
 * @property int $referred_by
 *
 * @package App\Models
 */
class CustomerUpdateSettingsRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'add_fund_bonus'                    => 'nullable|numeric|max:100|min:0',
            'loyalty_point_exchange_rate'       => 'nullable|numeric|min:1',
            'ref_earning_exchange_rate'         => 'nullable|numeric|min:0',
            'maximum_add_fund_amount'           => 'nullable|numeric|min:0',
            'minimum_add_fund_amount'           => 'nullable|numeric|min:1',
            'item_purchase_point'               => 'nullable|numeric|min:0',
            'minimun_transfer_point'            => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'add_fund_bonus.numeric'                => translate('The_add_fund_bonus_must_be_a_numeric_value'),
            'add_fund_bonus.max'                    => translate('The_add_fund_bonus_cannot_exceed_100'),
            'add_fund_bonus.min'                    => translate('The_add_fund_bonus_must_be_at_least_0'),
            'loyalty_point_exchange_rate.numeric'   => translate('The_loyalty_point_exchange_rate_must_be_a_numeric_value'),
            'loyalty_point_exchange_rate.min'       => translate('The_loyalty_point_exchange_rate_must_be_at_least_1'),
            'ref_earning_exchange_rate.numeric'     => translate('The_referral_earning_exchange_rate_must_be_a_numeric_value'),
            'ref_earning_exchange_rate.min'         => translate('The_referral_earning_exchange_rate_must_be_at_least_0'),
            'maximum_add_fund_amount.numeric'       => translate('The_maximum_add_fund_amount_must_be_a_numeric_value'),
            'maximum_add_fund_amount.min'           => translate('The_maximum_add_fund_amount_must_be_at_least_0'),
            'minimum_add_fund_amount.numeric'       => translate('The_minimum_add_fund_amount_must_be_a_numeric_value'),
            'minimum_add_fund_amount.min'           => translate('The_minimum_add_fund_amount_must_be_at_least_1'),
            'item_purchase_point.numeric'           => translate('The_item_purchase_point_must_be_a_numeric_value'),
            'item_purchase_point.min'               => translate('The_item_purchase_point_must_be_at_least_0'),
            'minimun_transfer_point.numeric'        => translate('The_minimum_transfer_point_must_be_a_numeric_value'),
            'minimun_transfer_point.min'            => translate('The_minimum_transfer_point_must_be_at_least_0'),
        ];
    }

}
