<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\HelpTopicRepositoryInterface;
use App\Enums\ViewPaths\Admin\VendorRegistrationSetting;
use App\Enums\WebConfigKey;
use App\Http\Controllers\BaseController;
use App\Repositories\VendorRegistrationReasonRepository;
use App\Services\VendorRegistrationSettingService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class VendorRegistrationSettingController extends BaseController
{
    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
        private readonly VendorRegistrationSettingService $vendorRegistrationSettingService,
        private readonly VendorRegistrationReasonRepository $vendorRegistrationReasonRepo,
        private readonly HelpTopicRepositoryInterface $helpTopicRepo

    )
    {
    }
    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getView();
    }
    public function getView(): View{
        $vendorRegistrationHeader = json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'vendor_registration_header'])['value']);
        return view(VendorRegistrationSetting::INDEX[VIEW],compact('vendorRegistrationHeader'));
    }
    public function getSellWithUsView(): View{
        $vendorRegistrationReasons = $this->vendorRegistrationReasonRepo->getList(orderBy: ['id'=>'desc'],dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        $sellWithUs = json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'vendor_registration_sell_with_us'])['value']);
        return view(VendorRegistrationSetting::WITH_US[VIEW],compact('sellWithUs','vendorRegistrationReasons'));
    }
    public function getBusinessProcessView(): View{
        $businessProcess= json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'business_process_main_section'])['value']);
        $businessProcessStep = json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'business_process_step'])['value']);
        return view(VendorRegistrationSetting::BUSINESS_PROCESS[VIEW],compact('businessProcess','businessProcessStep'));
    }
    public function getDownloadAppView(): View{
        $downloadVendorApp = json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'download_vendor_app'])['value']);
        return view(VendorRegistrationSetting::DOWNLOAD_APP[VIEW],compact('downloadVendorApp'));
    }
    public function getFAQView(Request $request): View{
        $helps = $this->helpTopicRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            searchValue: $request['searchValue'],
            filters: ['type' => 'vendor_registration'],
            dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        return view(VendorRegistrationSetting::FAQ[VIEW],compact('helps'));
    }
    public function updateHeaderSection(Request $request): RedirectResponse
    {
        $vendorRegistrationHeader = json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'vendor_registration_header'])['value']);
        $this->businessSettingRepo->updateOrInsert(type: 'vendor_registration_header',
            value:$this->vendorRegistrationSettingService->getHeaderAndSellWithUsUpdateData(request: $request,image: $vendorRegistrationHeader->image??null) );
        Toastr::success(translate('updated_successfully'));
        return redirect()->back();
    }
    public function updateSellWithUsSection(Request $request): RedirectResponse
    {
        $sellWithUs = json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'vendor_registration_sell_with_us'])['value']);
        $this->businessSettingRepo->updateOrInsert(type: 'vendor_registration_sell_with_us',
            value:$this->vendorRegistrationSettingService->getHeaderAndSellWithUsUpdateData(request: $request,image:   $sellWithUs->image??null) );
        Toastr::success(translate('updated_successfully'));
        return redirect()->back();
    }
    public function updateBusinessProcess(Request $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'business_process_main_section',
            value:$this->vendorRegistrationSettingService->getBusinessProcessUpdateData(request: $request) );
        $businessProcessStep = json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'business_process_step'])['value']);
        $this->businessSettingRepo->updateOrInsert(type: 'business_process_step',
            value:$this->vendorRegistrationSettingService->getBusinessProcessStepUpdateData(request: $request,businessProcessStep:$businessProcessStep) );
        Toastr::success(translate('updated_successfully'));
        return redirect()->back();
    }
    public function updateDownloadAppSection(Request $request): RedirectResponse
    {

        $downloadVendorApp= json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'download_vendor_app'])['value']);
        $this->businessSettingRepo->updateOrInsert(type: 'download_vendor_app',
            value:$this->vendorRegistrationSettingService->getDownloadVendorAppUpdateData(request: $request,image:$downloadVendorApp?->image) );
        Toastr::success(translate('updated_successfully'));
        return redirect()->back();
    }

}
