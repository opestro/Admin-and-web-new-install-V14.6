<?php

namespace App\Http\Controllers\Vendor\Coupon;

use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Coupon as CouponExport;
use App\Enums\ViewPaths\Vendor\Coupon;
use App\Exports\CouponListExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Vendor\CouponRequest;
use App\Http\Requests\Vendor\CouponUpdateRequest;
use App\Services\CouponService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CouponController extends BaseController
{
    /**
     * @param CouponRepositoryInterface $couponRepo
     * @param CustomerRepositoryInterface $customerRepo
     */
    public function __construct(
        private readonly CouponRepositoryInterface   $couponRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly VendorRepositoryInterface $vendorRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     */
    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getAddListView(request:$request);
    }


    /**
     * @param object $request
     * @return View
     */
    public function getAddListView(object $request): View
    {
        $vendorId = auth('seller')->id();
        $searchValue = $request['searchValue'];
        $coupons = $this->couponRepo->getListWhere(
            orderBy: ['id'=>'desc'],
            searchValue: $searchValue,
            filters:['added_by' => 'seller', 'vendorId'=> $vendorId],
            dataLimit: getWebConfig(name: 'pagination_limit')
        );
        $customers = $this->customerRepo->getListWhereNotIn([0]);
        return view(Coupon::INDEX[VIEW], compact('coupons', 'customers', 'searchValue'));
    }

    /**
     * @param CouponRequest $request
     * @param CouponService $couponService
     * @return RedirectResponse
     * @function add  is the adding request data to coupon table
     */
    public function add(CouponRequest $request,CouponService $couponService): RedirectResponse
    {
        if(!$couponService->checkConditions(request: $request)){
            return redirect()->back();
        }
        $this->couponRepo->add($couponService->getCouponData(request: $request, addedBy: 'seller'));
        Toastr::success(translate('coupon_added_successfully'));
        return redirect()->back();
    }

    /**
     * @param string|int $id
     * @return View
     * @function getUpdateView is the update view
     */
    public function getUpdateView(string|int $id): View
    {
        $coupon = $this->couponRepo->getFirstWhere(['id' => $id]);
        $customers = $this->customerRepo->getListWhereNotIn([0]);
        return view(Coupon::UPDATE[VIEW], compact('coupon', 'customers'));
    }

    /**
     * @param CouponUpdateRequest $request
     * @param CouponService $couponService
     * @param string|int $id
     * @return RedirectResponse
     * @function update is the update the coupon table
     */
    public function update(CouponUpdateRequest $request, string|int $id,CouponService $couponService): RedirectResponse
    {
        if(!$couponService->checkConditions(request: $request)){
            return redirect()->back();
        }
        $this->couponRepo->update(id: $id, data: $couponService->getCouponData(request:$request, addedBy: 'seller'));
        Toastr::success(translate('coupon_update_successfully'));
        return redirect()->route(Coupon::INDEX[ROUTE]);
    }

    /**
     * @param string|int $id
     * @param string|int $status
     * @return JsonResponse
     * @function updateStatus ,update the coupon status
     *
     */
    public function updateStatus(string|int $id, string|int $status): JsonResponse
    {
        $coupon = $this->couponRepo->getFirstWhere(['added_by' => 'seller', 'coupon_bearer' => 'seller', 'id' => $id]);
        $this->couponRepo->update(id: $coupon['id'], data: ['status' => $status]);
        return response()->json([
            'status' => 1,
            'message' => translate('coupon_status_updated')
        ]);
    }

    /**
     * @param string|int $id
     * @return RedirectResponse
     * @function delete,delete coupon from coupon table
     */
    public function delete(string|int $id): RedirectResponse
    {
        $coupon = $this->couponRepo->getFirstWhere(['added_by' => 'seller', 'coupon_bearer' => 'seller', 'id' => $id]);
        if (in_array($coupon['seller_id'], [auth('seller')->id(), 0])) {
            $this->couponRepo->delete(params: ['id' => $id]);
            Toastr::success(translate('coupon_deleted_successfully'));
        } else {
            Toastr::warning(translate('coupon_not_found'));
        }
        return redirect()->back();
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @function getQuickView showing quick view of coupon details
     */
    public function getQuickView(Request $request): JsonResponse
    {
        $coupon = $this->couponRepo->getFirstWhere(['id' => $request['id']]);
        return response()->json([
            'view' => view(Coupon::QUICK_VIEW[VIEW], compact('coupon'))->render(),
        ]);
    }
    public function exportList(Request $request): BinaryFileResponse
    {
        $vendorId = auth('seller')->id();
        $vendor = $this->vendorRepo->getFirstWhere(params:['id' => $vendorId]);
        $coupons = $this->couponRepo->getListWhere(orderBy:['id'=>'desc'],searchValue: $request['searchValue'],filters: ['added_by'=>'seller','vendorId'=>$vendorId] ,dataLimit: 'all');
        return Excel::download(new CouponListExport([
            'data-from' => 'vendor',
            'vendor' => $vendor,
            'coupon' => $coupons,
            'search' => $request['searchValue'],
        ]), CouponExport::EXPORT_XLSX
        );
    }
}
