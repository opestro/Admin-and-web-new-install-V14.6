<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class CouponResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $request['id'],
            'added_by' => $request['added_by'],
            'coupon_type' => $request['coupon_type'],
            'coupon_bearer' => $request['coupon_bearer'],
            'seller_id' => $request['seller_id'],
            'customer_id' => $request['customer_id'],
            'title' => $request['code'],
            'start_date' => $request['start_date'],
            'expire_date' => $request['expire_date'],
            'min_purchase' => $request['min_purchase'],
            'max_discount' => $request['max_discount'],
            'discount' => $request['discount'],
            'status' => $request['status'],
            'limit' => $request['limit'],
        ];
    }
}
