<?php

namespace App\Services;

use Brian2694\Toastr\Facades\Toastr;

class CouponService
{
    /**
     * @return array
     * This array return column name and there value when add coupon
     */
    public function getCouponData($request,$addedBy): array
    {
        return [
            'added_by' => $addedBy,
            'coupon_type' => $request['coupon_type'],
            'title' => $request['title'],
            'code' => $request['code'],
            'start_date' => $request['start_date'],
            'expire_date' => $request['expire_date'],
            'coupon_bearer' => $addedBy == 'seller' ? 'seller' : $request['coupon_bearer'],
            'seller_id' => $addedBy == 'seller' ? auth('seller')->id() : null,
            'customer_id' => $request['customer_id'],
            'limit' => $request['limit'],
            'min_purchase' => currencyConverter(amount: $request['min_purchase']),
            'discount_type' => $request['coupon_type'] == 'discount_on_purchase' ? $request['discount_type'] : 'amount',
            'discount' => $request['coupon_type'] == 'discount_on_purchase' ? ($request['discount_type'] == 'amount' ? currencyConverter(amount: $request['discount']) : $request['discount']) : 0,
            'max_discount' => $request['coupon_type'] == 'discount_on_purchase' && $request['discount_type'] == 'percentage' ? currencyConverter(amount: $request['max_discount'] != null ? $request['max_discount'] : $request['discount']) : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function getAddData(object $request): array
    {
        $data = [
            'coupon_type' => $request['coupon_type'],
            'title' => $request['title'],
            'code' => $request['code'],
            'start_date' => $request['start_date'],
            'expire_date' => $request['expire_date'],
            'status' => 1,
            'min_purchase' => currencyConverter(amount: $request['min_purchase']),
        ];

        if ($request['coupon_type'] == 'discount_on_purchase' || $request['coupon_type'] == 'free_delivery') {
            $data += [
                'coupon_bearer' => $request['coupon_bearer'],
                'seller_id' => $request['seller_id'] == 'inhouse' ? NULL : $request['seller_id'],
                'customer_id' => $request['customer_id'],
                'limit' => $request['limit'],
            ];
        }

        if ($request['coupon_type'] == 'discount_on_purchase' || $request['coupon_type'] == 'first_order') {
            $data += [
                'discount_type' => $request['discount_type'],
                'customer_id' => 0,
                'discount' => $request['discount_type'] == 'amount' ? currencyConverter(amount: $request['discount']) : $request['discount'],
                'max_discount' => currencyConverter(amount: $request['max_discount'] != null ? $request['max_discount'] : $request['discount']),
            ];
        }

        return $data;
    }

    public function getUpdateData(object $request): array
    {
        $data = [
            'coupon_type' => $request['coupon_type'],
            'title' => $request['title'],
            'code' => $request['code'],
            'start_date' => $request['start_date'],
            'expire_date' => $request['expire_date'],
            'status' => 1,
            'min_purchase' => currencyConverter(amount: $request['min_purchase']),
        ];
        if ($request['coupon_type'] == 'discount_on_purchase' || $request['coupon_type'] == 'free_delivery') {
            $data += [
                'coupon_bearer' => $request['coupon_bearer'],
                'seller_id' => $request['seller_id'] == 'inhouse' ? NULL : $request['seller_id'],
                'customer_id' => $request['customer_id'],
                'limit' => $request['limit'],
            ];
        }

        if ($request['coupon_type'] == 'discount_on_purchase') {
            $data += [
                'discount_type' => $request['discount_type'],
                'seller_id' => $request['seller_id'] == 'inhouse' ? NULL : $request['seller_id'],
                'customer_id' => $request['customer_id'],
                'limit' => $request['limit'],
            ];
        }

        if ($request['coupon_type'] == 'discount_on_purchase' || $request['coupon_type'] == 'first_order') {
            $data += [
                'discount_type' => $request['discount_type'],
                'customer_id' => 0,
                'discount' => $request['discount_type'] == 'amount' ? currencyConverter($request['discount']) : $request['discount'],
                'max_discount' => currencyConverter($request['max_discount'] != null ? $request['max_discount'] : $request['discount']),
            ];
        }

        if ($request['coupon_type'] == 'free_delivery') {
            $data['discount_type'] = 'percentage';
            $data['discount'] = 0;
            $data['max_discount'] = 0;
        } elseif ($request['coupon_type'] == 'first_order') {
            $data['coupon_bearer'] = 'inhouse';
            $data['seller_id'] = NULL;
            $data['customer_id'] = 0;
            $data['limit'] = 0;
        }

        return $data;
    }
    public function checkConditions(object $request):bool
    {
        if ( $request['coupon_type'] == 'discount_on_purchase' && $request['discount_type'] == 'amount' && $request['discount'] > $request['min_purchase']) {
            Toastr::error(translate('the_minimum_purchase_amount_must_be_greater_than_discount_amount'));
            return false;
        }elseif ($request['discount_type'] == 'percentage' && $request['discount'] >= 100 )
        {
            Toastr::error(translate('when_discount_type percentage,discount_amount_will_be_less_than_100'));
            return false;
        }
        return true;
    }

}
