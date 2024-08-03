<?php

namespace App\Http\Controllers\Admin;

use App\Utils\Helpers;
use App\Exports\ProductReportExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductReportController extends Controller
{
    public function all_product(Request $request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $seller_id = $request['seller_id'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';
        $query_param = ['search' => $search, 'seller_id' => $seller_id, 'date_type' => $date_type, 'from' => $from, 'to' => $to];
        $sellers = Seller::where(['status' => 'approved'])->get();

        $chart_data = self::all_product_chart_filter($request);

        $product_query = Product::with(['reviews', 'orderDetails' => function ($query) {
                $query->select(
                    'product_id',
                    DB::raw('SUM(qty * price) as total_sold_amount'),
                    DB::raw('SUM(qty) as product_quantity'),
                )
                ->where('delivery_status', 'delivered')->groupBy('product_id')
                ->groupBy('product_id');
            }])
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['user_id' => 1, 'added_by' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['user_id' => $seller_id, 'added_by' => 'seller']);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->orWhere('name', 'like', "%{$search}%");
            });
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
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['user_id' => 1, 'added_by' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['user_id' => $seller_id, 'added_by' => 'seller']);
                });
            });
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

        $reject_product_count_query = Product::where('request_status', 2);
        $reject_product_count = self::product_count($reject_product_count_query, $date_type, $from, $to, $seller_id);

        $pending_product_count_query = Product::where('request_status', '0');
        $pending_product_count = self::product_count($pending_product_count_query, $date_type, $from, $to, $seller_id);

        $active_product_count_query = Product::where('request_status', 1);
        $active_product_count = self::product_count($active_product_count_query, $date_type, $from, $to, $seller_id);

        $product_count = array(
            'reject_product_count'=> $reject_product_count,
            'active_product_count'=> $active_product_count,
            'pending_product_count'=> $pending_product_count
        );

        return view('admin-views.report.all-product', compact('sellers', 'products', 'chart_data', 'total_sales_value',
            'total_product_sale', 'total_product_sale_amount', 'total_discount_given', 'product_count', 'search', 'date_type', 'from', 'to', 'seller_id'));
    }

    public function product_count($query, $date_type, $from, $to, $seller_id){
        $query_final = $query->when($seller_id != 'all', function ($query) use ($seller_id) {
            $query->when($seller_id == 'inhouse', function ($q) {
                $q->where(['user_id' => 1, 'added_by' => 'admin']);
            })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                $q->where(['user_id' => $seller_id, 'added_by' => 'seller']);
            });
        });

        $count = self::create_date_wise_common_filter($query_final, $date_type, $from, $to)->count();
        return $count;
    }

    public function all_product_chart_filter($request)
    {
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';

        if ($date_type == 'this_year') { //this year table
            $number = 12;
            $default_inc = 1;
            $current_start_year = date('Y-01-01');
            $current_end_year = date('Y-12-31');
            $from_year = Carbon::parse($from)->format('Y');

            return self::all_product_same_year($request, $current_start_year, $current_end_year, $from_year, $number, $default_inc);
        } elseif ($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d', strtotime($current_month_end));

            return self::all_product_same_month($request, $current_month_start, $current_month_end, $month, $number, $inc);
        } elseif ($date_type == 'this_week') {
            return self::all_product_this_week($request);
        } elseif ($date_type == 'today') {
            return self::getAllProductForToday($request);
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
                $different_year = self::all_product_different_year($request, $start_date, $end_date, $from_year, $to_year);
                return $different_year;

            } elseif ($from_month != $to_month) {
                $same_year = self::all_product_same_year($request, $start_date, $end_date, $from_year, $to_month, $from_month);
                return $same_year;

            } elseif ($from_month == $to_month) {
                $same_month = self::all_product_same_month($request, $start_date, $end_date, $from_month, $to_day, $from_day);
                return $same_month;
            }

        }
    }

    public function all_product_same_year($request, $start_date, $end_date, $from_year, $number, $default_inc)
    {

        $products = self::all_product_date_common_query($request, $start_date, $end_date)
            ->selectRaw('count(*) as total_product, YEAR(created_at) year, MONTH(created_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M')"))
            ->latest('created_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $total_product[$month . '-' . $from_year] = 0;
            foreach ($products as $match) {
                if ($match['month'] == $inc) {
                    $total_product[$month . '-' . $from_year] = $match['total_product'];
                }
            }
        }

        return array(
            'total_product' => $total_product,
        );
    }

    public function all_product_same_month($request, $start_date, $end_date, $month_date, $number, $default_inc)
    {
        $year_month = date('Y-m', strtotime($start_date));
        $month = date("F", strtotime("$year_month"));

        $products = self::all_product_date_common_query($request, $start_date, $end_date)
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

    public function all_product_this_week($request)
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        $products = self::all_product_date_common_query($request, $start_date, $end_date)
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
    public function getAllProductForToday($request): array
    {
        $number = 1;
        $dayName = [Carbon::today()->format('l')];

        $products = self::all_product_date_common_query($request, Carbon::now()->startOfDay(), Carbon::now()->endOfDay())
            ->select(
                DB::raw('count(*) as total_product'),
                DB::raw("(DATE_FORMAT(created_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%D')"))
            ->latest('created_at')->get();

        for ($inc = 0; $inc < $number; $inc++) {
            $total_product[$dayName[$inc]] = 0;
            foreach ($products as $match) {
                if ($match['day'] == $dayName[$inc]) {
                    $total_product[$dayName[$inc]] = $match['total_product'];
                }
            }
        }

        return [
            'total_product' => $total_product ?? [],
        ];
    }

    public function all_product_different_year($request, $start_date, $end_date, $from_year, $to_year)
    {

        $products = self::all_product_date_common_query($request, $start_date, $end_date)
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

    public function all_product_date_common_query($request, $start_date, $end_date)
    {
        $seller_id = $request['seller_id'] ?? 'all';
        return Product::when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($query) {
                    $query->where(['user_id' => 1, 'added_by' => 'admin']);
                })->when($seller_id != 'inhouse', function ($query) use ($seller_id) {
                    $query->where(['user_id' => $seller_id, 'added_by' => 'seller']);
                });
            })
            ->whereBetween('created_at', [$start_date, $end_date]);
    }

    public function allProductExportExcel(Request $request):BinaryFileResponse
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $seller_id = $request['seller_id'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

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
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['user_id' => 1, 'added_by' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['user_id' => $seller_id, 'added_by' => 'seller']);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->orWhere('name', 'like', "%{$search}%");
            });
        $products = self::create_date_wise_common_filter($product_query, $date_type, $from, $to)->latest('created_at')->get();

        $seller = $request->has('seller_id') && $request['seller_id'] != 'inhouse' && $request['seller_id'] != 'all' ? (Seller::with('shop')->find($request->seller_id)) : ($request['seller_id'] ?? 'all');
        $data = [
            'products' =>$products,
            'search' =>$request['search'],
            'seller' => $seller,
            'from' => $request['from'],
            'to' => $request['to'],
            'date_type' => $request['date_type'] ?? 'this_year'
        ];

        return Excel::download(new ProductReportExport($data) , 'Product-Report-List.xlsx');
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
}
