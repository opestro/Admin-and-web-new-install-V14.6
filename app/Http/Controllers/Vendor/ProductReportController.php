<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Exports\ProductReportExport;
use App\Exports\ProductStockReportExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Utils\Helpers;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductReportController extends Controller
{
    public function __construct(
        private readonly VendorRepositoryInterface $vendorRepo,
    )
    {
    }
    public function all_product(Request $request){
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';
        $seller_id = auth('seller')->id();
        $query_param = ['search' => $search, 'date_type' => $date_type, 'from' => $from, 'to' => $to];

        $chart_data = self::all_product_chart_filter($request);

        $product_query = Product::with(['reviews'])
            ->with(['orderDetails' => function ($query) {
                $query->select(
                        'product_id',
                        DB::raw('SUM(qty * price) as total_sold_amount'),
                        DB::raw('SUM(qty) as product_quantity'),
                    )
                    ->where('delivery_status', 'delivered')->groupBy('product_id')
                    ->groupBy('product_id');
            }])
            ->when($search, function ($query) use ($search) {
                $query->orWhere('name', 'like', "%{$search}%");
            })
            ->where(['user_id' => $seller_id, 'added_by' => 'seller']);
        $products = self::create_date_wise_common_filter($product_query, $date_type, $from, $to)
            ->latest('created_at')
            ->paginate(Helpers::pagination_limit())
            ->appends($query_param);

        $total_sales_value = 0;
        foreach($products as $key=>$product){
            $total_sales_value += (isset($product->orderDetails[0]->total_sold_amount) ? $product->orderDetails[0]->total_sold_amount : 0) / (isset($product->orderDetails[0]->product_quantity) ? $product->orderDetails[0]->product_quantity : 1);
        }

        $total_product_sale_query = Product::with(['orderDetails'=>function($query){
                $query->select(
                    'product_id',
                    DB::raw('SUM(qty * price) as total_sale_amount'),
                    DB::raw('SUM(qty) as product_quantity'),
                    DB::raw('SUM(discount) as total_discount')
                )->groupBy('product_id');
            }])
            ->whereHas('orderDetails',function($query){
                $query->where('delivery_status', 'delivered');
            })
            ->where(['user_id' => $seller_id, 'added_by' => 'seller']);
        $total_product_sales = self::create_date_wise_common_filter($total_product_sale_query, $date_type, $from, $to)
            ->latest('created_at')
            ->get();

        $total_product_sale = 0;
        $total_product_sale_amount = 0;
        $total_discount_given = 0;
        if(count($total_product_sales) > 0) {
            foreach ($total_product_sales as $sales) {
                foreach ($sales->orderDetails as $sale) {
                    $total_product_sale += isset($sale->product_quantity) ? $sale->product_quantity : 0;
                    $total_discount_given += isset($sale->total_discount) ? $sale->total_discount : 0;
                    $total_product_sale_amount += isset($sale->total_sale_amount) ? $sale->total_sale_amount : 0;
                }
            }
        }

        $reject_product_count_query = Product::where(['request_status' => 2, 'user_id' => $seller_id, 'added_by' => 'seller']);
        $reject_product_count = self::create_date_wise_common_filter($reject_product_count_query, $date_type, $from, $to)->count();

        $pending_product_count_query = Product::where(['request_status'=>'0', 'user_id' => $seller_id, 'added_by' => 'seller']);
        $pending_product_count = self::create_date_wise_common_filter($pending_product_count_query, $date_type, $from, $to)->count();

        $active_product_count_query = Product::where(['request_status' => 1, 'user_id' => $seller_id, 'added_by' => 'seller']);
        $active_product_count = self::create_date_wise_common_filter($active_product_count_query, $date_type, $from, $to)->count();

        $product_count = array(
            'reject_product_count'=> $reject_product_count,
            'active_product_count'=> $active_product_count,
            'pending_product_count'=> $pending_product_count
        );

        $top_product = OrderDetail::with('product')
            ->select(DB::raw("product_id, sum(qty*price) as total_amount"))
            ->whereHas('product', function ($query) use($seller_id){
                $query->where(['user_id' => $seller_id, 'added_by' => 'seller', 'delivery_status'=>'delivered']);
            })
            ->where(['delivery_status'=>'delivered'])
            ->groupBy('product_id')
            ->orderBy("total_amount", 'desc')
            ->take(5)
            ->get();


        return view('vendor-views.report.all-product', compact('products', 'chart_data', 'total_sales_value',
            'total_product_sale', 'total_product_sale_amount', 'top_product', 'total_discount_given', 'product_count', 'search', 'date_type', 'from', 'to'));
    }

    public function all_product_chart_filter($request)
    {
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';

        if ($date_type == 'this_year') {
            $number = 12;
            $default_inc = 1;
            $current_start_year = date('Y-01-01');
            $current_end_year = date('Y-12-31');
            $from_year = Carbon::parse($from)->format('Y');
            return self::all_product_same_year($current_start_year, $current_end_year, $from_year, $number, $default_inc);
        } elseif ($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d', strtotime($current_month_end));
            return self::all_product_same_month($current_month_start, $current_month_end, $month, $number, $inc);
        } elseif ($date_type == 'this_week') {
            return self::all_product_this_week();
        } elseif ($date_type == 'today') {
            return self::getAllProductForToday();
        } elseif ($date_type == 'custom_date' && !empty($from) && !empty($to)) {
            $start_date = Carbon::parse($from)->format('Y-m-d 00:00:00');
            $end_date = Carbon::parse($to)->format('Y-m-d 23:59:59');
            $from_year = Carbon::parse($from)->format('Y');
            $from_month = Carbon::parse($from)->format('m');
            $from_day = Carbon::parse($from)->format('d');
            $to_year = Carbon::parse($to)->format('Y');
            $to_month = Carbon::parse($to)->format('m');
            $to_day = Carbon::parse($to)->format('d');

            if ($from_year != $to_year) {
                return self::all_product_different_year($start_date, $end_date, $from_year, $to_year);
            } elseif ($from_month != $to_month) {
                return self::all_product_same_year($start_date, $end_date, $from_year, $to_month, $from_month);
            } elseif ($from_month == $to_month) {
                return self::all_product_same_month($start_date, $end_date, $from_month, $to_day, $from_day);
            }
        }
    }

    public function all_product_same_year($start_date, $end_date, $from_year, $number, $default_inc)
    {

        $products = self::all_product_date_common_query($start_date, $end_date)
            ->selectRaw('count(*) as total_product, YEAR(created_at) year, MONTH(created_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M')"))
            ->latest('created_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $total_product[$month] = 0;
            foreach ($products as $match) {
                if ($match['month'] == $inc) {
                    $total_product[$month] = $match['total_product'];
                }
            }
        }

        return array(
            'total_product' => $total_product,
        );
    }

    public function all_product_same_month($start_date, $end_date, $month_date, $number, $default_inc)
    {
        $year_month = date('Y-m', strtotime($start_date));
        $month = substr(date("F", strtotime("$year_month")), 0, 3);

        $products = self::all_product_date_common_query($start_date, $end_date)
            ->selectRaw('count(*) as total_product, YEAR(updated_at) year, MONTH(created_at) month, DAY(created_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%D')"))
            ->latest('created_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $total_product[$inc] = 0;
            foreach ($products as $match) {
                if ($match['day'] == $inc) {
                    $total_product[$inc] = $match['total_product'];
                }
            }
        }

        return array(
            'total_product' => $total_product,
        );
    }

    public function all_product_this_week()
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        $products = self::all_product_date_common_query($start_date, $end_date)
            ->select(
                DB::raw('count(*) as total_product'),
                DB::raw("(DATE_FORMAT(created_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%D')"))
            ->latest('created_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $total_product[$day_name[$inc]] = 0;
            foreach ($products as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $total_product[$day_name[$inc]] = $match['total_product'];
                }
            }
        }

        return array(
            'total_product' => $total_product,
        );
    }

    public function getAllProductForToday(): array
    {
        $number = 1;
        $dayName = [Carbon::today()->format('l')];

        $products = self::all_product_date_common_query(Carbon::now()->startOfDay(), Carbon::now()->endOfDay())
            ->select(
                DB::raw('count(*) as total_product'),
                DB::raw("(DATE_FORMAT(created_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%D')"))
            ->latest('created_at')->get();

        for ($increment = 0; $increment < $number; $increment++) {
            $totalProduct[$dayName[$increment]] = 0;
            foreach ($products as $match) {
                if ($match['day'] == $dayName[$increment]) {
                    $totalProduct[$dayName[$increment]] = $match['total_product'];
                }
            }
        }

        return [
            'total_product' => $totalProduct ?? [],
        ];
    }

    public function all_product_different_year($start_date, $end_date, $from_year, $to_year)
    {
        $products = self::all_product_date_common_query($start_date, $end_date)
            ->selectRaw('count(*) as total_product, YEAR(created_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))
            ->latest('created_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $total_product[$inc] = 0;
            foreach ($products as $match) {
                if ($match['year'] == $inc) {
                    $total_product[$inc] = $match['total_product'];
                }
            }
        }

        return array(
            'total_product' => $total_product,
        );

    }

    public function all_product_date_common_query($start_date, $end_date)
    {
        return Product::where(['user_id' => auth('seller')->id(), 'added_by' => 'seller'])
            ->whereBetween('created_at', [$start_date, $end_date]);
    }

    public function create_date_wise_common_filter($query, $date_type, $from, $to)
    {
        return $query->when(($date_type == 'this_year'), function ($query) {
                return $query->whereYear('created_at', date('Y'));
            })
            ->when(($date_type == 'this_month'), function ($query) {
                return $query->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'));
            })
            ->when(($date_type == 'this_week'), function ($query) {
                return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when(($date_type == 'today'), function ($query) {
                return $query->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
            })
            ->when(($date_type == 'custom_date' && !is_null($from) && !is_null($to)), function ($query) use ($from, $to) {
                return $query->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            });
    }

    public function allProductExportExcel(Request $request):BinaryFileResponse
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';
        $vendorId = auth('seller')->id();
        $vendor = $this->vendorRepo->getFirstWhere(params:['id' => $vendorId]);
        $product_query = Product::with(['reviews'])
            ->with(['orderDetails' => function ($query) {
                $query->select(
                    'product_id',
                    DB::raw('SUM(qty * price) as total_sold_amount'),
                    DB::raw('SUM(qty) as product_quantity'),
                )
                    ->where('delivery_status', 'delivered')->groupBy('product_id')
                    ->groupBy('product_id');
            }])
            ->when($search, function ($query) use ($search) {
                $query->orWhere('name', 'like', "%{$search}%");
            })
            ->where(['user_id' => $vendorId, 'added_by' => 'seller']);
        $products = self::create_date_wise_common_filter($product_query, $date_type, $from, $to)->latest('created_at')->get();
        $data = [
            'products' =>$products,
            'search' =>$request['search'],
            'seller' => $vendor,
            'from' => $request['from'],
            'to' => $request['to'],
            'date_type' => $request['date_type'] ?? 'this_year'
        ];

        return Excel::download(new ProductReportExport($data) , 'Product-Report-List.xlsx');
    }
    public function stock_product_report(Request $request)
    {
        $search = $request['search'];
        $sort = $request['sort'] ?? 'ASC';
        $category_id = $request['category_id'] ?? 'all';
        $query_param = ['search' => $search, 'sort' => $sort, 'category_id'=>$category_id];

        $stock_limit = \App\Utils\Helpers::get_business_settings('stock_limit');
        $categories = Category::where(['position'=>0])->get();
        $products = self::stock_product_common_query($request)
            ->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('vendor-views.report.product-stock', compact('products', 'categories','search', 'stock_limit', 'sort','category_id'));
    }


    public function productStockExport(Request $request):BinaryFileResponse
    {
        $stock_limit = Helpers::get_business_settings('stock_limit');
        $products = self::stock_product_common_query($request)->get();
        $vendorId = auth('seller')->id();
        $vendor = $this->vendorRepo->getFirstWhere(params:['id' => $vendorId]);
        $category = $request->has('category_id') && $request['category_id'] != 'all' ? (Category::find($request->category_id)) : ($request['category_id'] ??'all');
        $data = [
            'products' =>$products,
            'search' => $request['search'],
            'seller' => $vendor,
            'category' => $category,
            'sort' => $request['sort'] ?? 'ASC',
            'stock_limit' => $stock_limit,
        ];
        return Excel::download(new ProductStockReportExport($data) , 'Product-stock-report.xlsx');
    }
    public function stock_product_common_query($request){
        $sort = $request['sort'] ?? 'ASC';
        $category_id = $request['category_id'] ?? 'all';
        return Product::where(['product_type' => 'physical', 'added_by'=>'seller','user_id'=>auth('seller')->id()])
            ->when($category_id && $category_id!='all', function($query) use($category_id) {
                $query->whereJsonContains('category_ids', ["id" => $category_id]);
            })
            ->when($request['search'], function ($q) use ($request) {
                $q->where('name', 'Like', '%' . $request['search'] . '%');
            })
            ->orderBy('current_stock', $sort);
    }
}
