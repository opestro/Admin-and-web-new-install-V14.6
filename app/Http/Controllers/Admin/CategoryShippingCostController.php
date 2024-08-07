<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\CategoryShippingCostRepositoryInterface;
use App\Http\Controllers\BaseController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class CategoryShippingCostController extends BaseController
{
    public function __construct(
        private readonly CategoryShippingCostRepositoryInterface $categoryShippingCostRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|RedirectResponse Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View|RedirectResponse
    {
        return $this->add($request);
    }

    public function add(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            foreach ($request->ids as $key => $id) {
                $this->categoryShippingCostRepo->updateOrInsert( params:['seller_id' => 0, 'category_id' => $request['category_ids'][$key]], data: [
                    'cost' => currencyConverter(amount: $request['cost'][$key]),
                    'multiply_qty' => isset($request->multiplyQTY) ? (in_array($id, $request->multiplyQTY) ? 1 : 0) : 0,
                    'updated_at' => now()
                ]);
            }
        }

        Toastr::success(translate('Category_cost_successfully_updated'));
        return back();
    }
}
