<?php

namespace App\Traits;

use App\Models\CategoryShippingCost;
use App\Models\ShippingMethod;
use App\Models\ShippingType;
use App\Utils\Helpers;

trait ProductTrait
{
    public function isAddedByInHouse(string|null $addedBy): bool
    {
        return isset($addedBy) && $addedBy == 'in_house';
    }

    public static function getProductDeliveryCharge(object|array $product, string|int $quantity): array
    {
        $deliveryCost = 0;
        $shippingModel = Helpers::get_business_settings('shipping_method');
        $shippingType = "";
        $maxOrderWiseShippingCost = 0;
        $minOrderWiseShippingCost = 0;

        if ($shippingModel == "inhouse_shipping") {
            $shippingType = ShippingType::where(['seller_id' => 0])->first();
            if ($shippingType->shipping_type == "category_wise") {
                $catId = $product->category_id;
                $CategoryShippingCost = CategoryShippingCost::where(['seller_id' => 0, 'category_id' => $catId])->first();
                $deliveryCost = $CategoryShippingCost ?
                    ($CategoryShippingCost->multiply_qty != 0 ? ($CategoryShippingCost->cost * $quantity) : $CategoryShippingCost->cost)
                    : 0;
            } elseif ($shippingType->shipping_type == "product_wise") {
                $deliveryCost = $product->multiply_qty != 0 ? ($product->shipping_cost * $quantity) : $product->shipping_cost;
            } elseif ($shippingType->shipping_type == 'order_wise') {
                $maxOrderWiseShippingCost = ShippingMethod::where(['creator_id' => 1, 'creator_type' => 'admin', 'status' => 1])->max('cost');
                $minOrderWiseShippingCost = ShippingMethod::where(['creator_id' => 1, 'creator_type' => 'admin', 'status' => 1])->min('cost');
            }
        } elseif ($shippingModel == "sellerwise_shipping") {

            if ($product->added_by == "admin") {
                $shippingType = ShippingType::where('seller_id', '=', 0)->first();
            } else {
                $shippingType = ShippingType::where('seller_id', '!=', 0)->where(['seller_id' => $product->user_id])->first();
            }

            if ($shippingType) {
                $shippingType = $shippingType ?? ShippingType::where('seller_id', '=', 0)->first();
                if ($shippingType->shipping_type == "category_wise") {
                    $catId = $product->category_id;
                    if ($product->added_by == "admin") {
                        $CategoryShippingCost = CategoryShippingCost::where(['seller_id' => 0, 'category_id' => $catId])->first();
                    } else {
                        $CategoryShippingCost = CategoryShippingCost::where(['seller_id' => $product->user_id, 'category_id' => $catId])->first();
                    }

                    $deliveryCost = $CategoryShippingCost ?
                        ($CategoryShippingCost->multiply_qty != 0 ? ($CategoryShippingCost->cost * $quantity) : $CategoryShippingCost->cost)
                        : 0;
                } elseif ($shippingType->shipping_type == "product_wise") {
                    $deliveryCost = $product->multiply_qty != 0 ? ($product->shipping_cost * $quantity) : $product->shipping_cost;
                } elseif ($shippingType->shipping_type == 'order_wise') {
                    $maxOrderWiseShippingCost = ShippingMethod::where(['creator_id' => $product->user_id, 'creator_type' => $product->added_by, 'status' => 1])->max('cost');
                    $minOrderWiseShippingCost = ShippingMethod::where(['creator_id' => $product->user_id, 'creator_type' => $product->added_by, 'status' => 1])->min('cost');
                }
            }
        }
        return [
            'delivery_cost' => $deliveryCost,
            'delivery_cost_max' => $maxOrderWiseShippingCost,
            'delivery_cost_min' => $minOrderWiseShippingCost,
            'shipping_type' => $shippingType->shipping_type ?? '',
        ];
    }
}
