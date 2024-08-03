<?php

use App\Events\AddFundToWalletEvent;
use App\Models\Cart;
use App\Models\ShippingAddress;
use App\Models\User;
use App\Utils\CartManager;
use App\Utils\CustomerManager;
use App\Utils\OrderManager;
use Illuminate\Support\Facades\DB;

if (!function_exists('digital_payment_success')) {
    function digital_payment_success($paymentData)
    {
        if (isset($paymentData) && $paymentData['is_paid'] == 1) {
            $generateUniqueId = OrderManager::gen_unique_id();
            $orderIds = [];

            $additionalData = json_decode($paymentData['additional_data'], true);

            $addCustomer = null;
            $newCustomerInfo = $additionalData['new_customer_info'] ?? null;

            if ($newCustomerInfo) {
                $checkCustomer = User::where(['email' => $newCustomerInfo['email']])->orWhere(['phone' => $newCustomerInfo['phone']])->first();
                if (!$checkCustomer) {
                    $addCustomer = User::create([
                        'name' => $newCustomerInfo['name'],
                        'f_name' => $newCustomerInfo['name'],
                        'l_name' => $newCustomerInfo['l_name'],
                        'email' => $newCustomerInfo['email'],
                        'phone' => $newCustomerInfo['phone'],
                        'is_active' => 1,
                        'password' => bcrypt($newCustomerInfo['password']),
                        'referral_code' => $newCustomerInfo['referral_code'],
                    ]);
                } else {
                    $addCustomer = $checkCustomer;
                }
                session()->put('newRegisterCustomerInfo', $addCustomer);

                if ($additionalData['is_guest']) {
                    $addressId = $additionalData['address_id'] ?? null;
                    $billingAddressId = $additionalData['billing_address_id'] ?? null;
                    ShippingAddress::where(['customer_id' => $additionalData['customer_id'], 'is_guest' => 1, 'id' => $addressId])
                        ->update(['customer_id' => $addCustomer['id'], 'is_guest' => 0]);
                    ShippingAddress::where(['customer_id' => $additionalData['customer_id'], 'is_guest' => 1, 'id' => $billingAddressId])
                        ->update(['customer_id' => $addCustomer['id'], 'is_guest' => 0]);
                }
            }

            $data = [];
            if (isset($additionalData['payment_request_from']) && in_array($additionalData['payment_request_from'], ['app'])) {
                $isGuestUserInOrder = $additionalData['is_guest_in_order'];
                $data += [
                    'request' => [
                        'customer_id' => $additionalData['customer_id'],
                        'is_guest' => $isGuestUserInOrder ?? 0,
                        'guest_id' => $isGuestUserInOrder ? $additionalData['customer_id'] : null,
                        'order_note' => $additionalData['order_note'],
                        'coupon_code' => $additionalData['coupon_code'] ?? null,
                        'coupon_discount' => $additionalData['coupon_discount'] ?? null,
                        'address_id' => $additionalData['address_id'] ?? null,
                        'billing_address_id' => $additionalData['billing_address_id'] ?? null,
                        'payment_request_from' => $additionalData['payment_request_from'],
                    ],
                ];

                if ($additionalData['is_guest']) {
                    $cartGroupIds = Cart::where(['customer_id' => $additionalData['customer_id'], 'is_guest' => 1, 'is_checked' => 1])->groupBy('cart_group_id')->pluck('cart_group_id')->toArray();
                } else {
                    $cartGroupIds = Cart::where(['customer_id' => $additionalData['customer_id'], 'is_guest' => '0', 'is_checked' => 1])->groupBy('cart_group_id')->pluck('cart_group_id')->toArray();
                }

            } else {
                $cartGroupIds = CartManager::get_cart_group_ids(type: 'checked');
            }
            session()->put('payment_mode', isset($additionalData['payment_mode']) ? $additionalData['payment_mode'] : 'web');

            foreach ($cartGroupIds as $cartGroupId) {
                $data += [
                    'payment_method' => $paymentData['payment_method'],
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => $paymentData['transaction_id'],
                    'order_group_id' => $generateUniqueId,
                    'new_customer_id' => $addCustomer ? $addCustomer['id'] : ($additionalData['new_customer_id'] ?? null),
                    'cart_group_id' => $cartGroupId,
                    'newCustomerRegister' => $addCustomer,
                ];
                $orderId = OrderManager::generate_order($data);
                unset($data['payment_method']);
                unset($data['cart_group_id']);
                $orderIds[] = $orderId;
            }

            if (isset($additionalData['payment_request_from']) && in_array($additionalData['payment_request_from'], ['app'])) {
                CartManager::cart_clean_for_api_digital_payment($data);
            } else {
                CartManager::cart_clean();
            }
        }
    }
}

if (!function_exists('digital_payment_fail')) {
    function digital_payment_fail($payment_data)
    {

    }
}

// Add Fund To Wallet - Success
if (!function_exists('add_fund_to_wallet_success')) {
    function add_fund_to_wallet_success($payment_data): void
    {
        if (isset($payment_data) && $payment_data['is_paid'] == 1) {
            $additional_data = json_decode($payment_data['additional_data']);
            session()->put('payment_mode', isset($additional_data->payment_mode) ? $additional_data->payment_mode : 'web');

            $wallet_transaction = CustomerManager::create_wallet_transaction($payment_data['payer_id'], floatval($payment_data['payment_amount']), 'add_fund', 'add_funds_to_wallet', $payment_data);

            if($wallet_transaction)
            {
                try{
                    $data = [
                        'walletTransaction' => $wallet_transaction,
                        'userName' => $wallet_transaction->user['f_name'],
                        'userType' => 'customer',
                        'templateName' => 'add-fund-to-wallet',
                        'subject' => translate('add_fund_to_wallet'),
                        'title' => translate('add_fund_to_wallet'),
                    ];
                    event(new AddFundToWalletEvent(email: $wallet_transaction->user['email'], data: $data));
                }catch(\Exception $ex)
                {
                    info($ex);
                }
            }
        }
    }
}

// Add Fund To Wallet - Fail
if (!function_exists('add_fund_to_wallet_fail')) {
    function add_fund_to_wallet_fail($payment_data)
    {

    }
}

if (!function_exists('config_settings')) {
    function config_settings($key, $settings_type)
    {
        try {
            $config = DB::table('addon_settings')->where('key_name', $key)
                ->where('settings_type', $settings_type)->first();
        } catch (Exception $exception) {
            return null;
        }
        return (isset($config)) ? $config : null;
    }
}
