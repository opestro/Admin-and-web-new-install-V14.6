<?php

namespace App\Traits;

use App\Models\NotificationMessage;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

trait PushNotificationTrait
{
    use CommonTrait;

    /**
     * @param string $key
     * @param string $type
     * @param object|array $order
     * @return void
     * push notification order related
     */
    protected function sendOrderNotification(string $key, string $type, object|array $order): void
    {
        try {
            $lang = getDefaultLanguage();
            /** for customer  */
            if ($type == 'customer') {
                $fcmToken = $order->customer?->cm_firebase_token;
                $lang = $order->customer?->app_language ?? $lang;
                $value = $this->pushNotificationMessage($key, 'customer', $lang);
                $value = $this->textVariableDataFormat(value: $value, key: $key, userName: "{$order->customer?->f_name} {$order->customer?->l_name}", shopName: $order->seller?->shop?->name, deliveryManName: "{$order->deliveryMan?->f_name} {$order->deliveryMan?->l_name}", time: now()->diffForHumans(), orderId: $order->id);
                if ($fcmToken && $value) {
                    $data = [
                        'title' => translate('order'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                        'type' => 'order'
                    ];

                    $this->sendPushNotificationToDevice($fcmToken, $data);
                }
            }
            /** end for customer  */
            /**for seller */
            if ($type == 'seller') {
                $sellerFcmToken = $order->seller?->cm_firebase_token;
                if ($sellerFcmToken) {
                    $lang = $order->seller?->app_language ?? $lang;
                    $value_seller = $this->pushNotificationMessage($key, 'seller', $lang);
                    $value_seller = $this->textVariableDataFormat(value: $value_seller, key: $key, userName: "{$order->customer?->f_name} {$order->customer?->l_name}", shopName: $order->seller?->shop?->name, deliveryManName: "{$order->deliveryMan?->f_name} {$order->deliveryMan?->l_name}", time: now()->diffForHumans(), orderId: $order->id);

                    if ($value_seller != null) {
                        $data = [
                            'title' => translate('order'),
                            'description' => $value_seller,
                            'order_id' => $order['id'],
                            'image' => '',
                            'type' => 'order'
                        ];

                        $this->sendPushNotificationToDevice($sellerFcmToken, $data);
                    }
                }
            }
            /**end for seller */
            /** for delivery man*/
            if ($type == 'delivery_man') {
                $fcmTokenDeliveryMan = $order->deliveryMan?->fcm_token;
                $lang = $order->deliveryMan?->app_language ?? $lang;
                $value_delivery_man = $this->pushNotificationMessage($key, 'delivery_man', $lang);
                $value_delivery_man = $this->textVariableDataFormat(value: $value_delivery_man, key: $key, userName: "{$order->customer?->f_name} {$order->customer?->l_name}", shopName: $order->seller?->shop?->name, deliveryManName: "{$order->deliveryMan?->f_name} {$order->deliveryMan?->l_name}", time: now()->diffForHumans(), orderId: $order->id);
                $data = [
                    'title' => translate('order'),
                    'description' => $value_delivery_man,
                    'order_id' => $order['id'],
                    'image' => '',
                    'type' => 'order'
                ];
                if ($order->delivery_man_id) {
                    self::add_deliveryman_push_notification($data, $order->delivery_man_id);
                }
                if ($fcmTokenDeliveryMan) {
                    $this->sendPushNotificationToDevice($fcmTokenDeliveryMan, $data);
                }
            }

            /** end delivery man*/
        } catch (\Exception $e) {
        }
    }

    /**
     * chatting related push notification
     * @param string $key
     * @param string $type
     * @param object $userData
     * @param object $messageForm
     * @return void
     */
    protected function chattingNotification(string $key, string $type, object $userData, object $messageForm): void
    {
        try {
            $fcm_token = $type == 'delivery_man' ? $userData?->fcm_token : $userData?->cm_firebase_token;
            if ($fcm_token) {
                $lang = $userData?->app_language ?? getDefaultLanguage();
                $value = $this->pushNotificationMessage($key, $type, $lang);
                $value = $this->textVariableDataFormat(
                    value: $value,
                    key: $key,
                    userName: "{$messageForm?->f_name} ",
                    shopName: "{$messageForm?->shop?->name}",
                    deliveryManName: "{$messageForm?->f_name}",
                    time: now()->diffForHumans()
                );
                $data = [
                    'title' => translate('message'),
                    'description' => $value,
                    'order_id' => '',
                    'image' => '',
                    'type' => 'chatting'
                ];
                $this->sendPushNotificationToDevice($fcm_token, $data);
            }
        } catch (\Exception $exception) {
            dd($exception);
        }

    }

    protected function withdrawStatusUpdateNotification(string $key, string $type, string $lang, int $status, string $fcmToken): void
    {
        $value = $this->pushNotificationMessage($key, $type, $lang);
        if ($value != null) {
            $data = [
                'title' => translate('withdraw_request_' . ($status == 1 ? 'approved' : 'denied')),
                'description' => $value,
                'image' => '',
                'type' => 'notification'
            ];
            $this->sendPushNotificationToDevice($fcmToken, $data);
        }
    }

    protected function customerStatusUpdateNotification(string $key, string $type, string $lang, string $status, string $fcmToken): void
    {
        $value = $this->pushNotificationMessage($key, $type, $lang);
        if ($value != null) {
            $data = [
                'title' => translate('your_account_has_been' . '_' . $status),
                'description' => $value,
                'image' => '',
                'type' => 'block'
            ];
            $this->sendPushNotificationToDevice($fcmToken, $data);
        }
    }

    protected function productRequestStatusUpdateNotification(string $key, string $type, string $lang, string $fcmToken): void
    {
        $value = $this->pushNotificationMessage($key, $type, $lang);
        if ($value != null) {
            $data = [
                'title' => translate($key),
                'description' => $value,
                'image' => '',
                'type' => 'notification'
            ];
            $this->sendPushNotificationToDevice($fcmToken, $data);
        }
    }

    protected function cashCollectNotification(string $key, string $type, string $lang, float $amount, string $fcmToken): void
    {
        $value = $this->pushNotificationMessage($key, $type, $lang);
        if ($value != null) {
            $data = [
                'title' => currencyConverter($amount) . ' ' . translate('_cash_deposit'),
                'description' => $value,
                'image' => '',
                'type' => 'notification'
            ];
            $this->sendPushNotificationToDevice($fcmToken, $data);
        }
    }

    /**
     * push notification variable message format
     */
    protected function textVariableDataFormat($value, $key = null, $userName = null, $shopName = null, $deliveryManName = null, $time = null, $orderId = null)
    {
        $data = $value;
        if ($data) {
            $order = $orderId ? Order::find($orderId) : null;
            $data = $userName ? str_replace("{userName}", $userName, $data) : $data;
            $data = $shopName ? str_replace("{shopName}", $shopName, $data) : $data;
            $data = $deliveryManName ? str_replace("{deliveryManName}", $deliveryManName, $data) : $data;
            $data = $key == 'expected_delivery_date' ? ($order ? str_replace("{time}", $order->expected_delivery_date, $data) : $data) : ($time ? str_replace("{time}", $time, $data) : $data);
            $data = $orderId ? str_replace("{orderId}", $orderId, $data) : $data;
        }
        return $data;
    }

    /**
     * push notification variable message
     * @param string $key
     * @param string $userType
     * @param string $lang
     * @return false|int|mixed|void
     */
    protected function pushNotificationMessage(string $key, string $userType, string $lang)
    {
        try {
            $notificationKey = [
                'pending' => 'order_pending_message',
                'confirmed' => 'order_confirmation_message',
                'processing' => 'order_processing_message',
                'out_for_delivery' => 'out_for_delivery_message',
                'delivered' => 'order_delivered_message',
                'returned' => 'order_returned_message',
                'failed' => 'order_failed_message',
                'canceled' => 'order_canceled',
                'order_refunded_message' => 'order_refunded_message',
                'refund_request_canceled_message' => 'refund_request_canceled_message',
                'new_order_message' => 'new_order_message',
                'order_edit_message' => 'order_edit_message',
                'new_order_assigned_message' => 'new_order_assigned_message',
                'delivery_man_assign_by_admin_message' => 'delivery_man_assign_by_admin_message',
                'order_rescheduled_message' => 'order_rescheduled_message',
                'expected_delivery_date' => 'expected_delivery_date',
                'message_from_admin' => 'message_from_admin',
                'message_from_seller' => 'message_from_seller',
                'message_from_delivery_man' => 'message_from_delivery_man',
                'message_from_customer' => 'message_from_customer',
                'refund_request_status_changed_by_admin' => 'refund_request_status_changed_by_admin',
                'withdraw_request_status_message' => 'withdraw_request_status_message',
                'cash_collect_by_seller_message' => 'cash_collect_by_seller_message',
                'cash_collect_by_admin_message' => 'cash_collect_by_admin_message',
                'fund_added_by_admin_message' => 'fund_added_by_admin_message',
                'delivery_man_charge' => 'delivery_man_charge',
                'product_request_approved_message' => 'product_request_approved_message',
                'product_request_rejected_message' => 'product_request_rejected_message',
                'customer_block_message' => 'customer_block_message',
                'customer_unblock_message' => 'customer_unblock_message',
            ];
            $data = NotificationMessage::with(['translations' => function ($query) use ($lang) {
                $query->where('locale', $lang);
            }])->where(['key' => $notificationKey[$key], 'user_type' => $userType])->first() ?? ["status" => 0, "message" => "", "translations" => []];
            if ($data) {
                if ($data['status'] == 0) {
                    return 0;
                }
                return count($data->translations) > 0 ? $data->translations[0]->value : $data['message'];
            } else {
                return false;
            }
        } catch (\Exception $exception) {

        }
    }



    protected function demoResetNotification(): void
    {
        try {
            $data = [
                'title' => translate('demo_reset_alert'),
                'description' => translate('demo_data_is_being_reset_to_default') . '.',
                'image' => '',
                'order_id' => '',
                'type' => 'demo_reset',
            ];
            $this->sendPushNotificationToTopic(data: $data, topic: $data['type']);
        } catch (\Throwable $th) {
            info('Failed_to_sent_demo_reset_notification');
        }
    }


    /**
     * Device wise notification send
     * @param string $fcmToken
     * @param array $data
     * @return bool|string
     */

    protected function sendPushNotificationToDevice(string $fcmToken, array $data): bool|string
    {
        $postData = [
            'message' => [
                'token' => $fcmToken,
                'data' => [
                    'title' => (string)$data['title'],
                    'body' => (string)$data['description'],
                    'image' => $data['image'],
                    'order_id' =>(string)($data['order_id'] ?? ''),
                    'type' => (string)$data['type'],
                    'is_read' => '0'
                ],
                'notification' => [
                    'title' => (string)$data['title'],
                    'body' => (string)$data['description'],
                ]
            ]
        ];
        return $this->sendNotificationToHttp($postData);
    }
    /**
     * Device wise notification send
     * @param array|object $data
     * @param string $topic
     * @return bool|string
     */
    protected function sendPushNotificationToTopic(array|object $data, string $topic = 'sixvalley'): bool|string
    {

        $postData = [
            'message' => [
                'topic' => $topic,
                'data' => [
                    'title' => (string)$data['title'],
                    'body' => (string)$data['description'],
                    'image' => $data['image'],
                    'order_id' => (string)$data['order_id'] ?? '',
                    'type' => (string)$data['type'],
                    'is_read' => '0'
                ],
                'notification' => [
                    'title' => (string)$data['title'],
                    'body' => (string)$data['description'],
                ]
            ]
        ];
        return $this->sendNotificationToHttp($postData);
    }

    protected function sendNotificationToHttp(array|null $data):bool|string|null
    {
        try {
            $key = (array)getWebConfig('push_notification_key');
            if(isset($key['project_id'])){
                $url = 'https://fcm.googleapis.com/v1/projects/'.$key['project_id'].'/messages:send';
                $headers = [
                    'Authorization' => 'Bearer ' . $this->getAccessToken($key),
                    'Content-Type' => 'application/json',
                ];
            }
            return Http::withHeaders($headers)->post($url, $data);
        }catch (\Exception $exception){
            return false;
        }
    }

    protected function getAccessToken($key):String
    {
        $jwtToken = [
            'iss' => $key['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => time() + 3600,
            'iat' => time(),
        ];
        $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $jwtPayload = base64_encode(json_encode($jwtToken));
        $unsignedJwt = $jwtHeader . '.' . $jwtPayload;
        openssl_sign($unsignedJwt, $signature, $key['private_key'], OPENSSL_ALGO_SHA256);
        $jwt = $unsignedJwt . '.' . base64_encode($signature);

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);
        return $response->json('access_token');
    }
}
