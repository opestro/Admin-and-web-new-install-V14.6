<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\CategoryShippingCostRepositoryInterface;
use App\Contracts\Repositories\ShippingMethodRepositoryInterface;
use App\Contracts\Repositories\ShippingTypeRepositoryInterface;
use App\Enums\ViewPaths\Admin\ShippingMethod;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ShippingMethodRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
     * @param ShippingTypeRepositoryInterface $shippingTypeRepo
     * @param ShippingMethodService $shippingMethodService
     * @param CategoryRepositoryInterface $categoryRepo
     * @param CategoryShippingCostRepositoryInterface $categoryShippingCostRepo
     * @param CategoryShippingCostService $categoryShippingCostService
     * @param BusinessSettingRepositoryInterface $businessSettingRepo
     */
    public function __construct(
        private readonly ShippingMethodRepositoryInterface       $shippingMethodRepo,
        private readonly ShippingTypeRepositoryInterface         $shippingTypeRepo,
        private readonly ShippingMethodService                   $shippingMethodService,
        private readonly CategoryRepositoryInterface             $categoryRepo,
        private readonly CategoryShippingCostRepositoryInterface $categoryShippingCostRepo,
        private readonly CategoryShippingCostService             $categoryShippingCostService,
        private readonly BusinessSettingRepositoryInterface      $businessSettingRepo,
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
     * @return View
     */
    public function getShippingMethodsView():View
    {
        $shippingMethods = $this->shippingMethodRepo->getListWhere(orderBy: ['id' => 'desc'], filters: ['creator_type' => 'admin']);
        $allCategoryIds = $this->categoryRepo->getListWhere(filters: ['position' => 0])->pluck('id')->toArray();
        $allCategoryShippingCostArray = $this->categoryShippingCostRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            filters: ['seller_id' => 0],
        )->pluck('category_id')->toArray();
        foreach ($allCategoryIds as $id) {
            if (!in_array($id, $allCategoryShippingCostArray)) {
                $this->categoryShippingCostRepo->add(
                    data: $this->categoryShippingCostService->getAddCategoryWiseShippingCostData(
                        addedBy: 'admin',
                        id: $id
                    )
                );
            }
        }
        $adminShipping = $this->shippingTypeRepo->getFirstWhere(
            params: ['seller_id' => 0]
        );
        $allCategoryShippingCost = $this->categoryShippingCostRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            filters: ['seller_id' => 0 ],
            relations:['category']
        );
        return view(ShippingMethod::INDEX[VIEW],compact('allCategoryShippingCost','shippingMethods','adminShipping'));
    }

    /**
     * @param ShippingMethodRequest $request
     * @return RedirectResponse
     */
    public function add(ShippingMethodRequest $request): RedirectResponse
    {
        $this->shippingMethodRepo->add($this->shippingMethodService->addShippingMethodData(request: $request, addedBy: 'admin'));
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
        return response()->json(['success' => 1,], status:200);
    }

    /**
     * @param string|int $id
     * @return View|RedirectResponse
     */
    public function getUpdateView(string|int $id): View|RedirectResponse
    {
        if ($id != 1) {
            $method = $this->shippingMethodRepo->getFirstWhere(params: ['id' => $id]);
            return view(ShippingMethod::UPDATE[VIEW], compact('method'));
        }
        return back();
    }

    /**
     * @param ShippingMethodRequest $request
     * @param string|int $id
     * @return RedirectResponse
     */
    public function update(ShippingMethodRequest $request , string|int $id):RedirectResponse
    {
        $this->shippingMethodRepo->update(id: $id, data: $this->shippingMethodService->addShippingMethodData(request: $request, addedBy: 'admin'));
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

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateShippingResponsibility(Request $request):RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type:'shipping_method',value: $request['shipping_method']);
        Toastr::success(translate('successfully_updated'));
        return redirect()->back();
    }

}
