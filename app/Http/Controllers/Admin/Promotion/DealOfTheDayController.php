<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Contracts\Repositories\DealOfTheDayRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ViewPaths\Admin\DealOfTheDay;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ProductIDRequest;
use App\Services\DealOfTheDayService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DealOfTheDayController extends BaseController
{
    /**
     * @param ProductRepositoryInterface $productRepo
     * @param TranslationRepositoryInterface $translationRepo
     * @param DealOfTheDayRepositoryInterface $dealOfTheDayRepo
     */
    public function __construct(
        private readonly ProductRepositoryInterface          $productRepo,
        private readonly TranslationRepositoryInterface      $translationRepo,
        private readonly DealOfTheDayRepositoryInterface     $dealOfTheDayRepo
    ){}

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getListView($request);
    }

    public function getListView(Request $request): View
    {
        $products = $this->productRepo->getListWithScope(
            scope: "active",
            relations: ['brand','category','seller.shop'],
            dataLimit: 'all',
        );

        $deals = $this->dealOfTheDayRepo->getListWhere(
            orderBy: ['id'=>'desc'],
            searchValue: $request['searchValue'],
            dataLimit: getWebConfig('pagination_limit')
        );
        return view(DealOfTheDay::LIST[VIEW], compact('deals', 'products'));
    }

    public function add(ProductIDRequest $request, DealOfTheDayService $dealOfTheDayService): RedirectResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id'=>$request['product_id']]);
        $dataArray = $dealOfTheDayService->getAddData(request:$request, product:$product);
        $deal = $this->dealOfTheDayRepo->add(data:$dataArray);
        $this->translationRepo->add(request:$request, model:'App\Models\DealOfTheDay', id:$deal->id);
        Toastr::success(translate('deal_added_successfully'));
        return back();
    }

    public function getUpdateView($deal_id): View
    {
        $deal = $this->dealOfTheDayRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $deal_id], relations: ['product']);
        $products = $this->productRepo->getListWithScope(
            orderBy: ['id'=>'desc'],
            scope: "active",
            relations: ['brand','category','seller.shop'],
            dataLimit: 'all',
        );
        return view(DealOfTheDay::UPDATE[VIEW], compact('deal', 'products'));
    }

    public function update(Request $request, $deal_id, DealOfTheDayService $dealOfTheDayService): RedirectResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id'=>$request['product_id']]);
        $dataArray = $dealOfTheDayService->getUpdateData(request:$request, product: $product);
        $this->dealOfTheDayRepo->update(id:$deal_id, data:$dataArray);
        $this->translationRepo->update(request:$request, model:'App\Models\DealOfTheDay', id:$deal_id);
        Toastr::success(translate('deal_updated_successfully'));
        return redirect()->route('admin.deal.day');
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $this->dealOfTheDayRepo->updateWhere(params:['status' => 1],data: ['status' => 0]);
        $this->dealOfTheDayRepo->update(id:$request['id'],data: ['status' => $request->get('status', 0)]);
        return response()->json([
            'success' => 1,
        ], 200);
    }


    public function delete(Request $request): JsonResponse
    {
        $this->dealOfTheDayRepo->delete(params:['id'=>$request['id']]);
        return response()->json(['message'=>translate('Delete_successfully')], 200);
    }

}
