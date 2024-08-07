<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Contracts\Repositories\MostDemandedRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Enums\GlobalConstant;
use App\Enums\ViewPaths\Admin\MostDemanded;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\MostDemandedRequest;
use App\Services\MostDemandedService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class MostDemandedController extends BaseController
{

    public function __construct(
        private readonly MostDemandedRepositoryInterface $mostDemandedRepo,
        private readonly ProductRepositoryInterface      $productRepo,
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
        return $this->getListView($request);
    }

    public function getListView(Request $request): View|RedirectResponse
    {
        if(theme_root_path() != GlobalConstant::THEME_LIFESTYLE){
            return redirect('admin/dashboard');
        }
        $products = $this->productRepo->getListWhere(orderBy: ['name'=>'asc'], dataLimit: 'all');
        $mostDemandedProducts = $this->mostDemandedRepo->getListWhere(
            orderBy: ['id'=>'desc'],
            searchValue: $request['searchValue'],
            relations: ['product'],
            dataLimit: getWebConfig(name: 'pagination_limit'),
        );
        return view(MostDemanded::LIST[VIEW], compact('products','mostDemandedProducts'));
    }

    public function add(MostDemandedRequest $request, MostDemandedService $mostDemandedService): RedirectResponse
    {
        $dataArray = $mostDemandedService->getProcessedData(request: $request);
        $this->mostDemandedRepo->add(data: $dataArray);
        Toastr::success(translate('most_demanded_product_add_successfully'));
        return back();
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $this->mostDemandedRepo->updateWhere(params: ['status' => 1], data: ['status' => 0]);
        $this->mostDemandedRepo->updateWhere(params: ['id' => $request['id']], data: ['status' => $request->get('status', 0)]);
        return response()->json(['success' => 1, 'message'=>translate('status_updated_successfully')], 200);
    }

    public function getUpdateView($id): View|RedirectResponse
    {
        if(theme_root_path() != GlobalConstant::THEME_LIFESTYLE){
            return redirect('admin/dashboard');
        }
        $products = $this->productRepo->getListWhere(orderBy: ['name'=>'asc'], dataLimit: 'all');
        $mostDemandedProduct = $this->mostDemandedRepo->getFirstWhere(params: ['id'=>$id]);
        return view(MostDemanded::UPDATE[VIEW], compact('products','mostDemandedProduct'));
    }

    public function update(Request $request, $id, MostDemandedService $mostDemandedService): RedirectResponse
    {
        $mostDemandedProduct = $this->mostDemandedRepo->getFirstWhere(params: ['id'=>$id]);
        $dataArray = $mostDemandedService->getProcessedData(request: $request, image: $mostDemandedProduct['banner']);
        $this->mostDemandedRepo->update(id: $id, data: $dataArray);
        Toastr::success(translate('most_demanded_product_update_successfully'));
        return redirect()->route('admin.most-demanded.index');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->mostDemandedRepo->delete(params: ['id'=>$request['id']]);
        return response()->json(['message'=>translate('most_demanded_product_delete_successfully')]);
    }
}
