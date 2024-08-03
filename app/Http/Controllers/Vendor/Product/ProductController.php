<?php

namespace App\Http\Controllers\Vendor\Product;

use App\Contracts\Repositories\AttributeRepositoryInterface;
use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ColorRepositoryInterface;
use App\Contracts\Repositories\DealOfTheDayRepositoryInterface;
use App\Contracts\Repositories\FlashDealProductRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Contracts\Repositories\WishlistRepositoryInterface;
use App\Enums\ViewPaths\Vendor\Product;
use App\Enums\WebConfigKey;
use App\Exports\ProductListExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\ProductAddRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Repositories\TranslationRepository;
use App\Services\ProductService;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends BaseController
{
    use FileManagerTrait {
        delete as deleteFile;
        update as updateFile;
    }

    public function __construct(
        private readonly CategoryRepositoryInterface         $categoryRepo,
        private readonly BrandRepositoryInterface            $brandRepo,
        private readonly ProductRepositoryInterface          $productRepo,
        private readonly TranslationRepository               $translationRepo,
        private readonly BusinessSettingRepositoryInterface  $businessSettingRepo,
        private readonly ColorRepositoryInterface            $colorRepo,
        private readonly AttributeRepositoryInterface        $attributeRepo,
        private readonly ReviewRepositoryInterface           $reviewRepo,
        private readonly CartRepositoryInterface             $cartRepo,
        private readonly WishlistRepositoryInterface         $wishlistRepo,
        private readonly FlashDealProductRepositoryInterface $flashDealProductRepo,
        private readonly DealOfTheDayRepositoryInterface     $dealOfTheDayRepo,
        private readonly VendorRepositoryInterface           $vendorRepo,
        private readonly ProductService                      $productService,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|array|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     * Index function is the starting point of a controller
     */
    public function index(?Request $request, string|array $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getListView(request: $request,type: $type);
    }

    public function getListView(Request $request,$type): View
    {
        $vendorId = auth('seller')->id();
        $filters = [
            'added_by' => 'seller',
            'seller_id' => $vendorId,
            'brand_id' => $request['brand_id'],
            'category_id' => $request['category_id'],
            'sub_category_id' => $request['sub_category_id'],
            'sub_sub_category_id' => $request['sub_sub_category_id'],
            'request_status' => $type== 'new-request' ? 0 : ($type == 'approved'  ? '1' : ($type == 'denied' ? '2' : 'all')),
        ];
        $searchValue = $request['searchValue'];
        $products = $this->productRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            searchValue: $searchValue,
            filters: $filters,
            relations: ['translations'],
            dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT)
        );
        $brands = $this->brandRepo->getListWhere(filters: ['status' => 1], dataLimit: 'all');
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $subCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_category_id']]);
        $subSubCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_sub_category_id']]);

        return view(Product::LIST[VIEW], compact('products', 'type','searchValue', 'brands',
            'categories', 'subCategory', 'subSubCategory', 'filters'));
    }

    public function getAddView(): View
    {
        $languages = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'pnc_language']);
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $brands = $this->brandRepo->getListWhere(filters: ['status' => 1], dataLimit: 'all');
        $brandSetting = getWebConfig(name: 'product_brand');
        $digitalProductSetting = getWebConfig(name: 'digital_product');
        $colors = $this->colorRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $attributes = $this->attributeRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $languages = getWebConfig(name: 'pnc_language') ?? null;
        $defaultLanguage = $languages[0];

        return view(Product::ADD[VIEW], compact('languages', 'categories', 'brands', 'brandSetting', 'digitalProductSetting', 'colors', 'attributes', 'languages', 'defaultLanguage'));
    }

    public function add(ProductAddRequest $request, ProductService $service): JsonResponse|RedirectResponse
    {
        if ($request->ajax()) {
            return response()->json([], 200);
        }

        $dataArray = $service->getAddProductData(request: $request, addedBy: 'seller');
        $savedProduct = $this->productRepo->add(data: $dataArray);
        $this->productRepo->addRelatedTags(request: $request, product: $savedProduct);
        $this->translationRepo->add(request: $request, model: 'App\Models\Product', id: $savedProduct->id);

        Toastr::success(translate('product_added_successfully'));
        return redirect()->route('vendor.products.list',['type'=>'all']);
    }

    public function getUpdateView(string|int $id): RedirectResponse|View
    {
        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $id, 'user_id'=>auth('seller')->id(), 'added_by'=>'seller'], relations: ['translations']);
        if(!$product){
            Toastr::error(translate('invalid_product'));
            return redirect()->route('vendor.products.list',['type'=>'all']);
        }

        $product['colors'] = json_decode($product['colors']);
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $brands = $this->brandRepo->getListWhere(filters: ['status' => 1], dataLimit: 'all');
        $brandSetting = getWebConfig(name: 'product_brand');
        $digitalProductSetting = getWebConfig(name: 'digital_product');
        $colors = $this->colorRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $attributes = $this->attributeRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $languages = getWebConfig(name: 'pnc_language') ?? null;
        $defaultLanguage = $languages[0];

        return view(Product::UPDATE[VIEW], compact('product', 'categories', 'brands', 'brandSetting', 'digitalProductSetting', 'colors', 'attributes', 'languages', 'defaultLanguage'));
    }

    public function update(ProductUpdateRequest $request, ProductService $service, string|int $id): JsonResponse|RedirectResponse
    {
        if ($request->ajax()) {
            return response()->json([], 200);
        }

        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $id], relations: ['translations']);
        $dataArray = $service->getUpdateProductData(request: $request, product: $product, updateBy: 'seller');

        $this->productRepo->update(id: $id, data: $dataArray);
        $this->productRepo->addRelatedTags(request: $request, product: $product);
        $this->translationRepo->update(request: $request, model: 'App\Models\Product', id: $id);

        Toastr::success(translate('product_updated_successfully'));
        return redirect()->route(Product::VIEW[ROUTE],['id'=>$product['id']]);

    }

    public function getView(string|int $id): View|RedirectResponse
    {
        $vendorId =  auth('seller')->id();
        $productActive = $this->productRepo->getFirstWhereActive(params: ['id' => $id, 'user_id' =>$vendorId]);
        $relations = ['category', 'brand', 'reviews', 'rating', 'orderDetails', 'orderDelivered','translations'];
        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $id,'user_id' => $vendorId], relations: $relations);
        if(!$product){
            return redirect()->route('vendor.products.list',['type'=>'all']);
        }
        $product['priceSum'] =  $product?->orderDelivered->sum('price');
        $product['qtySum'] =  $product?->orderDelivered->sum('qty');
        $product['discountSum'] =  $product?->orderDelivered->sum('discount');

        $productColors = [];
        $colors = json_decode($product['colors']);
        foreach ($colors as $color) {
            $getColor = $this->colorRepo->getFirstWhere(params: ['code' => $color]);
            if ($getColor) {
                $productColors[$getColor['name']] = $colors;
            }
        }

        $reviews = $this->reviewRepo->getListWhere(filters: ['product_id' => ['product_id' => $id], 'whereNull' => ['column' => 'delivery_man_id']], dataLimit: getWebConfig(name: 'pagination_limit'));
        return view(Product::VIEW[VIEW], compact('product', 'reviews', 'productActive', 'productColors'));
    }
    public function exportList(Request $request, string $type): BinaryFileResponse
    {
        $vendorId = auth('seller')->id();
        $vendor = $this->vendorRepo->getFirstWhere(params:['id' => $vendorId]);
        $filters = [
            'added_by' => 'seller',
            'seller_id' => $vendorId,
            'brand_id' => $request['brand_id'],
            'category_id' => $request['category_id'],
            'sub_category_id' => $request['sub_category_id'],
            'sub_sub_category_id' => $request['sub_sub_category_id'],
            'request_status' => $type== 'new-request' ? 0 : ($type == 'approved'  ? 1 : ($type == 'denied' ? 2 : 'all')),
        ];
        $products = $this->productRepo->getListWhere(orderBy: ['id' => 'desc'], searchValue: $request['searchValue'], filters: $filters, relations: ['translations'], dataLimit: 'all');

        //export from product
        $category = (!empty($request['category_id']) && $request->has('category_id')) ? $this->categoryRepo->getFirstWhere(params: ['id' => $request['category_id']]) : 'all';
        $subCategory = (!empty($request->sub_category_id) && $request->has('sub_category_id')) ? $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_category_id']]) : 'all';
        $subSubCategory = (!empty($request->sub_sub_category_id) && $request->has('sub_sub_category_id')) ? $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_sub_category_id']]) : 'all';
        $brand = (!empty($request->brand_id) && $request->has('brand_id')) ? $this->brandRepo->getFirstWhere(params: ['id' => $request->brand_id]) : 'all';
        $seller = (!empty($request->seller_id) && $request->has('seller_id')) ? $this->sellerRepo->getFirstWhere(params: ['id' => $request->seller_id]) : '';
        $data = [
            'data-from' => 'vendor',
            'vendor' =>$vendor,
            'products' => $products,
            'category' => $category,
            'sub_category' => $subCategory,
            'sub_sub_category' => $subSubCategory,
            'brand' => $brand,
            'searchValue' => $request['searchValue'],
            'type' => $request->type ?? '',
            'seller' => $seller,
            'status' => $request->status ?? '',
        ];
        return Excel::download(new ProductListExport($data), ucwords($request['type']) . '-' . 'product-list.xlsx');
    }
    public function getSkuCombinationView(Request $request, ProductService $service): JsonResponse
    {
        $combinationView = $service->getSkuCombinationView(request: $request);
        return response()->json(['view' => $combinationView]);
    }

    public function getCategories(Request $request, ProductService $service): JsonResponse
    {
        $parentId = $request['parent_id'];
        $filter = ['parent_id' => $parentId];
        $categories = $this->categoryRepo->getListWhere(filters: $filter, dataLimit: 'all');
        $dropdown = $service->getCategoryDropdown(request: $request, categories: $categories);

        $childCategories = '';
        if (count($categories) == 1) {
            $subCategories = $this->categoryRepo->getListWhere(filters: ['parent_id' => $categories[0]['id']], dataLimit: 'all');
            $childCategories = $service->getCategoryDropdown(request: $request, categories: $subCategories);
        }

        return response()->json([
            'select_tag' => $dropdown,
            'sub_categories' => count($categories) == 1 ? $childCategories : '',
        ]);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $status = $request['status'];
        $productId = $request['id'];
        $product = $this->productRepo->getFirstWhere(params: ['id' => $productId, 'user_id' => auth('seller')->id()]);
        $success = 0;

        if ($status == 1 && $product['request_status'] == 1) {
            $this->productRepo->update(id: $productId, data: ['status' => $status]);
            $success = 1;
        } elseif ($status != 1) {
            $this->productRepo->update(id: $productId, data: ['status' => $status ?? 0]);
            $success = 1;
        }

        return response()->json([
            'success' => $success,
            'message' => $success ? translate("status_updated_successfully") : translate("status_updated_failed").' '.translate("Product_must_be_approved"),
        ], 200);
    }

    public function getBarcodeView(Request $request, string|int $id): View|RedirectResponse
    {
        if ($request['limit'] > 270) {
            Toastr::warning(translate('you_can_not_generate_more_than_270_barcode'));
            return back();
        }
        $product = $this->productRepo->getFirstWhere(params: ['id' => $id, 'user_id' => auth('seller')->id()]);
        $rangeData = range(1, $request->limit ?? 4);
        $barcodes = array_chunk($rangeData, 24);
        return view(Product::BARCODE_VIEW[VIEW], compact('product', 'barcodes'));
    }

    public function delete(string|int $id, ProductService $service): RedirectResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id' => $id, 'user_id' => auth('seller')->id()]);

        if($product){
            $this->translationRepo->delete(model: 'App\Models\Product', id: $id);
            $this->cartRepo->delete(params: ['product_id' => $id]);
            $this->wishlistRepo->delete(params: ['product_id' => $id]);
            $this->flashDealProductRepo->delete(params: ['product_id' => $id]);
            $this->dealOfTheDayRepo->delete(params: ['product_id' => $id]);
            $service->deleteImages(product: $product);
            $this->productRepo->delete(params: ['id' => $id]);
            Toastr::success(translate('product_removed_successfully'));
        }else{
            Toastr::error(translate('invalid_product'));
        }

        return back();
    }

    public function getStockLimitListView(Request $request): View
    {
        $vendorId = auth('seller')->id();
        $stockLimit = getWebConfig(name: 'stock_limit');
        $sortOrderQty = $request['sortOrderQty'];
        $searchValue = $request['searchValue'];
        $withCount = ['orderDetails'];
        $status = $request['status'];
        $filters = [
            'added_by' => 'seller',
            'request_status' => 1,
            'product_type' => 'physical',
            'seller_id' => $vendorId,
        ];

        $orderBy = [];
        if ($sortOrderQty == 'quantity_asc') {
            $orderBy = ['current_stock' => 'asc'];
        }else if ($sortOrderQty == 'quantity_desc') {
            $orderBy = ['current_stock' => 'desc'];
        } elseif ($sortOrderQty == 'order_asc') {
            $orderBy = ['order_details_count' => 'asc'];
        } elseif ($sortOrderQty == 'order_desc') {
            $orderBy = ['order_details_count' => 'desc'];
        } elseif ($sortOrderQty == 'default') {
            $orderBy = ['id' => 'asc'];
        }

        $products = $this->productRepo->getStockLimitListWhere(orderBy: $orderBy, searchValue: $searchValue, filters: $filters, withCount: $withCount, relations: ['translations'], dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        return view(Product::STOCK_LIMIT[VIEW], compact('products', 'searchValue', 'status', 'sortOrderQty', 'stockLimit'));
    }

    public function updateQuantity(Request $request): RedirectResponse
    {
        $variations = [];
        $stockCount = $request['current_stock'];
        if ($request->has('type')) {
            foreach ($request['type'] as $key => $str) {
                $item = [];
                $item['type'] = $str;
                $item['price'] = currencyConverter(amount: abs($request['price_' . str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_' . str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_' . str_replace('.', '_', $str)]);
                $variations[] = $item;
            }
        }
        $dataArray = [
            'current_stock' => $stockCount,
            'variation' => json_encode($variations),
        ];

        if ($stockCount >= 0) {
            $this->productRepo->update(id: $request['product_id'], data: $dataArray);
            Toastr::success(translate('product_quantity_updated_successfully'));
            return back();
        }
        Toastr::warning(translate('product_quantity_can_not_be_less_than_0_'));
        return back();
    }

    public function deleteImage(Request $request, ProductService $service): RedirectResponse
    {
        $this->deleteFile(filePath: '/product/' . $request['image']);
        $product = $this->productRepo->getFirstWhere(params: ['id' => $request['id']]);

        if (count(json_decode($product['images'])) < 2) {
            Toastr::warning(translate('you_can_not_delete_all_images'));
            return back();
        }
        $imageProcessing = $service->deleteImage(request: $request, product: $product);
        $updateData = [
            'images' => json_encode($imageProcessing['images']),
            'color_image' => json_encode($imageProcessing['color_images']),
        ];
        $this->productRepo->update(id: $request['id'], data: $updateData);

        Toastr::success(translate('product_image_removed_successfully'));
        return back();
    }

    public function getVariations(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id' => $request['id']]);
        return response()->json([
            'view' => view(Product::GET_VARIATIONS[VIEW], compact('product'))->render()
        ]);
    }

    public function getBulkImportView(): View
    {
        return view(Product::BULK_IMPORT[VIEW]);
    }

    public function importBulkProduct(Request $request, ProductService $service): RedirectResponse
    {
        $dataArray = $service->getImportBulkProductData(request: $request, addedBy: 'seller');
        if (!$dataArray['status']) {
            Toastr::error($dataArray['message']);
            return back();
        }

        $this->productRepo->addArray(data: $dataArray['products']);
        Toastr::success($dataArray['message']);
        return back();
    }

    public function getSearchedProductsView(Request $request):JsonResponse
    {
        $searchValue = $request['searchValue'] ?? null;
        $products = $this->productRepo->getListWhere(
            searchValue:$searchValue,
            filters: [
                'added_by' => 'seller',
                'seller_id' => auth('seller')->id(),
                'status' => 1,
                'category_id' => $request['category_id'],
                'code' => $request['name']??null,
            ],
            dataLimit:getWebConfig(name:'pagination_limit')
        );
        return response()->json([
            'count' => $products->count(),
            'result' => view(Product::SEARCH[VIEW], compact('products'))->render(),
        ]);
    }


    public function getProductGalleryView(Request $request): View
    {
        $vendorId = auth('seller')->id();
        $searchValue = $request['searchValue'];
        $filters = [
            'added_by' => 'seller',
            'searchValue' => $searchValue,
            'request_status' => 1,
            'seller_id' => $vendorId,
            'brand_id' => $request['brand_id'],
            'category_id' => $request['category_id'],
        ];
        $products = $this->productRepo->getListWhere(orderBy: ['id' => 'desc'], searchValue: $request['searchValue'], filters: $filters, relations: ['translations'], dataLimit:getWebConfig(WebConfigKey::PAGINATION_LIMIT) );

        $products->map(function ($product){
            if ($product->product_type == 'physical' && count(json_decode($product->choice_options)) >0 || count(json_decode($product->colors)) >0 ){
                $colorName = [];
                $colorsCollection = collect(json_decode($product->colors));
                $colorsCollection->map(function ($color) use (&$colorName) {
                    $colorName[] = $this->colorRepo->getFirstWhere(['code' => $color])->name;
                });
                $product['colorsName'] = $colorName;
            }
        });

        $brands = $this->brandRepo->getListWhere(filters: ['status' => 1], dataLimit: 'all');

        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');

        return view(Product::PRODUCT_GALLERY[VIEW], compact('products',  'brands', 'categories','searchValue'));

    }
    public function getStockLimitStatus(Request $request): JsonResponse
    {
        $vendorId = auth('seller')->id();
        $filters = [
            'added_by' => 'seller',
            'product_type' => 'physical',
            'request_status' => $request['status'],
            'seller_id' => $vendorId,
        ];
        $products = $this->productRepo->getStockLimitListWhere(filters: $filters,dataLimit: 'all');
        if ($products->count() == 1 ){
            $product = $products->first();
            $thumbnail = getValidImage(path: 'storage/app/public/product'.$product['thumbnail'],type: 'backend-product');
            return response()->json(['status'=>'one_product','product_count'=>1,'product'=>$product,'thumbnail'=>$thumbnail]);
        }else{
            return response()->json(['status'=>'multiple_product','product_count'=>$products->count()]);
        }

    }
}
