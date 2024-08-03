<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\DeliveryCountryCodeRepositoryInterface;
use App\Contracts\Repositories\DeliveryZipCodeRepositoryInterface;
use App\Enums\ViewPaths\Admin\DeliveryRestriction;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\DeliveryCountryCodeAddRequest;
use App\Http\Requests\Admin\DeliveryZipCodeAddRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeliveryRestrictionController extends BaseController
{

    public function __construct(
        private readonly BusinessSettingRepositoryInterface     $businessSettingRepo,
        private readonly DeliveryCountryCodeRepositoryInterface $deliveryCountryCodeRepo,
        private readonly DeliveryZipCodeRepositoryInterface     $deliveryZipCodeRepo,
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
        return $this->getView($request);
    }

    public function getView(): View
    {
        $storedCountries = $this->deliveryCountryCodeRepo->getListWhere(orderBy: ['id' => 'desc'], dataLimit: getWebConfig(name: 'pagination_limit'));
        $countryRestrictionStatus = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'delivery_country_restriction']);
        $zipCodeAreaRestrictionStatus = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'delivery_zip_code_area_restriction']);
        $countries = COUNTRIES;
        $storedCountryCode = $storedCountries->pluck('country_code')->toArray();
        $storedZip = $this->deliveryZipCodeRepo->getListWhere(orderBy: ['id' => 'desc'], dataLimit: getWebConfig(name: 'pagination_limit'));
        return view(DeliveryRestriction::VIEW[VIEW], compact('countries', 'storedCountries', 'storedCountryCode', 'storedZip', 'countryRestrictionStatus', 'zipCodeAreaRestrictionStatus'));
    }

    public function add(DeliveryCountryCodeAddRequest $request): RedirectResponse
    {
        foreach ($request->input('country_code') as $code) {
            $this->deliveryCountryCodeRepo->add(data: ['country_code' => $code, 'created_at' => now()]);
        }
        Toastr::success(translate('delivery_country_added_successfully'));
        return back();
    }

    public function delete(Request $request): RedirectResponse
    {
        $this->deliveryCountryCodeRepo->delete(params: ['id' => $request['id']]);
        Toastr::success(translate('delivery_country_deleted_successfully'));
        return back();
    }

    public function addZipCode(DeliveryZipCodeAddRequest $request): RedirectResponse
    {
        $zipCodes = explode(',', $request['zipcode']);
        $existingZipCodes = $this->deliveryZipCodeRepo->getListWhere(dataLimit: 'all')->pluck('zipcode')->toArray();
        $zipCodes = array_diff($zipCodes, $existingZipCodes);
        if (!$zipCodes) {
            Toastr::warning(translate('delivery_zip_code_already_exists'));
            return back();
        }
        foreach ($zipCodes as $code) {
            $this->deliveryZipCodeRepo->add(data: ['zipcode' => $code]);
        }
        Toastr::success(translate('delivery_zip_code_added_successfully'));
        return back();
    }

    public function deleteZipCode(Request $request): RedirectResponse
    {
        $this->deliveryZipCodeRepo->delete(params: ['id' => $request['id']]);
        Toastr::success(translate('delivery_zip_code_deleted_successfully'));
        return back();
    }

    public function countryRestrictionStatusChange(Request $request): JsonResponse|RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'delivery_country_restriction', value: $request->get('status', 0));
        if ($request->ajax()) {
            return response()->json([
                'message' => translate('delivery_country_restriction_status_changed_successfully'),
                'status' => true
            ]);
        }
        return back();
    }

    public function zipcodeRestrictionStatusChange(Request $request): JsonResponse|RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'delivery_zip_code_area_restriction', value: $request->get('status', 0));
        if ($request->ajax()) {
            return response()->json([
                'message' => translate('delivery_zip_code_restriction_status_changed_successfully'),
                'status' => true,
            ]);
        }
        return back();
    }

}
