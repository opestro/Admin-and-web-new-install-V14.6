<?php

namespace App\Http\Controllers\Admin;

use App\Utils\Helpers;
use App\Exports\ProductWishlistedExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;

class ProductWishlistReportController extends Controller
{
    /**
     * Product wishlist report list show, search & filtering
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $search = $request['search'];
        $seller_id = $request['seller_id'];
        $sort = $request['sort'] ?? 'ASC';
        $sellers = \App\Models\Seller::where(['status'=>'approved'])->get() ;

        $products = self::common_query_filter($request)
                ->where(['request_status'=>1])
                ->paginate(Helpers::pagination_limit())
                ->appends(['search' => $request['search'], 'seller_id' => $seller_id, 'sort' => $request['sort']]);

        return view('admin-views.report.product-in-wishlist', compact('products',  'sellers','search', 'seller_id', 'sort'));
    }

    /**
     * Product wishlist report export by excel
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function export(Request $request)
    {
        $sort = $request['sort'] ?? 'ASC';

        $products = self::common_query_filter($request)->where(['request_status'=>1])->get();
        $seller = $request->has('seller_id') && $request['seller_id'] != 'inhouse' && $request['seller_id'] != 'all' ? (Seller::with('shop')->find($request->seller_id)) : ($request['seller_id'] ?? 'all');

        $data = [
            'products' =>$products,
            'search' => $request['search'],
            'seller' => $seller,
            'sort' => $sort,
        ];
        return Excel::download(new ProductWishlistedExport($data) , 'Product-wishlisted-report.xlsx');
    }

    public function common_query_filter($request){
        $search = $request['search'];
        $seller_id = $request['seller_id'];
        $sort = $request['sort'] ?? 'ASC';

        return Product::with(['wishList'])
            ->when($seller_id == 'in_house', function ($query) {
                $query->where(['added_by' => 'admin']);
            })
            ->when($seller_id != 'in_house' && isset($seller_id) && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where(['added_by' => 'seller', 'user_id' => $seller_id]);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'Like', '%' . $search . '%');
            })
            ->when($sort, function ($query) use ($sort) {
                $query->withCount('wishList')
                    ->orderBy('wish_list_count', $sort);
            });
    }
}
