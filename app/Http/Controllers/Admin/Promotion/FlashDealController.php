<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\FlashDealProductRepositoryInterface;
use App\Contracts\Repositories\FlashDealRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ViewPaths\Admin\FlashDeal;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\FlashDealAddRequest;
use App\Http\Requests\Admin\FlashDealUpdateRequest;
use App\Http\Requests\Admin\ProductIDRequest;
use App\Services\FlashDealService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class FlashDealController extends BaseController
{
    /**
     * @param ProductRepositoryInterface $productRepo
     * @param FlashDealProductRepositoryInterface $flashDealProductRepo
     * @param FlashDealRepositoryInterface $flashDealRepo
     * @param TranslationRepositoryInterface $translationRepo
     */
    public function __construct(
        private readonly ProductRepositoryInterface          $productRepo,
        private readonly FlashDealProductRepositoryInterface $flashDealProductRepo,
        private readonly FlashDealRepositoryInterface        $flashDealRepo,
        private readonly TranslationRepositoryInterface      $translationRepo,
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
    ){}

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getListView($request);
    }

    public function getListView(Request $request): View
    {
        $flashDeals = $this->flashDealRepo->getListWithRelations(
            orderBy: ['id'=>'desc'],
            searchValue:$request['searchValue'],
            filters:['deal_type'=>'flash_deal'],
            withCount: ['products'=>'products'],
            dataLimit: getWebConfig('pagination_limit')
        );
        $flashDealPriority = json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'flash_deal_priority'])['value']);
        return view(FlashDeal::LIST[VIEW], compact('flashDeals','flashDealPriority'));
    }

    public function add(FlashDealAddRequest $request, FlashDealService $flashDealService): RedirectResponse
    {
        $dataArray = $flashDealService->getAddData(request:$request);
        $addFlashDeal = $this->flashDealRepo->add(data:$dataArray);
        $this->translationRepo->add(request:$request, model:'App\Models\FlashDeal', id:$addFlashDeal->id);
        Toastr::success(translate('deal_added_successfully'));
        return back();
    }

    public function getUpdateView($deal_id): View
    {
        $deal = $this->flashDealRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $deal_id], relations: ['translations']);
        return view(FlashDeal::UPDATE[VIEW], compact('deal'));
    }

    public function update(FlashDealUpdateRequest $request, $deal_id, FlashDealService $flashDealService): RedirectResponse
    {
        $deal = $this->flashDealRepo->getFirstWhere(params:['id'=>$deal_id]);
        $dataArray = $flashDealService->getUpdateData(request:$request, data: $deal);
        $this->flashDealRepo->update(id:$request['id'], data:$dataArray);
        $this->translationRepo->update(request:$request, model:'App\Models\FlashDeal', id:$request['id']);
        Toastr::success(translate('deal_updated_successfully'));
        return back();
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $this->flashDealRepo->updateWhere(params:['status' => 1, 'deal_type' => 'flash_deal'],data: ['status' => 0]);
        $this->flashDealRepo->update(id:$request['id'],data: ['status' => $request->get('status', 0)]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function getAddProductView($deal_id): View
    {
        $products = $this->productRepo->getListWithScope(
            scope: "active",
            relations: ['brand','category','seller.shop'],
            dataLimit: 'all');

        $flashDealProducts = $this->flashDealProductRepo->getListWhere(filters:['flash_deal_id'=>$deal_id])->pluck('product_id')->toArray();

        $deal = $this->flashDealRepo->getFirstWhere(params:['id'=>$deal_id],relations: ['products.product']);

        $dealProducts = $this->productRepo->getListWithScope(
            orderBy: ['id' => 'desc'],
            scope: "active",
            whereIn: ['id'=>$flashDealProducts],
            relations: ['brand','category','seller.shop'],
            dataLimit: getWebConfig('pagination_limit'));

        return view(FlashDeal::ADD_PRODUCT[VIEW], compact('deal', 'products', 'dealProducts'));
    }

    public function addProduct(ProductIDRequest $request, $deal_id, FlashDealService $flashDealService): RedirectResponse
    {
        $flashDealProducts = $this->flashDealProductRepo->getFirstWhere(params: ['flash_deal_id'=>$deal_id, 'product_id'=>$request['product_id']]);
        if(!isset($flashDealProducts)) {
            $dataArray = $flashDealService->getAddProduct(request: $request, id:$deal_id);
            $this->flashDealProductRepo->add(data: $dataArray);
            Toastr::success(translate('product_added_successfully'));
            return back();
        }

        Toastr::info(translate('product_already_added'));
        return back();
    }

    public function delete(Request $request): JsonResponse
    {
        $this->flashDealProductRepo->delete(params:['product_id'=>$request['id']]);
        return response()->json(['message'=>translate('product_removed_successfully')], 200);
    }

    public function search(Request $request): JsonResponse
    {
        $products = $this->productRepo->getListWithScope(
            orderBy: ['id' => 'desc'],
            searchValue: $request['searchValue'],
            scope: "active",
            relations: ['brand','category','seller.shop'],
            dataLimit: 'all');
        return response()->json([
            'result' => view(FlashDeal::SEARCH[VIEW], compact('products'))->render(),
        ]);
    }


}
