<?php

namespace App\Traits;

trait CalculatorTrait
{
    protected function getDiscountAmount(float $price, float $discount, string $discountType): float
    {
        if ($discountType == PERCENTAGE) {
            $value = ($price / 100) * $discount;
        } else {
            $value = $discount;
        }
        return round($value,4);
    }
    protected function getTaxAmount(float $price, float $tax):float
    {
        return ($price / 100) * $tax;
    }
}
