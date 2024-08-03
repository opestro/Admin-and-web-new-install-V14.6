<?php

namespace App\Services;

class PrioritySetupService
{
    public function updateBrandAndCategoryPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'sort_by' => $request['sort_by'] ?? null,
        ];
    }

    public function updateTopVendorPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'minimum_rating_point' => $request['minimum_rating_point'] ?? null,
            'sort_by' => $request['sort_by'] ?? null,
            'vacation_mode_sorting' => $request['vacation_mode_sorting'] ?? null,
            'temporary_close_sorting' => $request['temporary_close_sorting'] ?? null,
        ];
    }

    public function updateVendorPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'sort_by' => $request['sort_by'] ?? null,
            'vacation_mode_sorting' => $request['vacation_mode_sorting'] ?? null,
            'temporary_close_sorting' => $request['temporary_close_sorting'] ?? null,
        ];
    }

    public function updateVendorProductListPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'sort_by' => $request['sort_by'] ?? null,
            'out_of_stock_product' => $request['out_of_stock_product'] ?? null,
        ];
    }

    public function updateFeaturedProductPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'sort_by' => $request['sort_by'] ?? null,
            'out_of_stock_product' => $request['out_of_stock_product'] ?? null,
            'temporary_close_sorting' => $request['temporary_close_sorting'] ?? null,
        ];
    }

    public function updateFeatureDealPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'sort_by' => $request['sort_by'] ?? null,
            'out_of_stock_product' => $request['out_of_stock_product'] ?? null,
            'temporary_close_sorting' => $request['temporary_close_sorting'] ?? null,
        ];
    }

    public function updateFlashDealPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'sort_by' => $request['sort_by'] ?? null,
            'out_of_stock_product' => $request['out_of_stock_product'] ?? null,
            'temporary_close_sorting' => $request['temporary_close_sorting'] ?? null,
        ];
    }

    public function updateNewArrivalProductListPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'duration' => $request['duration'] ?? 1,
            'duration_type' => $request['duration_type'] ?? 'month',
            'sort_by' => $request['sort_by'] ?? null,
            'out_of_stock_product' => $request['out_of_stock_product'] ?? null,
            'temporary_close_sorting' => $request['temporary_close_sorting'] ?? null,
        ];
    }

    public function updateTopRatedProductListPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'minimum_rating_point' => $request['minimum_rating_point'] ?? null,
            'sort_by' => $request['sort_by'] ?? null,
            'out_of_stock_product' => $request['out_of_stock_product'] ?? null,
            'temporary_close_sorting' => $request['temporary_close_sorting'] ?? null,
        ];
    }

    public function updateCategoryWiseProductListPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'sort_by' => $request['sort_by'] ?? null,
            'out_of_stock_product' => $request['out_of_stock_product'] ?? null,
            'temporary_close_sorting' => $request['temporary_close_sorting'] ?? null,
        ];
    }

    public function updateBestSellingProductListPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'sort_by' => $request['sort_by'] ?? null,
            'out_of_stock_product' => $request['out_of_stock_product'] ?? null,
            'temporary_close_sorting' => $request['temporary_close_sorting'] ?? null,
        ];
    }

    public function updateProductListPrioritySetupData($request): array
    {
        return [
            'custom_sorting_status' => $request->get('custom_sorting_status', 0),
            'out_of_stock_product' => $request['out_of_stock_product'] ?? null,
            'temporary_close_sorting' => $request['temporary_close_sorting'] ?? null,
        ];
    }


}
