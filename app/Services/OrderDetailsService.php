<?php

namespace App\Services;

class OrderDetailsService
{

    public function getPOSOrderDetailsData(int|string $orderId,array $item ,object $product,float $price ,float $tax):array
    {
        return [
            'order_id' => $orderId,
            'product_id' => $item['id'],
            'product_details' => $product,
            'qty' => $item['quantity'],
            'price' => $price,
            'seller_id' => $product['user_id'],
            'tax' => $tax*$item['quantity'],
            'tax_model' => $product['tax_model'],
            'discount' => $item['discount']*$item['quantity'],
            'discount_type' => 'discount_on_product',
            'delivery_status' => 'delivered',
            'payment_status' => 'paid',
            'variant' => $item['variant'],
            'variation' => json_encode($item['variations']),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
