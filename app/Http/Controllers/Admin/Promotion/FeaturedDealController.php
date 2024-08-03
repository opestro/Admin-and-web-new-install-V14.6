<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\FlashDealRepositoryInterface;
use App\Enums\ViewPaths\Admin\FeatureDeal;
use App\Http\Controllers\BaseController;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class FeaturedDealController extends BaseController
{
    /**
     * @param FlashDealRepositoryInterface $flashDealRepo
     */
    public function __construct(
        private readonly FlashDealRepositoryInterface        $flashDealRepo,
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
            filters:['deal_type'=>'feature_deal'],
            withCount: ['products'=>'products'],
            dataLimit: getWebConfig('pagination_limit')
        );
        $featureDealPriority = json_decode($this->businessSettingRepo->getFirstWhere(params: ['type' => 'feature_deal_priority'])['value']);
        return view(FeatureDeal::LIST[VIEW], compact('flashDeals','featureDealPriority'));
    }

    public function getUpdateView($deal_id): View
    {
        $deal = $this->flashDealRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $deal_id]);
        return view(FeatureDeal::UPDATE[VIEW], compact('deal'));
    }

    public function update(Request $request): JsonResponse
    {
        $this->flashDealRepo->update(id:$request['id'], data: ['featured' => $request['featured']]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $this->flashDealRepo->updateWhere(params:['status' => 1, 'deal_type' => 'feature_deal'],data: ['status' => 0]);
        $this->flashDealRepo->update(id:$request['id'],data: ['status' => $request->get('status', 0)]);
        return response()->json([
            'success' => 1,
        ], 200);
    }
}
