<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\BusinessSettings;
use App\Http\Controllers\BaseController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderSettingsController extends BaseController
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
        return view(BusinessSettings::ORDER_VIEW[VIEW]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'billing_input_by_customer', value: $request->get('billing_input_by_customer', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'minimum_order_amount_status', value: $request->get('minimum_order_amount_status', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'refund_day_limit', value: $request->get('refund_day_limit', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'order_verification', value: $request->get('order_verification', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'free_delivery_status', value: $request->get('free_delivery_status', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'free_delivery_responsibility', value: $request['free_delivery_responsibility']);
        $this->businessSettingRepo->updateOrInsert(type: 'guest_checkout', value: $request->get('guest_checkout', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'free_delivery_over_amount_seller', value: currencyConverter(amount: $request['free_delivery_over_amount_seller']) ?? 0);
        Toastr::success(translate('successfully_updated'));
        return back();
    }


}
