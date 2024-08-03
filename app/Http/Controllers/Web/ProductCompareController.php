<?php

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\AttributeRepositoryInterface;
use App\Contracts\Repositories\ProductCompareRepositoryInterface;
use App\Enums\GlobalConstant;
use App\Enums\SessionKey;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\ProductCompareRequest;
use App\Services\ProductCompareService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductCompareController extends BaseController
{
    /**
     * @param ProductCompareRepositoryInterface $productCompareRepo
     * @param ProductCompareService $productCompareService
     * @param AttributeRepositoryInterface $attributeRepo
     */
    public function __construct(
        private readonly ProductCompareRepositoryInterface $productCompareRepo,
        private readonly ProductCompareService $productCompareService,
        private readonly AttributeRepositoryInterface $attributeRepo,
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
       return $this->getProductCompareListView();
    }

    /**
     * @return View
     */
    public function getProductCompareListView():View
    {
        $customerId =auth('customer')->id();
        $compareLists = $this->productCompareRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            filters: ['user_id' => $customerId, 'whereHas' => 'product'],
            relations: ['product'],
            dataLimit: 'all'
        );
        $attributes = [];
        if (theme_root_path() == GlobalConstant::THEME_LIFESTYLE) {
            $attributes = $this->attributeRepo->getList(
                orderBy: ['id' => 'desc'], dataLimit: 'all',
            );
        }

        return view(VIEW_FILE_NAMES['account_compare_list'], compact('compareLists','attributes'));
    }

    /**
     * @param ProductCompareRequest $request
     * @return JsonResponse|RedirectResponse
     * @note if ($request->ajax()) {this request come from product details and home page and other product related section }
     * @note if (!$request->ajax()) {this request come  from user profile compare list tab  }
     */
    public function add(ProductCompareRequest $request):JsonResponse|RedirectResponse
    {
        if ($request->ajax()) {
            if (auth('customer')->check()) {
                $customerId = auth('customer')->id();
                $compareList = $this->productCompareRepo->getFirstWhere(params: ['user_id' => $customerId, 'product_id' => $request['product_id']]);
                if ($compareList) {
                    $this->productCompareRepo->delete(params: ['id' => $compareList['id']]);
                    $compareLists = $this->productCompareRepo->getListWhere(
                        orderBy: ['id' => 'desc'],
                        filters: ['user_id' => $customerId, 'whereHas' => 'product'],
                        relations: ['product'],
                        dataLimit: 'all'
                    );
                    $compareProductIds = $compareLists->pluck('product_id')->toArray();
                    session()->forget(SessionKey::PRODUCT_COMPARE_LIST);
                    session()->put(SessionKey::PRODUCT_COMPARE_LIST, $compareProductIds);
                    return response()->json([
                        'error' => translate("compare_list_Removed"),
                        'value' => 2,
                        'count' => $compareLists->count(),
                        'product_count' => count($compareProductIds),
                        'compare_product_ids' => $compareProductIds
                    ]);
                } else {
                    $compareLists = $this->productCompareRepo->getListWhere(
                        orderBy: ['id' => 'asc'],
                        filters: ['user_id' => $customerId, 'whereHas' => 'product'],
                        dataLimit: 'all'
                    );
                    if ($compareLists->count() == 3) {
                        $compareList = $compareLists->first();
                        $this->productCompareRepo->delete(params: ['id' => $compareList['id']]);
                    }
                    $this->productCompareRepo->add(data: $this->productCompareService->getAddData(customerId: $customerId, productId: $request['product_id']));
                    $compareLists = $this->productCompareRepo->getListWhere(
                        orderBy: ['id' => 'desc'],
                        filters: ['user_id' => $customerId, 'whereHas' => 'product'],
                        relations: ['product'],
                        dataLimit: 'all'
                    );
                    $compareProductIds = $compareLists->pluck('product_id')->toArray();
                    session()->forget(SessionKey::PRODUCT_COMPARE_LIST);
                    session()->put(SessionKey::PRODUCT_COMPARE_LIST, $compareProductIds);
                    return response()->json([
                        'success' => translate("product_added_to_compare_list"),
                        'value' => 1,
                        'count' => $compareLists->count(),
                        'id' => $request['product_id'],
                        'product_count' => count($compareProductIds),
                        'compare_product_ids' => $compareProductIds
                    ]);
                }
            } else {
                return response()->json(['error' => translate('login_first'), 'value' => 0]);
            }
        } else {
            $customerId = auth('customer')->id();
            $compareList = $this->productCompareRepo->getFirstWhere(params: ['user_id' => $customerId, 'product_id' => $request['product_id']]);
            if ($compareList) {
                return redirect()->back();
            } else {
                $compareLists = $this->productCompareRepo->getListWhere(
                    orderBy: ['id' => 'asc'],
                    filters: ['user_id' => $customerId, 'whereHas' => 'product'],
                    dataLimit: 'all'
                );
                if ($compareLists->count() == 3) {
                    $compareList = $compareLists->first();
                    $this->productCompareRepo->delete(params: ['id' => $compareList['id']]);
                }

                $this->productCompareRepo->add(data: $this->productCompareService->getAddData(customerId: $customerId, productId: $request['product_id']));
                $compareLists = $this->productCompareRepo->getListWhere(
                    orderBy: ['id' => 'desc'],
                    filters: ['user_id' => $customerId, 'whereHas' => 'product'],
                    relations: ['product'],
                    dataLimit: 'all'
                );
                $compareProductIds = $compareLists->pluck('product_id')->toArray();
                session()->forget(SessionKey::PRODUCT_COMPARE_LIST);
                session()->put(SessionKey::PRODUCT_COMPARE_LIST, $compareProductIds);
            }
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request):RedirectResponse
    {
        $this->productCompareRepo->delete(params: ['id' => $request['id'], 'user_id' => auth('customer')->id()]);
        $compareLists = $this->productCompareRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            filters: ['user_id' => auth('customer')->id()],
            dataLimit: 'all'
        )->pluck('product_id')->toArray();
        session()->forget(SessionKey::PRODUCT_COMPARE_LIST);
        session()->put(SessionKey::PRODUCT_COMPARE_LIST, $compareLists);
        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function deleteAllCompareProduct():RedirectResponse
    {
        $customerId = auth('customer')->id();
        $this->productCompareRepo->delete(params: ['user_id' => $customerId]);
        $compareLists = $this->productCompareRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            filters: ['user_id' => auth('customer')->id()],
            dataLimit: 'all'
        )->pluck('product_id')->toArray();
        session()->forget(SessionKey::PRODUCT_COMPARE_LIST);
        session()->put(SessionKey::PRODUCT_COMPARE_LIST, $compareLists);
        return redirect()->back();
    }
}
