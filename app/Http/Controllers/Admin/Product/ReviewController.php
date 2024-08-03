<?php

namespace App\Http\Controllers\Admin\Product;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Enums\ViewPaths\Admin\Review;
use App\Exports\CustomerReviewListExport;
use App\Http\Controllers\BaseController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReviewController extends BaseController
{
    public function __construct(
        private readonly ReviewRepositoryInterface      $reviewRepo,
        private readonly ProductRepositoryInterface     $productRepo,
        private readonly CustomerRepositoryInterface    $customerRepo,
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

    public function getListView(Request $request):  View
    {
        if (!empty($request['from']) && empty($request['to'])) {
            Toastr::warning(translate('please_select_to_date'));
        }

        $filters = [
            'product_id' => $request['product_id'],
            'customer_id' => $request['customer_id'],
            'status' => $request['status'],
            'from' => $request['from'],
            'to' => $request['to'],
            'delivery_man_id' => null,
        ];

        if ($request->has('searchValue')) {
            $productIds = $this->productRepo->getListWhere(
                searchValue: $request['searchValue'],
                dataLimit: 'all')->pluck('id')->toArray();
            $customerIds = $this->customerRepo->getListWhere(searchValue: $request['searchValue'], dataLimit: 'all')->pluck('id')->toArray();
            $filtersBy = [
                'product_id' => $productIds,
                'customer_id' => $customerIds,
            ];
            $reviews = $this->reviewRepo->getListWhereIn(
                globalScope: false,
                orderBy:['id'=>'desc'],
                whereInFilters: $filtersBy,
                relations: ['product', 'customer'],
                nullFields: ['delivery_man_id'],
                dataLimit: getWebConfig(name: 'pagination_limit'));
        } else {
            $reviews = $this->reviewRepo->getListWhereIn(
                globalScope: false,
                orderBy:['id'=>'desc'],
                filters: $filters,
                relations: ['product', 'customer'],
                dataLimit:getWebConfig(name: 'pagination_limit'));
        }

        $products = $this->productRepo->getListWithScope(
            searchValue: $request['searchValue'],
            scope: 'active',
            relations: ['category','brand','seller'],
            dataLimit: 'all');

        $product = $this->productRepo->getFirstWhere(params:['id'=>$request['product_id']]);
        $customer = "all";
        if($request['customer_id'] != 'all' && !is_null($request['customer_id']) && $request->has('customer_id')){
            $customer = $this->customerRepo->getFirstWhere(params: ['id'=>$request['customer_id']]);
        }

        return view(Review::LIST[VIEW], [
            'reviews' => $reviews,
            'products' => $products,
            'product' => $product,
            'customer' => $customer,
            'from' => $request['from'],
            'to' => $request['to'],
            'customer_id' => $request['customer_id'],
            'product_id' => $request['product_id'],
            'status' => $request['status'],
        ]);
    }

    public function updateStatus(Request $request):  RedirectResponse|JsonResponse
    {
        $status = $request->status ?? 0;
        $this->reviewRepo->update(id: $request['id'], data: ['status'=>$status]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 1,
                'message' => translate('review_status_updated.')
            ]);
        }
        Toastr::success(translate('review_status_updated'));
        return back();
    }

    public function exportList(Request $request): BinaryFileResponse|RedirectResponse
    {
        $filters = [
            'product_id' => $request['product_id'],
            'customer_id' => $request['customer_id'],
            'status' => $request['status'],
            'from' => $request['from'],
            'to' => $request['to'],
            'delivery_man_id' => null,
        ];
        if ($request->has('searchValue')) {
            $productIds = $this->productRepo->getListWhere(
                searchValue: $request['searchValue'],
                dataLimit: 'all')->pluck('id')->toArray();
            $customerIds = $this->customerRepo->getListWhere(searchValue: $request['searchValue'], dataLimit: 'all')->pluck('id')->toArray();
            $filtersBy = [
                'product_id' => $productIds,
                'customer_id' => $customerIds,
            ];
            $reviews = $this->reviewRepo->getListWhereIn(
                globalScope: false,
                orderBy:['id'=>'desc'],
                whereInFilters: $filtersBy,
                relations: ['product', 'customer'],
                nullFields: ['delivery_man_id'],
                dataLimit: getWebConfig(name: 'pagination_limit'));
        } else {
            $reviews = $this->reviewRepo->getListWhereIn(
                globalScope: false,
                orderBy:['id'=>'desc'],
                filters: $filters,
                relations: ['product', 'customer'],
                dataLimit:getWebConfig(name: 'pagination_limit'));
        }
        $data = [
            'data-from' =>'admin',
            'reviews' => $reviews,
            'product_name' => $request->has('product_id') ? $this->productRepo->getFirstWhere(params:['id'=>$request['product_id']])['name'] : "all_products",
            'customer_name' => $request->has('customer_id') && $request->has('customer_id') != 'all' ? $this->customerRepo->getFirstWhere(params: ['id'=>$request['customer_id']]) : "all_customers",
            'from' => $request['from'],
            'to' => $request['to'],
            'status' => $request['status'],
            'key' => $request['search'],
        ];
        return Excel::download(new CustomerReviewListExport($data), 'Customer-Review-List.xlsx');
    }

    public function getCustomerList(Request $request): JsonResponse
    {
        $data = $this->customerRepo->getCustomerList(request:$request);
        return response()->json($data);
    }

    public function search(Request $request): JsonResponse
    {
        $products = $this->productRepo->getListWithScope(
            searchValue: $request['name'],
            scope: 'active',
            relations: ['category','brand','seller.shop'],
            dataLimit: 'all');
        return response()->json([
            'result' => view(Review::SEARCH_PRODUCT[VIEW], compact('products'))->render(),
        ]);
    }
}
