<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\BusinessSettings;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\VendorSettingsRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VendorSettingsController extends BaseController
{

    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getView();
    }

    public function getView(): View
    {
        $sales_commission = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'sales_commission']);
        if (!isset($sales_commission)) {
            $this->businessSettingRepo->add(data: ['type' => 'sales_commission', 'value' => 0]);
        }

        $seller_registration = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'seller_registration']);
        if (!isset($seller_registration)) {
            $this->businessSettingRepo->add(data: ['type' => 'seller_registration', 'value' => 1]);
        }
        return view(BusinessSettings::VENDOR_VIEW[VIEW]);
    }

    public function update(VendorSettingsRequest $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'sales_commission', value: $request->get('commission', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'seller_pos', value: $request->get('seller_pos', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'seller_registration', value: $request->get('seller_registration', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'minimum_order_amount_by_seller', value: $request->get('minimum_order_amount_by_seller', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'new_product_approval', value: $request->get('new_product_approval', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'product_wise_shipping_cost_approval', value: $request->get('product_wise_shipping_cost_approval', 0));
        Toastr::success(translate('Updated_successfully'));
        return redirect()->back();
    }

}
