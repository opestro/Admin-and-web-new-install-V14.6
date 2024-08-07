<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class ChattingService
{
    use FileManagerTrait;

    /**
     * @param object $request
     * @return array
     */
    public function getAttachment(object $request):array
    {
        $attachment = [];
        if ($request->file('image')) {
            foreach ($request['image'] as $key=>$value) {
                $attachment[] = [
                    'file_name' => $this->upload('chatting/', 'webp', $value),
                    'storage' => getWebConfig(name: 'storage_connection_type') ?? 'public',
                ];
            }
        }
        if($request->file('file')) {
            foreach ($request['file'] as $key=>$value) {
                $attachment[] = [
                    'file_name' => $this->fileUpload(dir: 'chatting/', format: $value->getClientOriginalExtension(), file: $value),
                    'storage' => getWebConfig(name: 'storage_connection_type') ?? 'public',
                ];

            }
        }
        return $attachment;
    }

    /**
     * @param object $request
     * @param string|int $shopId
     * @param string|int $vendorId
     * @return array
     */
    public function getDeliveryManChattingData(object $request , string|int $shopId, string|int $vendorId):array
    {
        return [
            'delivery_man_id' => $request['delivery_man_id'],
            'seller_id' => $vendorId,
            'shop_id' => $shopId,
            'message' => $request['message'],
            'attachment' =>json_encode($this->getAttachment($request)),
            'sent_by_seller' => 1,
            'seen_by_seller' => 1,
            'seen_by_delivery_man' => 0,
            'notification_receiver' => 'deliveryman',
            'created_at' => now(),
        ];
    }

    /**
     * @param object $request
     * @param string|int $shopId
     * @param string|int $vendorId
     * @return array
     */
    public function getCustomerChattingData(object $request , string|int $shopId, string|int $vendorId):array
    {
        return [
            'user_id' => $request['user_id'],
            'seller_id' => $vendorId,
            'shop_id' => $shopId,
            'message' => $request->message,
            'attachment' =>json_encode($this->getAttachment($request)),
            'sent_by_seller' => 1,
            'seen_by_seller' => 1,
            'seen_by_customer' => 0,
            'notification_receiver' => 'customer',
            'created_at' => now(),
        ];
    }

    /**
     * @param object $request
     * @param string $type
     * @return array
     */
    public function addChattingData(object $request,string $type):array
    {
        $attachment = $this->getAttachment(request: $request);
        return [
            'delivery_man_id' => $type == 'delivery-man' ? $request['delivery_man_id'] : null ,
            'user_id' => $type == 'customer' ? $request['user_id'] : null ,
            'admin_id' => 0,
            'message' => $request['message'],
            'attachment' => json_encode($attachment),
            'sent_by_admin' => 1,
            'seen_by_admin' => 1,
            'seen_by_customer' => 0,
            'seen_by_delivery_man' => $type == 'delivery-man' ? 0 : null,
            'notification_receiver' => $type == 'delivery-man' ? 'deliveryman' : 'customer',
            'created_at' => now(),
        ];
    }

    public function addChattingDataForWeb(object $request ,string|int $userId,string $type ,string|int $shopId=null, string|int $vendorId=null,int $adminId=null, int $deliveryManId=null):array
    {
        return [
            'user_id' => $userId,
            'seller_id' => $vendorId,
            'shop_id' => $shopId ,
            'admin_id' => $adminId ,
            'delivery_man_id' => $deliveryManId ,
            'message' => $request->message,
            'attachment' =>json_encode($this->getAttachment($request)),
            'sent_by_customer' => 1,
            'seen_by_customer' => 1,
            'seen_by_seller' => 0,
            'seen_by_admin' => $type == 'admin' ? 0 : null,
            'seen_by_delivery_man' => $type == 'deliveryman' ? 0 : null,
            'notification_receiver' => $type,
            'created_at' => now(),
        ];
    }
}
