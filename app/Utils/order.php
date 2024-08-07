<?php


use Illuminate\Support\Str;

if (!function_exists('getOrderSummary')) {
    function getOrderSummary(object $order): array
    {
        $sub_total = 0;
        $total_tax = 0;
        $total_discount_on_product = 0;
        foreach ($order->details as $key => $detail) {
            $sub_total += $detail->price * $detail->qty;
            $total_tax += $detail->tax;
            $total_discount_on_product += $detail->discount;
        }
        $total_shipping_cost = $order['shipping_cost'];
        return [
            'subtotal' => $sub_total,
            'total_tax' => $total_tax,
            'total_discount_on_product' => $total_discount_on_product,
            'total_shipping_cost' => $total_shipping_cost,
        ];
    }
}
if (!function_exists('getUniqueId')) {
    function getUniqueId(): string
    {
        return rand(1000, 9999) . '-' . Str::random(5) . '-' . time();
    }
}
