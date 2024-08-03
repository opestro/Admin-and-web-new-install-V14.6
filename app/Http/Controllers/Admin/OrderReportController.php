<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Report;
use App\Utils\Helpers;
use App\Exports\OrderReportExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderReportController extends Controller
{
    public function __construct(
        private readonly VendorRepositoryInterface $vendorRepo,
    )
    {
    }
    public function order_list(Request $request)
    {
        $seller_id = $request->seller_id ?? 'all';
        $date_type = $request->date_type ?? 'this_year';
        $from = $request->from;
        $to = $request->to;
        $search = $request->search;
        $query_param = ['seller_id'=>$seller_id, 'search' => $request->search, 'date_type' => $date_type, 'from' => $from, 'to' => $to];
        $sellers = Seller::where(['status'=>'approved'])->get();

        $chart_data = self::order_report_chart_filter($request);

        $orders = self::all_order_table_data_filter($request);
        $orders = $orders->latest('updated_at')->paginate(Helpers::pagination_limit())->appends($query_param);

        $ongoing_order_query = Order::whereIn('order_status',['out_for_delivery','processing','confirmed', 'pending']);
        $ongoing_order = self::order_count($request, $ongoing_order_query);

        $cancel_order_query = Order::whereIn('order_status',['canceled','failed','returned']);
        $canceled_order = self::order_count($request, $cancel_order_query);

        $delivered_order_query = Order::where('order_status','delivered');
        $delivered_order = self::order_count($request, $delivered_order_query);

        $order_count = array(
            'ongoing_order'=>$ongoing_order,
            'canceled_order'=>$canceled_order,
            'delivered_order'=>$delivered_order,
            'total_order'=>$canceled_order+$ongoing_order+$delivered_order,
        );

        $due_amount_order_query = Order::whereNotIn('order_status', ['delivered', 'canceled', 'returned', 'failed'])
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            });
        $due_amount = self::date_wise_common_filter($due_amount_order_query, $date_type, $from, $to)->sum('order_amount');

        $settled_amount_query = Order::where('order_status','delivered')
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            });
        $settled_amount = self::date_wise_common_filter($settled_amount_query, $date_type, $from, $to)->sum('order_amount');

        $digital_payment_query = Order::where(['order_status' => 'delivered'])->whereNotIn('payment_method', ['cash', 'cash_on_delivery', 'pay_by_wallet', 'offline_payment']);
        $digital_payment = self::pie_chart_common_query($request, $digital_payment_query)->sum('order_amount');

        $cash_payment_query = Order::where(['order_status' => 'delivered'])->whereIn('payment_method', ['cash', 'cash_on_delivery']);
        $cash_payment = self::pie_chart_common_query($request, $cash_payment_query)->sum('order_amount');

        $wallet_payment_query = Order::where(['order_status' => 'delivered'])->where(['payment_method' => 'pay_by_wallet']);
        $wallet_payment = self::pie_chart_common_query($request, $wallet_payment_query)->sum('order_amount');

        $offline_payment_query = Order::where(['payment_method' => 'offline_payment']);
        $offline_payment = self::pie_chart_common_query($request, $offline_payment_query)->sum('order_amount');

        $total_payment = $cash_payment + $wallet_payment + $digital_payment + $offline_payment;

        $payment_data = [
            'total_payment' => $total_payment,
            'cash_payment' => $cash_payment,
            'wallet_payment' => $wallet_payment,
            'offline_payment' => $offline_payment,
            'digital_payment' => $digital_payment,
        ];

        return view('admin-views.report.order-index', compact('orders', 'order_count', 'payment_data', 'chart_data', 'due_amount', 'settled_amount', 'sellers', 'seller_id', 'search', 'date_type', 'from', 'to'));
    }

    public function order_report_chart_filter($request)
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
            return self::order_report_same_year($request, $current_start_year, $current_end_year, $from_year, $number, $default_inc);
        } elseif ($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d', strtotime($current_month_end));
            return self::order_report_same_month($request, $current_month_start, $current_month_end, $month, $number, $inc);
        } elseif ($date_type == 'this_week') {
            return self::order_report_this_week($request);
        } elseif ($date_type == 'today') {
            return self::getOrderReportForToday($request);
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
                return self::order_report_different_year($request, $start_date, $end_date, $from_year, $to_year);
            } elseif ($from_month != $to_month) {
                return self::order_report_same_year($request, $start_date, $end_date, $from_year, $to_month, $from_month);
            } elseif ($from_month == $to_month) {
                return self::order_report_same_month($request, $start_date, $end_date, $from_month, $to_day, $from_day);
            }

        }
    }

    public function order_report_same_year($request, $start_date, $end_date, $from_year, $number, $default_inc)
    {
        $orders = self::order_report_chart_common_query($request, $start_date, $end_date)
            ->selectRaw('sum(order_amount) as order_amount, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $orderAmount[$month] = 0;
            foreach ($orders as $match) {
                if ($match['month'] == $inc) {
                    $orderAmount[$month] = $match['order_amount'];
                }
            }
        }

        return [
            'order_amount' => $orderAmount ?? [],
        ];
    }

    public function order_report_same_month($request, $start_date, $end_date, $month_date, $number, $default_inc)
    {
        $year_month = date('Y-m', strtotime($start_date));
        $month = substr(date("F", strtotime("$year_month")), 0, 3);

        $orders = self::order_report_chart_common_query($request, $start_date, $end_date)
            ->selectRaw('sum(order_amount) as order_amount, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $order_amount[$inc] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $inc) {
                    $order_amount[$inc] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function order_report_this_week($request)
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        $orders = self::order_report_chart_common_query($request, $start_date, $end_date)
            ->select(
                DB::raw('sum(order_amount) as order_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $order_amount[$day_name[$inc]] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $order_amount[$day_name[$inc]] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }
    public function getOrderReportForToday($request): array
    {
        $number = 1;
        $dayName = [Carbon::today()->format('l')];

        $orders = self::order_report_chart_common_query($request, Carbon::now()->startOfDay(), Carbon::now()->endOfDay())
            ->select(
                DB::raw('sum(order_amount) as order_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc < $number; $inc++) {
            $order_amount[$dayName[$inc]] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $dayName[$inc]) {
                    $order_amount[$dayName[$inc]] = $match['order_amount'];
                }
            }
        }

        return [
            'order_amount' => $order_amount ?? [],
        ];
    }

    public function order_report_different_year($request, $start_date, $end_date, $from_year, $to_year)
    {
        $orders = self::order_report_chart_common_query($request, $start_date, $end_date)
            ->selectRaw('sum(order_amount) as order_amount, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $order_amount[$inc] = 0;
            foreach ($orders as $match) {
                if ($match['year'] == $inc) {
                    $order_amount[$inc] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function order_report_chart_common_query($request, $start_date, $end_date)
    {
        $sellerId = $request['seller_id'] ?? 'all';
        return Order::where([ 'order_status'=>'delivered'])
            ->when($sellerId != 'all', function ($query) use ($sellerId) {
                $query->when($sellerId == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($sellerId != 'inhouse', function ($q) use ($sellerId) {
                    $q->where(['seller_id' => $sellerId, 'seller_is' => 'seller']);
                });
            })
            ->whereBetween('updated_at', [$start_date, $end_date]);
    }

    public function pie_chart_common_query($request, $query)
    {
        $from = $request['from'];
        $to = $request['to'];
        $seller_id = $request['seller_id'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $query_f = $query->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            });

        return self::date_wise_common_filter($query_f, $date_type, $from, $to);;
    }

    public function orderReportExportExcel(Request $request):BinaryFileResponse
    {
        $orders = self::all_order_table_data_filter($request)->latest('updated_at')->get();
        $vendor = $request->has('seller_id') && $request['seller_id'] != 'inhouse' && $request['seller_id'] != 'all' ? ( $this->vendorRepo->getFirstWhere(params: ['id'=>$request['seller_id']],relations: ['shop'])):($request['seller_id'] ?? 'all');
        $data = [
            'orders' =>$orders,
            'search' =>$request['search'],
            'vendor' => $vendor,
            'from' => $request['from'],
            'to' => $request['to'],
            'dateType' => $request['date_type'] ?? 'this_year'
        ];
        return Excel::download(new OrderReportExport($data),Report::ORDER_REPORT_LIST);
    }

    public function all_order_table_data_filter($request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $seller_id = $request['seller_id'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $orders_query = Order::withSum('details', 'tax')
            ->withSum('details', 'discount')
            ->when($search, function ($q) use ($search) {
                $q->orWhere('id', 'like', "%{$search}%");
            })
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            });
        return self::date_wise_common_filter($orders_query, $date_type, $from, $to);
    }

    public function order_count($request, $query){
        $from = $request['from'];
        $to = $request['to'];
        $seller_id = $request['seller_id'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $query_f = $query->when($seller_id != 'all', function ($query) use ($seller_id) {
            $query->when($seller_id == 'inhouse', function ($q) {
                $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
            })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
            });
        });

        $count = self::date_wise_common_filter($query_f, $date_type, $from, $to)->count();
        return $count;
    }

    public function date_wise_common_filter($query, $date_type, $from, $to)
    {
        return $query->when(($date_type == 'this_year'), function ($query) {
            return $query->whereYear('updated_at', date('Y'));
        })
            ->when(($date_type == 'this_month'), function ($query) {
                return $query->whereMonth('updated_at', date('m'))
                    ->whereYear('updated_at', date('Y'));
            })
            ->when(($date_type == 'this_week'), function ($query) {
                return $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when(($date_type == 'today'), function ($query) {
                return $query->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
            })
            ->when(($date_type == 'custom_date' && !is_null($from) && !is_null($to)), function ($query) use ($from, $to) {
                return $query->whereDate('updated_at', '>=', $from)
                    ->whereDate('updated_at', '<=', $to);
            });
    }

    public function exportOrderReportInPDF(Request $request)
    {
        $dateType = $request['date_type'] ?? 'this_year';

        $orders = self::all_order_table_data_filter($request)->latest('updated_at')->get();
        $seller = $request->has('seller_id') && $request['seller_id'] != 'inhouse' && $request['seller_id'] != 'all' ? (Seller::with('shop')->find($request['seller_id'])->f_name) : ($request['seller_id'] ?? 'all');

        $totalOrderAmount = $orders->sum('order_amount') ?? 0;
        $totalProductDiscount = $orders->sum('details_sum_discount') ?? 0;
        $totalCouponDiscount = $orders->sum('discount_amount') ?? 0;
        $totalTax = $orders->sum('details_sum_tax') ?? 0;
        $totalOrderCommission = $orders->sum('admin_commission') ?? 0;

        $totalDeliveryCharge = 0;
        $totalDeliverymanIncentive = 0;
        foreach($orders as $order){
            $totalDeliveryCharge += ($order->shipping_cost - ($order->extra_discount_type == 'free_shipping_over_order_amount' ? $order->extra_discount : 0));
            $totalDeliverymanIncentive += ($order->delivery_type=='self_delivery' && $order->delivery_man_id) ? $order->deliveryman_charge : 0;
        }

        $data = [
            'orders' => $orders,
            'total_orders' => count($orders),
            'search' => $request['search'],
            'seller' => $seller,
            'type' => $request->has('seller_id') ? ($request['seller_id'] != 'inhouse' ? 'seller' : $request['seller_id']) : 'all',
            'from' => $request['from'],
            'to' => $request['to'],
            'company_name' => getWebConfig(name: 'company_name'),
            'company_email' => getWebConfig(name: 'company_email'),
            'company_phone' => getWebConfig(name: 'company_phone'),
            'company_web_logo' => getWebConfig(name: 'company_web_logo'),
            'date_type' => $request['date_type'] ?? 'this_year',
            'total_order_amount' => $totalOrderAmount,
            'total_product_discount' => $totalProductDiscount,
            'total_coupon_discount' => $totalCouponDiscount,
            'total_tax' => $totalTax,
            'total_order_commission' => $totalOrderCommission,
            'total_delivery_charge' => $totalDeliveryCharge,
            'total_deliveryman_incentive' => $totalDeliverymanIncentive,
        ];

        $mpdfView = View::make('admin-views.transaction.total_orders_report_pdf', ['data'=>$data]);
        Helpers::gen_mpdf($mpdfView, 'order_transaction_summary_report_', $dateType);
    }
}
