<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Enums\ViewPaths\Vendor\Profile;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Vendor\VendorBankInfoRequest;
use App\Http\Requests\Vendor\VendorPasswordRequest;
use App\Http\Requests\Vendor\VendorRequest;
use App\Repositories\VendorRepository;
use App\Services\VendorService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ProfileController extends BaseController
{
    /**
     * @param VendorRepository $vendorRepo
     * @param VendorService $vendorService
     * @param ShopRepositoryInterface $shopRepo
     */
    public function __construct(
        private readonly VendorRepository $vendorRepo,
        private readonly VendorService $vendorService,
        private readonly ShopRepositoryInterface $shopRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return \Illuminate\Contracts\View\View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     */
    public function index(?Request $request, string $type = null): \Illuminate\Contracts\View\View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
       return $this->getListView();
    }
    /**
     * @return View
     */
    public function getListView():View
    {
        $vendor = $this->vendorRepo->getFirstWhere(['id'=>auth('seller')->id()]);
        return view(Profile::INDEX[VIEW],compact('vendor'));
    }

    /**
     * @param string|int $id
     * @return View|RedirectResponse
     */
    public function getUpdateView(string|int $id):View|RedirectResponse
    {
        if (auth('seller')->id() != $id) {
            Toastr::warning(translate('you_can_not_change_others_profile'));
            return redirect()->back();
        }
        $vendor = $this->vendorRepo->getFirstWhere(['id'=>auth('seller')->id()]);
        $shopBanner = $this->shopRepo->getFirstWhere(['seller_id'=>auth('seller')->id()])->banner;
        return view(Profile::UPDATE[VIEW],compact('vendor','shopBanner'));
    }

    /**
     * @param VendorRequest $request
     * @param string|int $id
     * @return JsonResponse
     */
    public function update(VendorRequest $request, string|int $id):JsonResponse
    {

        $vendor = $this->vendorRepo->getFirstWhere(['id'=>$id]);
        $this->vendorRepo->update(id:$id,data: $this->vendorService->getVendorDataForUpdate(request:$request,vendor:$vendor));
        return response()->json(['message'=>translate('profile_updated_successfully')]);
    }

    /**
     * @param VendorPasswordRequest $request
     * @param string|int $id
     * @return JsonResponse
     */
    public function updatePassword(VendorPasswordRequest $request , string|int $id):JsonResponse
    {
        $this->vendorRepo->update(id:$id,data:$this->vendorService->getVendorPasswordData(request:$request));
        return response()->json(['message'=>translate('password_updated_successfully')]);
    }

    /**
     * @param string|int $id
     * @return View|RedirectResponse
     */
    public function getBankInfoUpdateView(string|int $id):View|RedirectResponse
    {
        $vendorId = auth('seller')->id();
        if ($vendorId != $id) {
            Toastr::warning(translate('you_can_not_change_others_info'));
            return redirect()->back();
        }
        $vendor = $this->vendorRepo->getFirstWhere(['id' => $vendorId]);
        return view(Profile::BANK_INFO_UPDATE[VIEW],compact('vendor'));
    }

    /**
     * @param VendorBankInfoRequest $request
     * @param string|int $id
     * @return RedirectResponse
     */
    public function updateBankInfo(VendorBankInfoRequest $request , string|int $id):RedirectResponse
    {
        $vendor = $this->vendorRepo->getFirstWhere(['id' => $id]);
        $this->vendorRepo->update(id: $vendor['id'], data: $this->vendorService->getVendorBankInfoData(request: $request));
        Toastr::success(translate('successfully_updated').'!!');
        return redirect()->route(Profile::INDEX[ROUTE]);
    }


}
