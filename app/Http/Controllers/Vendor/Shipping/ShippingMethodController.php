<?php

namespace App\Http\Controllers\Vendor\Shipping;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\CategoryShippingCostRepositoryInterface;
use App\Contracts\Repositories\ShippingMethodRepositoryInterface;
use App\Contracts\Repositories\ShippingTypeRepositoryInterface;
use App\Enums\ViewPaths\Vendor\Dashboard;
use App\Enums\ViewPaths\Vendor\ShippingMethod;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Requests\Vendor\ShippingMethodRequest;
use App\Services\CategoryShippingCostService;
use App\Services\ShippingMethodService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ShippingMethodController extends BaseController
{
    /**
     * @param ShippingMethodRepositoryInterface $shippingMethodRepo
     * @param ShippingMethodService $shippingMethodService
     * @param CategoryRepositoryInterface $categoryRepo
     * @param CategoryShippingCostRepositoryInterface $categoryShippingCostRepo
     * @param CategoryShippingCostService $categoryShippingCostService
     * @param ShippingTypeRepositoryInterface $shippingTypeRepo
     */
    public function __construct(
        private readonly ShippingMethodRepositoryInterface       $shippingMethodRepo,
        private readonly ShippingMethodService                   $shippingMethodService,
        private readonly CategoryRepositoryInterface             $categoryRepo,
        private readonly CategoryShippingCostRepositoryInterface $categoryShippingCostRepo,
        private readonly CategoryShippingCostService             $categoryShippingCostService,
        private readonly ShippingTypeRepositoryInterface         $shippingTypeRepo,
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
       return $this->getShippingMethodsView();
    }
    /**
     * @return View|RedirectResponse
     */
    public function getShippingMethodsView():View|RedirectResponse
    {
        $shippingMethod = getWebConfig(name: 'shipping_method');
        $vendorId =  auth('seller')->id();
        if ($shippingMethod === 'sellerwise_shipping') {
            $allCategoryIds = $this->categoryRepo->getListWhere(filters: ['position' => 0])->pluck('id')->toArray();
            $allCategoryShippingCostArray = $this->categoryShippingCostRepo->getListWhere(
                orderBy: ['id' => 'desc'],
                filters: ['seller_id' => $vendorId ],
            )->pluck('category_id')->toArray();
            foreach ($allCategoryIds as $id) {
                if (!in_array($id, $allCategoryShippingCostArray)) {
                    $this->categoryShippingCostRepo->add(
                        data: $this->categoryShippingCostService->getAddCategoryWiseShippingCostData(
                            addedBy: 'seller',
                            id: $id
                        )
                    );
                }
            }
            $allCategoryShippingCost = $this->categoryShippingCostRepo->getListWhere(
                orderBy: ['id' => 'desc'],
                filters: ['seller_id' => $vendorId ],
                relations:['category']
            );
            $sellerShipping = $this->shippingTypeRepo->getFirstWhere(
                params: ['seller_id' => $vendorId]
            );
            $shippingType = isset($sellerShipping) ? $sellerShipping['shipping_type'] : 'order_wise';
            $shippingMethods = $this->shippingMethodRepo->getListWhere(
                orderBy: ['id' => 'desc'],
                filters: ['creator_id' => $vendorId, 'creator_type' => 'seller'],
                dataLimit: getWebConfig(name: 'pagination_limit')
            );
            return view(ShippingMethod::INDEX[VIEW], compact('shippingMethods', 'allCategoryShippingCost', 'shippingType'));
        } else {
            return redirect()->route(Dashboard::INDEX[ROUTE]);
        }
    }

    /**
     * @param ShippingMethodRequest $request
     * @return RedirectResponse
     */
    public function add(ShippingMethodRequest $request): RedirectResponse
    {
        $this->shippingMethodRepo->add($this->shippingMethodService->addShippingMethodData(request: $request, addedBy: 'seller'));
        Toastr::success(translate('successfully_added'));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatus(Request $request):JsonResponse
    {
        $this->shippingMethodRepo->update(id:$request['id'],data:[ 'status' => $request['status']]);
        return response()->json(['success' => 1,],status:200);
    }

    /**
     * @param string|int $id
     * @return View|RedirectResponse
     */
    public function getUpdateView(string|int $id): View|RedirectResponse
    {
        $shippingMethod = getWebConfig(name: 'shipping_method');
        if ($shippingMethod === 'sellerwise_shipping') {
            $shippingMethod = $this->shippingMethodRepo->getFirstWhere(params: ['id' => $id]);
            return view(ShippingMethod::UPDATE[VIEW], compact('shippingMethod'));
        } else {
            return redirect()->route(Dashboard::INDEX[ROUTE]);
        }
    }

    /**
     * @param ShippingMethodRequest $request
     * @param string|int $id
     * @return RedirectResponse
     */
    public function update(ShippingMethodRequest $request , string|int $id):RedirectResponse
    {
        $this->shippingMethodRepo->update(id: $id, data: $this->shippingMethodService->addShippingMethodData(request: $request, addedBy: 'seller'));
        Toastr::success(translate('successfully_updated'));
        return redirect()->route(ShippingMethod::INDEX[ROUTE]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request):RedirectResponse
    {
        $this->shippingMethodRepo->delete(params: ['id' => $request['id']]);
        return redirect()->back();
    }
}
