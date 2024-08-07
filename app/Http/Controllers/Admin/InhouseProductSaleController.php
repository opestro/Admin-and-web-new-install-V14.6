<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Enums\ViewPaths\Admin\InhouseProductSale;
use App\Http\Controllers\BaseController;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class InhouseProductSaleController extends BaseController
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepo,
        private readonly ProductRepositoryInterface  $productRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getListView(request: $request);
    }

    public function getListView(Request $request): View
    {
        $categories = $this->categoryRepo->getListWhere(filters: ['parent_id' => 0], dataLimit: 'all');
        $products = $this->productRepo->getListWhere(
            filters: ['added_by'=>'in_house', 'category_id'=>$request['category_id']],
            relations: ['orderDetails'],
            dataLimit: getWebConfig(name: 'pagination_limit'),
        );
        return view(InhouseProductSale::VIEW[VIEW], compact('categories', 'products'));
    }
}
