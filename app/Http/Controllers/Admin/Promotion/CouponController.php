<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Coupon as CouponExport;
use App\Enums\ViewPaths\Admin\Coupon;
use App\Exports\CouponListExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\CouponAddRequest;
use App\Http\Requests\Admin\CouponUpdateRequest;
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
     * @param VendorRepositoryInterface $vendorRepo
     */
    public function __construct(
        private readonly CouponRepositoryInterface   $couponRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly VendorRepositoryInterface   $vendorRepo,
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

    public function getAddListView(Request $request): View
    {
        $coupons = $this->couponRepo->getListWhere(searchValue: $request['searchValue'],filters: ['added_by'=>'admin'] ,dataLimit: getWebConfig(name: 'pagination_limit'));
        $customers = $this->customerRepo->getListWhereNotIn([0]);
        return view(Coupon::ADD[VIEW], compact('coupons', 'customers'));
    }

    public function add(CouponAddRequest $request, CouponService $couponService): RedirectResponse
    {
        if($request['discount_type'] == 'amount' && $request['discount'] > $request['min_purchase']){
            Toastr::error(translate('the_minimum_purchase_amount_must_be_greater_than_discount_amount'));
            return redirect()->back();
        }
        $data = $couponService->getAddData(request:$request);
        $this->couponRepo->add(data: $data);
        Toastr::success(translate('coupon_added_successfully'));
        return back();
    }

    public function getUpdateView(string|int $id): View|RedirectResponse
    {
        $sellers = $this->vendorRepo->getListWhere(filters: ['status'=>'approved'], relations: ['shop'], dataLimit: 'all');
        $customers = $this->customerRepo->getListWhereNotIn([0]);
        $coupon = $this->couponRepo->getFirstWhere(['id' => $id]);
        if($coupon){
            return view(Coupon::UPDATE[VIEW], compact('coupon', 'customers', 'sellers'));
        }
        Toastr::error(translate('invalid_Coupon'));
        return redirect()->route(Coupon::ADD[ROUTE]);
    }

    public function update(CouponUpdateRequest $request, string|int $id, CouponService $couponService): RedirectResponse
    {
        if($request['discount_type'] == 'amount' && $request['discount'] > $request['min_purchase']){
            Toastr::error(translate('The_minimum_purchase_amount_must_be_greater_than_discount_amount'));
            return redirect()->back();
        }

        $data = $couponService->getUpdateData(request:$request);
        $this->couponRepo->update(id: $id, data: $data);
        Toastr::success(translate('coupon_updated_successfully'));
        return redirect()->route(Coupon::ADD[ROUTE]);
    }

    public function updateStatus(Request $request): JsonResponse|RedirectResponse
    {
        $this->couponRepo->update(id: $request['id'], data: ['status' => $request->get('status', 0)]);
        if ($request->ajax()) {
            return response()->json([
                'status' => 1,
                'message' => translate('coupon_status_updated')
            ]);
        }
        Toastr::success(translate('coupon_status_updated'));
        return back();
    }

    public function quickView(Request $request): JsonResponse
    {
        $coupon = $this->couponRepo->getFirstWhere(params: ['id'=>$request['id'], 'added_by' => 'admin']);
        return response()->json([
            'view' => view(Coupon::QUICK_VIEW[VIEW], compact('coupon'))->render(),
        ]);
    }

    public function delete(string|int $id): RedirectResponse
    {
        $this->couponRepo->delete(params:['id'=>$id, 'added_by' => 'admin']);
        Toastr::success(translate('Coupon_deleted_successfully'));
        return redirect()->back();
    }

    public function getVendorList(Request $request): JsonResponse
    {
        $sellers = $this->vendorRepo->getListWhere(filters: ['status'=>'approved'], relations: ['shop'], dataLimit: 'all');
        $output= '<option value="" disabled selected>'. translate('select_vendor') .'</option><option value="0">'.translate('all_vendor').'</option>';
        $output .= $request['coupon_bearer'] == 'inhouse' ? '<option value="inhouse">'.translate('inhouse').'</option>' : '';
        foreach($sellers as $seller) {
            $output .= '<option value="'.$seller->id.'">'.$seller->shop->name.'</option>';
        }
        return response()->json($output);
    }

    public function exportList(Request $request): BinaryFileResponse
    {
        $coupons = $this->couponRepo->getListWhere(searchValue: $request['searchValue'],filters: ['added_by'=>'admin'] ,dataLimit: 'all');
        return Excel::download(new CouponListExport([
                    'coupon'=>$coupons,
                    'search'=>$request['searchValue'],
                ]), CouponExport::EXPORT_XLSX
        );
    }

}
