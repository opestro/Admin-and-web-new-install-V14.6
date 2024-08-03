<?php

namespace App\Http\Controllers\Admin;

use App\Utils\Helpers;
use App\Exports\ProductStockReportExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductStockReportController extends Controller
{
    /**
     * Product stock report list show, search & filtering
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $search = $request['search'];
        $seller_id = $request['seller_id'] ?? 'all';
        $sort = $request['sort'] ?? 'ASC';
        $category_id = $request['category_id'] ?? 'all';

        $stock_limit = \App\Utils\Helpers::get_business_settings('stock_limit');
        $sellers = Seller::where(['status'=>'approved'])->get();
        $categories = Category::where(['position'=>0])->get();
        $query_param = ['search' => $search, 'sort' => $sort, 'seller_id' => $seller_id, 'category_id'=>$category_id];

        $products = self::common_query($request)
            ->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.report.product-stock', compact('categories', 'sellers', 'products', 'stock_limit', 'seller_id', 'search', 'sort', 'category_id'));
    }

    /**
     * Product total stock report export by excel
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function export(Request $request)
    {
        $stock_limit = Helpers::get_business_settings('stock_limit');
        $products = self::common_query($request)->get();
        $seller = $request->has('seller_id') && $request['seller_id'] != 'inhouse' && $request['seller_id'] != 'all' ? (Seller::with('shop')->find($request->seller_id)) : ($request['seller_id']?? 'all');
        $category = $request->has('category_id') && $request['category_id'] != 'all' ? (Category::find($request->category_id)) : ($request['category_id'] ??'all');
        $data = [
            'products' =>$products,
            'search' => $request['search'],
            'seller' => $seller,
            'category' => $category,
            'sort' => $request['sort'] ?? 'ASC',
            'stock_limit' => $stock_limit,
        ];
        return Excel::download(new ProductStockReportExport($data) , 'Product-stock-report.xlsx');
    }

    public function common_query($request){
        $search = $request['search'];
        $seller_id = $request['seller_id'] ?? 'all';
        $sort = $request['sort'] ?? 'ASC';
        $category_id = $request['category_id'] ?? 'all';

        return Product::where(['product_type' => 'physical'])->when(empty($request['seller_id']) || $request['seller_id'] == 'all', function ($query) {
                $query->whereIn('added_by', ['admin', 'seller']);
            })
            ->when($category_id && $category_id!='all', function($query) use($category_id) {
                $query->whereJsonContains('category_ids', ["id" => $category_id]);
            })
            ->when($seller_id == 'in_house', function ($query) {
                $query->where(['added_by' => 'admin']);
            })
            ->when($seller_id != 'in_house' && isset($seller_id) && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where(['added_by' => 'seller', 'user_id' => $seller_id]);
            })
            ->when(!empty($search), function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderBy('current_stock', $sort);
    }

}
