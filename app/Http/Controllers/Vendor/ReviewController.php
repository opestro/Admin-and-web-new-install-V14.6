<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\ViewPaths\Vendor\Review;
use App\Exports\CustomerReviewListExport;
use App\Http\Controllers\BaseController;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReviewController extends BaseController
{
    /**
     * @param ReviewRepositoryInterface $reviewRepo
     * @param ProductRepositoryInterface $productRepo
     * @param CustomerRepositoryInterface $customerRepo
     */
    public function __construct(
        private readonly ReviewRepositoryInterface   $reviewRepo,
        private readonly ProductRepositoryInterface  $productRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly VendorRepositoryInterface $vendorRepo,
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
        return $this->getList($request);
    }

    /**
     * @param object $request
     * @return View
     */
    public function getList(object $request): View
    {
        $vendorId = auth('seller')->id();
        $filters = [
            'product_id' => $request['product_id'],
            'customer_id' => $request['customer_id'],
            'status' => $request['status'],
            'from' => $request['from'],
            'to' => $request['to'],

        ];
        if ($request->has('searchValue')) {
            $productId = $this->productRepo->getListWhere(
                searchValue: $request['searchValue'],
                filters: ['added_by' => 'seller', 'seller_id' => $vendorId],
                dataLimit: 'all')->pluck('id')->toArray();
            $customerIds = $this->customerRepo->getListWhere(searchValue: $request['searchValue'], dataLimit: 'all')->pluck('id')->toArray();
            $whereInFilters = [
                'product_id' => $productId,
                'customer_id' => $customerIds,
            ];
            $reviews = $this->reviewRepo->getListWhereIn(
                globalScope: false,
                orderBy: ['id' => 'desc'],
                whereInFilters: $whereInFilters,
                relations: ['product', 'customer'],
                nullFields: ['delivery_man_id'],
                dataLimit: getWebConfig('pagination_limit'));
        } else {
            $reviews = $this->reviewRepo->getListWhereHas(
                globalScope: false,
                whereHas: 'product',
                whereHasFilter: ['added_by' => 'seller', 'user_id' => $vendorId],
                orderBy: ['id' => 'desc'],
                filters: $filters,
                relations: ['product', 'customer'],
                dataLimit: getWebConfig('pagination_limit'),

            );
        }
        $products = $this->productRepo->getListWhereNotIn(
            filters: ['user_id' => $vendorId, 'added_by' => 'seller'],
            whereNotIn: ['request_status' => [1]],
        )->take(FILTER_PRODUCT_DATA_LIMIT);
        $product = $this->productRepo->getFirstWhere(params: ['id' => $request['product_id']]);
        $customer = "all";
        if ($request['customer_id'] != 'all' && !is_null($request['customer_id']) && $request->has('customer_id')) {
            $customer = $this->customerRepo->getFirstWhere(params: ['id' => $request['customer_id']]);
        }
        return view(Review::INDEX[VIEW], [
            'reviews' => $reviews,
            'products' => $products,
            'product' => $product,
            'customer' => $customer,
            'from' => $request['from'],
            'to' => $request['to'],
            'customer_id' => $request['customer_id'],
            'product_id' => $request['product_id'],
            'status' => $request['status'],
            'searchValue' => $request['searchValue'],
        ]);
    }

    /**
     * @param string|int $id
     * @param string|int $status
     * @return JsonResponse
     */
    public function updateStatus(string|int $id, string|int $status): JsonResponse
    {
        $this->reviewRepo->update(id: $id, data: ['status' => $status]);
        return response()->json([
            'status' => 1,
            'message' => translate('review_status_updated.')
        ]);
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse|RedirectResponse
     */
    public function exportList(Request $request): BinaryFileResponse|RedirectResponse
    {
        $vendorId = auth('seller')->id();
        $vendor = $this->vendorRepo->getFirstWhere(params:['id' => $vendorId]);
        $filters = [
            'product_id' => $request['product_id'],
            'customer_id' => $request['customer_id'],
            'status' => $request['status'],
            'from' => $request['from'],
            'to' => $request['to'],
        ];
        if ($request->has('searchValue')) {
            $productId = $this->productRepo->getListWhere(
                searchValue: $request['searchValue'],
                filters: ['added_by' => 'seller', 'seller_id' => $vendorId],
                dataLimit: 'all')->pluck('id')->toArray();
            $customerIds = $this->customerRepo->getListWhere(searchValue: $request['searchValue'], dataLimit: 'all')->pluck('id')->toArray();
            $whereInFilters = [
                'product_id' => $productId,
                'customer_id' => $customerIds,
            ];
            $reviews = $this->reviewRepo->getListWhereIn(
                globalScope: false,
                orderBy: ['id' => 'desc'],
                whereInFilters: $whereInFilters,
                relations: ['product', 'customer'],
                nullFields: ['delivery_man_id'],
                dataLimit: getWebConfig('pagination_limit'));
        } else {
            $reviews = $this->reviewRepo->getListWhereHas(
                globalScope: false,
                whereHas: 'product',
                whereHasFilter: ['added_by' => 'seller', 'user_id' => $vendorId],
                orderBy: ['id' => 'desc'],
                filters: $filters,
                relations: ['product', 'customer'],
                dataLimit: getWebConfig('pagination_limit'),

            );
        }
        $data = [
            'data-from' =>'vendor',
            'vendor' => $vendor,
            'reviews' => $reviews,
            'product_name' => $request->has('product_id') ? $this->productRepo->getFirstWhere(params: ['id' => $request['product_id']])['name'] : "all_products",
            'customer_name' => $request->has('customer_id') ? $this->customerRepo->getFirstWhere(params: ['id' => $request['customer_id']]) : "all_customers",
            'from' => $request['from'],
            'to' => $request['to'],
            'status' => $request['status'],
            'key' => $request['search'],
        ];
        return Excel::download(new CustomerReviewListExport($data), 'Product-Review-List.xlsx');
    }
}
