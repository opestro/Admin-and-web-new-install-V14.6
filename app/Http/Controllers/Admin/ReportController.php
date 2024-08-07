<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ExportFileNames\Admin\Report;
use App\Exports\AdminEarningReportExport;
use App\Exports\VendorEarningReportExport;
use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\Product;
use App\Models\RefundTransaction;
use App\Models\Seller;
use App\Models\SellerWallet;
use App\Utils\BackEndHelper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function admin_earning(Request $request)
    {
        $from         = $request['from'];
        $to           = $request['to'];
        $date_type    = $request['date_type'] ?? 'this_year';

        $digital_payment_query = Order::where(['order_status' => 'delivered'])->whereNotIn('payment_method', ['cash', 'cash_on_delivery', 'pay_by_wallet', 'offline_payment']);
        $digital_payment = self::earning_common_query($request, $digital_payment_query)->sum('order_amount');

        $cash_payment_query = Order::where(['order_status' => 'delivered'])->whereIn('payment_method', ['cash', 'cash_on_delivery']);
        $cash_payment = self::earning_common_query($request, $cash_payment_query)->sum('order_amount');

        $wallet_payment_query = Order::where(['order_status' => 'delivered'])->where(['payment_method' => 'pay_by_wallet']);
        $wallet_payment = self::earning_common_query($request, $wallet_payment_query)->sum('order_amount');

        $offline_payment_query = Order::where(['payment_method' => 'offline_payment']);
        $offline_payment = self::earning_common_query($request, $offline_payment_query)->sum('order_amount');

        $total_payment = $cash_payment + $wallet_payment + $digital_payment + $offline_payment;

        $payment_data = [
            'total_payment' => $total_payment,
            'cash_payment' => $cash_payment,
            'wallet_payment' => $wallet_payment,
            'offline_payment' => $offline_payment,
            'digital_payment' => $digital_payment,
        ];

        $filter_data = self::earning_common_filter('admin', $date_type, $from, $to);
        $inhouse_earn = $filter_data['earn_from_order'];
        $shipping_earn = $filter_data['shipping_earn'];
        $deliveryman_incentive = $filter_data['deliveryman_incentive'];
        $admin_commission_earn = $filter_data['commission'];
        $refund_given = $filter_data['refund_given'];
        $discount_given = $filter_data['discount_given'];
        $total_tax = $filter_data['total_tax'];
        $admin_bearer_free_shipping = $filter_data['admin_bearer_free_shipping'];

        $total_inhouse_earning = 0;
        $total_commission = 0;
        $total_shipping_earn = 0;
        $total_deliveryman_incentive = 0;
        $total_discount_given = 0;
        $total_refund_given = 0;
        $total_tax_final = 0;
        $total_earning_statistics = array();
        $total_commission_statistics = array();
        foreach($inhouse_earn as $key=>$earning) {
            $total_inhouse_earning += $earning;
            $total_commission += $admin_commission_earn[$key];
            $total_shipping_earn += $shipping_earn[$key];
            $total_deliveryman_incentive += $shipping_earn[$key];
            $total_discount_given += $discount_given[$key];
            $total_tax_final += $total_tax[$key];
            $total_refund_given += $refund_given[$key];
            $total_commission_statistics[$key] = $admin_commission_earn[$key];
            $total_earning_statistics[$key] = ($earning+$admin_commission_earn[$key]+$shipping_earn[$key])-$discount_given[$key]-$refund_given[$key]-$deliveryman_incentive[$key];
        }

        $total_in_house_products_query = Product::where(['added_by' => 'admin']);
        $total_in_house_products = self::earning_common_query($request, $total_in_house_products_query)->count();

        $total_stores_query = Seller::where(['status' => 'approved']);
        $total_stores = self::earning_common_query($request, $total_stores_query)->count();

        $earning_data = [
            'total_inhouse_earning' => $total_inhouse_earning-$total_tax_final,
            'total_commission' => $total_commission,
            'total_shipping_earn' => $total_shipping_earn,
            'total_deliveryman_incentive' => $total_deliveryman_incentive,
            'total_in_house_products' => $total_in_house_products,
            'total_stores' => $total_stores,
            'total_earning_statistics' => $total_earning_statistics,
            'total_commission_statistics' => $total_commission_statistics,
        ];

        return view('admin-views.report.admin-earning', compact('earning_data', 'inhouse_earn', 'shipping_earn', 'deliveryman_incentive',
            'admin_commission_earn', 'refund_given', 'discount_given', 'total_tax', 'from', 'to', 'date_type', 'payment_data'));
    }

    public function exportAdminEarning(Request $request):BinaryFileResponse
    {
        $from         = $request['from'];
        $to           = $request['to'];
        $dateType    = $request['date_type'] ?? 'this_year';
        $filterData = self::earning_common_filter('admin', $dateType, $from, $to);
        $data = [
            'from'=>$from,
            'to'=>$to,
            'dateType' => $dateType,
            'inhouseEarn' => $filterData['earn_from_order'],
            'shippingEarn' => $filterData['shipping_earn'],
            'deliverymanIncentive' => $filterData['deliveryman_incentive'],
            'adminCommissionEarn' => $filterData['commission'],
            'refundGiven' => $filterData['refund_given'],
            'discountGiven' => $filterData['discount_given'],
            'totalTax' => $filterData['total_tax'],
        ];
        return Excel::download(new AdminEarningReportExport($data), Report::ADMIN_EARNING_REPORT);
    }

    public function earning_common_filter($type, $date_type, $from, $to){

        if($date_type == 'this_year') {
            $number = 12;
            $default_inc = 1;
            $current_start_year = date('Y-01-01');
            $current_end_year = date('Y-12-31');
            $from_year = Carbon::parse($from)->format('Y');

            return self::earning_same_year($type, $current_start_year, $current_end_year, $from_year, $number, $default_inc);
        } elseif($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d',strtotime($current_month_end));

            return self::earning_same_month($type, $current_month_start, $current_month_end, $month, $number, $inc);
        } elseif ($date_type == 'this_week') {
            return self::earning_this_week($type);
        } elseif ($date_type == 'today') {
            return self::getEarningDataForToday(type: $type);
        }elseif($date_type == 'custom_date' && !empty($from) && !empty($to)){
            $start_date = Carbon::parse($from)->format('Y-m-d 00:00:00');
            $end_date = Carbon::parse($to)->format('Y-m-d 23:59:59');
            $from_year = Carbon::parse($from)->format('Y');
            $from_month = Carbon::parse($from)->format('m');
            $from_day = Carbon::parse($from)->format('d');
            $to_year = Carbon::parse($to)->format('Y');
            $to_month = Carbon::parse($to)->format('m');
            $to_day = Carbon::parse($to)->format('d');

            if($from_year != $to_year){
                $different_year = self::earning_different_year($type, $start_date, $end_date, $from_year, $to_year);
                return $different_year;

            }elseif($from_month != $to_month){
                $same_year = self::earning_same_year($type, $start_date, $end_date, $from_year, $to_month, $from_month);
                return $same_year;

            }elseif($from_month == $to_month){
                $same_month = self::earning_same_month($type, $start_date, $end_date, $from_month, $to_day, $from_day);
                return $same_month;
            }

        }
    }

    public function getEarningDataForToday($type): array
    {
        $number = 1;
        $dayName = [Carbon::today()->format('l')];

        //earn from order
        $earnFromOrders = Order::where(['order_status' => 'delivered', 'seller_is' => $type])
            ->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->select(
                DB::raw('(sum(order_amount) - sum(shipping_cost) + sum(CASE WHEN is_shipping_free=1 THEN extra_discount ELSE 0 END)) as earn_from_order'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))->latest('updated_at')->get();

        for ($increment = 0; $increment < $number; $increment++) {
            $earnFromOrder[$dayName[$increment]] = 0;
            foreach ($earnFromOrders as $match) {
                if ($match['day'] == $dayName[$increment]) {
                    $earnFromOrder[$dayName[$increment]] = $match['earn_from_order'];
                }
            }
        }

        //shipping earn
        $shippingEarns = Order::whereHas('deliveryMan', function ($query) use ($type) {
                $query->when($type == 'admin', function ($query) {
                    $query->where('seller_id', '0');
                })
                ->when($type == 'seller', function ($query) {
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->select(
                DB::raw('sum(shipping_cost) as shipping_earn'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->where(['order_type' => 'default_type', 'order_status' => 'delivered'])
            ->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($increment = 0; $increment < $number; $increment++) {
            $shippingEarn[$dayName[$increment]] = 0;
            foreach ($shippingEarns as $match) {
                if ($match['day'] == $dayName[$increment]) {
                    $shippingEarn[$dayName[$increment]] = $match['shipping_earn'];
                }
            }
        }

        //deliveryman incentives
        $deliveryman_incentives = Order::whereHas('deliveryMan', function ($query) use ($type) {
                $query->when($type == 'admin', function ($query) {
                    $query->where('seller_id', '0');
                })
                ->when($type == 'seller', function ($query) {
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->select(
                DB::raw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility='.($type=='seller'?'"sellerwise_shipping" AND seller_is="seller"':'"inhouse_shipping" OR seller_is="admin"').' THEN deliveryman_charge ELSE 0 END) as deliveryman_incentive'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->where(['order_type' => 'default_type', 'order_status' => 'delivered'])
            ->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($increment = 0; $increment < $number; $increment++) {
            $deliveryman_incentive[$dayName[$increment]] = 0;
            foreach ($deliveryman_incentives as $match) {
                if ($match['day'] == $dayName[$increment]) {
                    $deliveryman_incentive[$dayName[$increment]] = $match['deliveryman_incentive'];
                }
            }
        }

        //commission
        $commissions = Order::where(['seller_is' => 'seller', 'order_status' => 'delivered'])
            ->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->select(
                DB::raw('sum(admin_commission) as commission'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc < $number; $inc++) {
            $commission[$dayName[$inc]] = 0;
            foreach ($commissions as $match) {
                if ($match['day'] == $dayName[$inc]) {
                    $commission[$dayName[$inc]] = $match['commission'];
                }
            }
        }

        //admin bearer free shipping
        $adminBearerFreeShippingData = Order::where(['seller_is' => 'seller', 'order_status' => 'delivered'])
            ->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->select(
                DB::raw('sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END) as free_shipping_admin_bearer'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc < $number; $inc++) {
            $adminBearerFreeShipping[$dayName[$inc]] = 0;
            foreach ($adminBearerFreeShippingData as $match) {
                if ($match['day'] == $dayName[$inc]) {
                    $adminBearerFreeShipping[$dayName[$inc]] = $match['free_shipping_admin_bearer'];
                }
            }
        }

        //discount_given
        $discountsGivenData = Order::where(['order_status' => 'delivered'])
            ->when($type == 'admin', function ($query) {
                $query->where('coupon_discount_bearer', 'inhouse');
            })
            ->when($type == 'seller', function ($query) {
                $query->where('coupon_discount_bearer', 'seller');
            })
            ->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->select(
                DB::raw('(sum(CASE WHEN discount_type="coupon_discount" THEN discount_amount ELSE 0 END) + sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as discount_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($increment = 0; $increment < $number; $increment++) {
            $discountGiven[$dayName[$increment]] = 0;
            foreach ($discountsGivenData as $match) {
                if ($match['day'] == $dayName[$increment]) {
                    $discountGiven[$dayName[$increment]] = $match['discount_amount'];
                }
            }
        }

        //vat/tax
        $taxes = OrderTransaction::where(['status' => 'disburse', 'seller_is' => $type])
            ->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->select(
                DB::raw('sum(tax) as total_tax'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($increment = 0; $increment < $number; $increment++) {
            $totalTax[$dayName[$increment]] = 0;
            foreach ($taxes as $match) {
                if ($match['day'] == $dayName[$increment]) {
                    $totalTax[$dayName[$increment]] = $match['total_tax'];
                }
            }
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status' => 'paid', 'paid_by' => $type])
            ->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->select(
                DB::raw('sum(amount) as refund_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($increment = 0; $increment < $number; $increment++) {
            $refundGiven[$dayName[$increment]] = 0;
            foreach ($refunds as $match) {
                if ($match['day'] == $dayName[$increment]) {
                    $refundGiven[$dayName[$increment]] = $match['refund_amount'];
                }
            }
        }

        return [
            'earn_from_order' => $earnFromOrder ?? [],
            'shipping_earn' => $shippingEarn ?? [],
            'deliveryman_incentive' => $deliveryman_incentive ?? [],
            'commission' => $commission ?? [],
            'discount_given' => $discountGiven ?? [],
            'total_tax' => $totalTax ?? [],
            'refund_given' => $refundGiven ?? [],
            'admin_bearer_free_shipping' => $adminBearerFreeShipping ?? [],
        ];
    }

    public function earning_common_query($request, $query){
        $from         = $request['from'];
        $to           = $request['to'];
        $date_type    = $request['date_type'] ?? 'this_year';

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

    public function earning_same_month($type, $start_date, $end_date, $month_date, $number, $default_inc){
        $year_month = date('Y-m', strtotime($start_date));
        $month = substr(date("F", strtotime("$year_month")), 0, 3);

        //earn from order
        $earn_from_orders = Order::where(['order_status'=>'delivered', 'seller_is'=>$type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('(sum(order_amount) - sum(shipping_cost) + sum(CASE WHEN is_shipping_free=1 THEN extra_discount ELSE 0 END)) as earn_from_order, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $earn_from_order[$inc] = 0;
            foreach ($earn_from_orders as $match) {
                if ($match['day'] == $inc) {
                    $earn_from_order[$inc] = $match['earn_from_order'];
                }
            }
        }

        //shipping earn
        $shipping_earns = Order::whereHas('deliveryMan', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->selectRaw('sum(shipping_cost) as shipping_earn, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->where(['order_type'=>'default_type', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $shipping_earn[$inc] = 0;
            foreach ($shipping_earns as $match) {
                if ($match['day'] == $inc) {
                    $shipping_earn[$inc] = $match['shipping_earn'];
                }
            }
        }

        //deliveryman incentive
        $deliveryman_incentives = Order::whereHas('deliveryMan', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->selectRaw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility='.($type=='seller'?'"sellerwise_shipping" AND seller_is="seller"':'"inhouse_shipping" OR seller_is="admin"').' THEN deliveryman_charge ELSE 0 END) as deliveryman_incentive, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->where(['order_type'=>'default_type', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $deliveryman_incentive[$inc] = 0;
            foreach ($deliveryman_incentives as $match) {
                if ($match['day'] == $inc) {
                    $deliveryman_incentive[$inc] = $match['deliveryman_incentive'];
                }
            }
        }

        //commission
        $commissions = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(admin_commission) as commission, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $commission[$inc] = 0;
            foreach ($commissions as $match) {
                if ($match['day'] == $inc) {
                    $commission[$inc] = $match['commission'];
                }
            }
        }

        //admin bearer free shipping
        $admin_bearer_free_shippings = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END) as free_shipping_admin_bearer, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $admin_bearer_free_shipping[$inc] = 0;
            foreach ($admin_bearer_free_shippings as $match) {
                if ($match['day'] == $inc) {
                    $admin_bearer_free_shipping[$inc] = $match['free_shipping_admin_bearer'];
                }
            }
        }

        //discount_given
        $discounts_given = Order::where(['order_status'=>'delivered'])
            ->when($type=='admin', function ($query){
                $query->where('coupon_discount_bearer', 'inhouse');
            })
            ->when($type=='seller', function ($query){
                $query->where('coupon_discount_bearer', 'seller');
            })
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('(sum(CASE WHEN discount_type="coupon_discount" THEN discount_amount ELSE 0 END) + sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as discount_amount, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $discount_given[$inc] = 0;
            foreach ($discounts_given as $match) {
                if ($match['day'] == $inc) {
                    $discount_given[$inc] = $match['discount_amount'];
                }
            }
        }

        //vat/tax
        $taxes = OrderTransaction::where(['seller_is'=> $type, 'status'=>'disburse'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(tax) as total_tax, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $total_tax[$inc] = 0;
            foreach ($taxes as $match) {
                if ($match['day'] == $inc) {
                    $total_tax[$inc] = $match['total_tax'];
                }
            }
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status'=>'paid', 'paid_by'=> $type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(amount) as refund_amount, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $refund_given[$inc] = 0;
            foreach ($refunds as $match) {
                if ($match['day'] == $inc) {
                    $refund_given[$inc] = $match['refund_amount'];
                }
            }
        }

        return [
            'earn_from_order' => $earn_from_order,
            'admin_bearer_free_shipping' => $admin_bearer_free_shipping,
            'shipping_earn' => $shipping_earn,
            'deliveryman_incentive' => $deliveryman_incentive,
            'commission' => $commission,
            'discount_given' => $discount_given,
            'total_tax' => $total_tax,
            'refund_given' => $refund_given,
        ];
    }

    public function earning_this_week($type){
        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        //earn from order
        $earn_from_orders = Order::where(['order_status'=>'delivered', 'seller_is'=>$type])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('(sum(order_amount) - sum(shipping_cost) + sum(CASE WHEN is_shipping_free=1 THEN extra_discount ELSE 0 END)) as earn_from_order'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $earn_from_order[$day_name[$inc]] = 0;
            foreach ($earn_from_orders as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $earn_from_order[$day_name[$inc]] = $match['earn_from_order'];
                }
            }
        }

        //shipping earn
        $shipping_earns = Order::whereHas('deliveryMan', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->select(
                DB::raw('sum(shipping_cost) as shipping_earn'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->where(['order_type'=>'default_type', 'order_status'=>'delivered'])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $shipping_earn[$day_name[$inc]] = 0;
            foreach ($shipping_earns as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $shipping_earn[$day_name[$inc]] = $match['shipping_earn'];
                }
            }
        }

        //deliveryman incentive
        $deliveryman_incentives = Order::whereHas('deliveryMan', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                    ->when($type=='seller', function ($query){
                        $query->where('seller_id', '!=', '0');
                    });
            })
            ->select(
                DB::raw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility='.($type=='seller'?'"sellerwise_shipping" AND seller_is="seller"':'"inhouse_shipping" OR seller_is="admin"').' THEN deliveryman_charge ELSE 0 END) as deliveryman_incentive'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->where(['order_type'=>'default_type', 'order_status'=>'delivered'])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $deliveryman_incentive[$day_name[$inc]] = 0;
            foreach ($deliveryman_incentives as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $deliveryman_incentive[$day_name[$inc]] = $match['deliveryman_incentive'];
                }
            }
        }

        //commission
        $commissions = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('sum(admin_commission) as commission'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $commission[$day_name[$inc]] = 0;
            foreach ($commissions as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $commission[$day_name[$inc]] = $match['commission'];
                }
            }
        }

        //admin bearer free shipping
        $admin_bearer_free_shippings = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END) as free_shipping_admin_bearer'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $admin_bearer_free_shipping[$day_name[$inc]] = 0;
            foreach ($admin_bearer_free_shippings as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $admin_bearer_free_shipping[$day_name[$inc]] = $match['free_shipping_admin_bearer'];
                }
            }
        }

        //discount_given
        $discounts_given = Order::where(['order_status'=>'delivered'])
            ->when($type=='admin', function ($query){
                $query->where('coupon_discount_bearer', 'inhouse');
            })
            ->when($type=='seller', function ($query){
                $query->where('coupon_discount_bearer', 'seller');
            })
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('(sum(CASE WHEN discount_type="coupon_discount" THEN discount_amount ELSE 0 END) + sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as discount_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $discount_given[$day_name[$inc]] = 0;
            foreach ($discounts_given as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $discount_given[$day_name[$inc]] = $match['discount_amount'];
                }
            }
        }

        //vat/tax
        $taxes = OrderTransaction::where(['status'=>'disburse', 'seller_is'=> $type])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('sum(tax) as total_tax'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $total_tax[$day_name[$inc]] = 0;
            foreach ($taxes as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $total_tax[$day_name[$inc]] = $match['total_tax'];
                }
            }
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status'=>'paid', 'paid_by'=> $type])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('sum(amount) as refund_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $refund_given[$day_name[$inc]] = 0;
            foreach ($refunds as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $refund_given[$day_name[$inc]] = $match['refund_amount'];
                }
            }
        }

        return [
            'earn_from_order' => $earn_from_order,
            'shipping_earn' => $shipping_earn,
            'deliveryman_incentive' => $deliveryman_incentive,
            'commission' => $commission,
            'discount_given' => $discount_given,
            'total_tax' => $total_tax,
            'refund_given' => $refund_given,
            'admin_bearer_free_shipping' => $admin_bearer_free_shipping,
        ];
    }

    public function earning_same_year($type, $start_date, $end_date, $from_year, $number, $default_inc){

        //earn from order
        $earn_from_orders = Order::where(['order_status'=>'delivered', 'seller_is'=>$type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('(sum(order_amount) - sum(shipping_cost) + sum(CASE WHEN is_shipping_free=1 THEN extra_discount ELSE 0 END)) as earn_from_order, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $earn_from_order[$month] = 0;
            foreach ($earn_from_orders as $match) {
                if ($match['month'] == $inc) {
                    $earn_from_order[$month] = $match['earn_from_order'];
                }
            }
        }

        //shipping earn
        $shipping_earns = Order::whereHas('deliveryMan', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->selectRaw('sum(shipping_cost) as shipping_earn, YEAR(updated_at) year, MONTH(updated_at) month')
            ->where(['order_type'=>'default_type', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $shipping_earn[$month] = 0;
            foreach ($shipping_earns as $match) {
                if ($match['month'] == $inc) {
                    $shipping_earn[$month] = $match['shipping_earn'];
                }
            }
        }

        //deliveryman incentive
        $deliveryman_incentives = Order::whereHas('deliveryMan', function ($query) use($type){
            $query->when($type=='admin', function ($query){
                $query->where('seller_id', '0');
            })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
        })
            ->selectRaw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility='.($type=='seller'?'"sellerwise_shipping" AND seller_is="seller"':'"inhouse_shipping" OR seller_is="admin"').' THEN deliveryman_charge ELSE 0 END) as deliveryman_incentive, YEAR(updated_at) year, MONTH(updated_at) month')
            ->where(['order_type'=>'default_type', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $deliveryman_incentive[$month] = 0;
            foreach ($deliveryman_incentives as $match) {
                if ($match['month'] == $inc) {
                    $deliveryman_incentive[$month] = $match['deliveryman_incentive'];
                }
            }
        }

        //commission
        $commissions = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(admin_commission) as commission, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $commission[$month] = 0;
            foreach ($commissions as $match) {
                if ($match['month'] == $inc) {
                    $commission[$month] = $match['commission'];
                }
            }
        }

        //admin bearer free shipping
        $admin_bearer_free_shippings = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END) as free_shipping_admin_bearer, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $admin_bearer_free_shipping[$month] = 0;
            foreach ($admin_bearer_free_shippings as $match) {
                if ($match['month'] == $inc) {
                    $admin_bearer_free_shipping[$month] = $match['free_shipping_admin_bearer'];
                }
            }
        }

        //discount_given
        $discounts_given = Order::where(['order_status'=>'delivered'])
            ->when($type=='admin', function ($query){
                $query->selectRaw('(sum(CASE WHEN discount_type="coupon_discount" AND coupon_discount_bearer="inhouse" THEN discount_amount ELSE 0 END) + sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as discount_amount, YEAR(updated_at) year, MONTH(updated_at) month');
            })
            ->when($type=='seller', function ($query){
                $query->selectRaw('(sum(CASE WHEN discount_type="coupon_discount" AND coupon_discount_bearer="seller" THEN discount_amount ELSE 0 END) + sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="seller" THEN extra_discount ELSE 0 END)) as discount_amount, YEAR(updated_at) year, MONTH(updated_at) month');
            })
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $discount_given[$month] = 0;
            foreach ($discounts_given as $match) {
                if ($match['month'] == $inc) {
                    $discount_given[$month] = $match['discount_amount'];
                }
            }
        }

        //vat/tax
        $taxes = OrderTransaction::where(['status'=>'disburse', 'seller_is'=> $type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(tax) as total_tax, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $total_tax[$month] = 0;
            foreach ($taxes as $match) {
                if ($match['month'] == $inc) {
                    $total_tax[$month] = $match['total_tax'];
                }
            }
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status'=>'paid', 'paid_by'=> $type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(amount) as refund_amount, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $refund_given[$month] = 0;
            foreach ($refunds as $match) {
                if ($match['month'] == $inc) {
                    $refund_given[$month] = $match['refund_amount'];
                }
            }
        }

        $data = array(
            'earn_from_order' => $earn_from_order,
            'shipping_earn' => $shipping_earn,
            'deliveryman_incentive' => $deliveryman_incentive,
            'commission' => $commission,
            'discount_given' => $discount_given,
            'total_tax' => $total_tax,
            'refund_given' => $refund_given,
            'admin_bearer_free_shipping' => $admin_bearer_free_shipping,
        );
        return $data;
    }

    public function earning_different_year($type, $start_date, $end_date, $from_year, $to_year){

        //earn from order for different year
        $earn_from_orders = Order::where(['order_status'=>'delivered', 'seller_is'=>$type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('(sum(order_amount) - sum(shipping_cost) + sum(CASE WHEN is_shipping_free=1 THEN extra_discount ELSE 0 END)) as earn_from_order, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))->latest('updated_at')->get();


        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $earn_from_order[$inc] = 0;
            foreach ($earn_from_orders as $match) {
                if ($match['year'] == $inc) {
                    $earn_from_order[$inc] = $match['earn_from_order'];
                }
            }
        }

        //shipping earn for custom same year
        $shipping_earns = Order::whereHas('deliveryMan', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->where(['order_type'=>'default_type', 'order_status'=>'delivered', 'is_shipping_free'=>'0'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(shipping_cost) as shipping_earn, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $shipping_earn[$inc] = 0;
            foreach ($shipping_earns as $match) {
                if ($match['year'] == $inc) {
                    $shipping_earn[$inc] = $match['shipping_earn'];
                }
            }
        }

        //deliveryman incentive
        $deliveryman_incentives = Order::whereHas('deliveryMan', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->where(['order_type'=>'default_type', 'order_status'=>'delivered', 'is_shipping_free'=>'0'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility='.($type=='seller'?'"sellerwise_shipping" AND seller_is="seller"':'"inhouse_shipping" OR seller_is="admin"').' THEN deliveryman_charge ELSE 0 END) as deliveryman_incentive, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $deliveryman_incentive[$inc] = 0;
            foreach ($deliveryman_incentives as $match) {
                if ($match['year'] == $inc) {
                    $deliveryman_incentive[$inc] = $match['deliveryman_incentive'];
                }
            }
        }

        //commission
        $commissions = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(admin_commission) as commission, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $commission[$inc] = 0;
            foreach ($commissions as $match) {
                if ($match['year'] == $inc) {
                    $commission[$inc] = $match['commission'];
                }
            }
        }

        //admin bearer free shipping
        $admin_bearer_free_shippings = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END) as free_shipping_admin_bearer, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $admin_bearer_free_shipping[$inc] = 0;
            foreach ($admin_bearer_free_shippings as $match) {
                if ($match['year'] == $inc) {
                    $admin_bearer_free_shipping[$inc] = $match['free_shipping_admin_bearer'];
                }
            }
        }

        //discount_given
        $discounts_given = Order::where(['discount_type'=>'coupon_discount','order_status'=>'delivered'])
            ->when($type=='admin', function ($query){
                $query->where('coupon_discount_bearer', 'inhouse');
            })
            ->when($type=='seller', function ($query){
                $query->where('coupon_discount_bearer', 'seller');
            })
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('(sum(CASE WHEN discount_type="coupon_discount" THEN discount_amount ELSE 0 END) + sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as discount_amount, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $discount_given[$inc] = 0;
            foreach ($discounts_given as $match) {
                if ($match['year'] == $inc) {
                    $discount_given[$inc] = $match['discount_amount'];
                }
            }
        }

        //vat/tax
        $taxes = OrderTransaction::where(['status'=>'disburse', 'seller_is'=> $type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(tax) as total_tax, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $total_tax[$inc] = 0;
            foreach ($taxes as $match) {
                if ($match['year'] == $inc) {
                    $total_tax[$inc] = $match['total_tax'];
                }
            }
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status'=>'paid', 'paid_by'=> $type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(amount) as refund_amount, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $refund_given[$inc] = 0;
            foreach ($refunds as $match) {
                if ($match['year'] == $inc) {
                    $refund_given[$inc] = $match['refund_amount'];
                }
            }
        }

        $data = array(
            'earn_from_order' => $earn_from_order,
            'shipping_earn' => $shipping_earn,
            'deliveryman_incentive' => $deliveryman_incentive,
            'commission' => $commission,
            'discount_given' => $discount_given,
            'total_tax' => $total_tax,
            'refund_given' => $refund_given,
            'admin_bearer_free_shipping' => $admin_bearer_free_shipping,
        );
        return $data;
    }

    public function admin_earning_duration_download_pdf(Request $request){
        $earning_data = $request->except('_token');
        $company_phone =BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email =BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name =BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo =BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $mpdf_view = View::make('admin-views.report.admin-earning-duration-wise-pdf', compact('earning_data', 'company_name','company_email','company_phone','company_web_logo'));
        Helpers::gen_mpdf($mpdf_view, 'admin_earning_', $earning_data['duration']);
    }

    public function vendorEarning(Request $request)
    {
        $from         = $request['from'];
        $to           = $request['to'];
        $date_type    = $request['date_type'] ?? 'this_year';

        $total_seller_query = Seller::where(['status' => 'approved']);
        $total_seller = self::earning_common_query($request, $total_seller_query)->count();

        $all_product_query = Product::where(['added_by' => 'seller']);
        $all_product = self::earning_common_query($request, $all_product_query)->count();

        $rejected_product_query = Product::where(['added_by' => 'seller', 'request_status' => 2]);
        $rejected_product = self::earning_common_query($request, $rejected_product_query)->count();

        $pending_product_query = Product::where(['added_by' => 'seller', 'request_status' => 0]);
        $pending_product = self::earning_common_query($request, $pending_product_query)->count();

        $active_product_query = Product::where(['added_by' => 'seller', 'status' => 1, 'request_status' => 1]);
        $active_product = self::earning_common_query($request, $active_product_query)->count();

        $data = [
            'total_seller' => $total_seller,
            'all_product' => $all_product,
            'rejected_product' => $rejected_product,
            'pending_product' => $pending_product,
            'active_product' => $active_product,
        ];

        $payments = SellerWallet::selectRaw('sum(total_earning) as total_earning, sum(pending_withdraw) as pending_withdraw, sum(withdrawn) as withdrawn')->first();
        $withdrawable_balance = $payments->total_earning - $payments->pending_withdraw;

        $payment_data = [
            'wallet_amount' => $payments->total_earning,
            'withdrawable_balance' => $withdrawable_balance,
            'pending_withdraw' => $payments->pending_withdraw,
            'already_withdrawn' => $payments->withdrawn,
        ];

        $filter_data_chart = self::earning_common_filter('seller', $date_type, $from, $to);
        $seller_earn_chart = $filter_data_chart['earn_from_order'];
        $shipping_earn_chart = $filter_data_chart['shipping_earn'];
        $deliveryman_incentive = $filter_data_chart['deliveryman_incentive'];
        $commission_given_chart = $filter_data_chart['commission'];
        $discount_given_chart = $filter_data_chart['discount_given'];
        $total_tax_chart = $filter_data_chart['total_tax'];
        $refund_given_chart = $filter_data_chart['refund_given'];

        foreach($seller_earn_chart as $key=>$earning) {
            $chart_earning_statistics[$key] = ($earning+$shipping_earn_chart[$key])-($refund_given_chart[$key]+$commission_given_chart[$key]-$deliveryman_incentive[$key]);
        }

        $filter_data_table = self::seller_earning_common_filter_table($date_type, $from, $to);
        $seller_earn_table = $filter_data_table['seller_earn_table'];
        $shipping_earn_table = $filter_data_table['shipping_earn_table'];
        $deliveryman_incentive_table = $filter_data_table['deliveryman_incentive'];
        $commission_given_table = $filter_data_table['commission_given_table'];
        $total_refund_table = $filter_data_table['total_refund_table'];
        $discount_given_table = $filter_data_table['discount_given_table'];
        $discount_given_bearer_admin_table = $filter_data_table['discount_given_bearer_admin_table'];
        $total_tax_table = $filter_data_table['total_tax_table'];

        $table_earning = array(
            'seller_earn_table' => $seller_earn_table,
            'commission_given_table' => $commission_given_table,
            'shipping_earn_table' => $shipping_earn_table,
            'deliveryman_incentive_table' => $deliveryman_incentive_table,
            'discount_given_table' => $discount_given_table,
            'discount_given_bearer_admin_table' => $discount_given_bearer_admin_table,
            'total_tax_table' => $total_tax_table,
            'total_refund_table' => $total_refund_table,
        );
        $total_seller_earning = 0;
        $total_commission = 0;
        $total_shipping_earn = 0;
        $total_deliveryman_incentive = 0;
        $total_discount_given = 0;
        $total_refund_given = 0;
        $total_tax = 0;
        foreach($seller_earn_table as $key=>$earning) {
            $shipping_earn = isset($shipping_earn_table[$key]['amount']) ? $shipping_earn_table[$key]['amount'] : 0;
            $deliveryman_incentive_given = isset($deliveryman_incentive_table[$key]['amount']) ? $deliveryman_incentive_table[$key]['amount'] : 0;
            $commission_given = isset($commission_given_table[$key]['amount']) ? $commission_given_table[$key]['amount'] : 0;
            $discount_given = isset($discount_given_table[$key]['amount']) ? $discount_given_table[$key]['amount'] : 0;
            $tax = isset($total_tax_table[$key]['amount']) ? $total_tax_table[$key]['amount'] : 0;
            $refund_given = isset($total_refund_table[$key]['amount']) ? $total_refund_table[$key]['amount'] : 0;

            $total_seller_earning += $earning['amount'];
            $total_commission += $commission_given;
            $total_shipping_earn += $shipping_earn;
            $total_deliveryman_incentive += $deliveryman_incentive_given;
            $total_discount_given += $discount_given;
            $total_tax += $tax;
            $total_refund_given += $refund_given;
        }
        $total_earning = ($total_seller_earning+$total_shipping_earn) - ($total_commission+$total_refund_given+$total_deliveryman_incentive);

        return view('admin-views.report.seller-earning', compact('data', 'payment_data', 'table_earning', 'total_earning', 'chart_earning_statistics', 'from', 'to', 'date_type'));
    }
    public function exportVendorEarning(Request $request):BinaryFileResponse
    {
        $from         = $request['from'];
        $to           = $request['to'];
        $dateType    = $request['date_type'] ?? 'this_year';
        $filterData = self::seller_earning_common_filter_table($dateType, $from, $to);
        $data = [
            'from'=>$from,
            'to'=>$to,
            'dateType' => $dateType,
            'vendorEarnTable' => $filterData['seller_earn_table'],
            'shippingEarnTable' => $filterData['shipping_earn_table'],
            'deliverymanIncentiveTable' => $filterData['deliveryman_incentive'],
            'commissionGivenTable' => $filterData['commission_given_table'],
            'totalRefundTable' => $filterData['total_refund_table'],
            'discountGivenTable' => $filterData['discount_given_table'],
            'discountGivenBearerAdminTable' => $filterData['discount_given_bearer_admin_table'],
            'totalTaxTable' => $filterData['total_tax_table'],
        ];
        return Excel::download(new VendorEarningReportExport($data), Report::VENDOR_EARNING_REPORT);
    }
    public function seller_earning_common_filter_table($date_type, $from, $to)
    {
        if ($date_type == 'this_year') {
            $start_date = date('Y-01-01');
            $end_date = date('Y-12-31');
            return self::seller_earning_query_table($start_date, $end_date);
        } elseif ($date_type == 'this_month') {
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            return self::seller_earning_query_table($current_month_start, $current_month_end);
        } elseif ($date_type == 'this_week') {
            return self::seller_earning_query_table(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        } elseif ($date_type == 'today') {
            return self::seller_earning_query_table(Carbon::now()->startOfDay(), Carbon::now()->endOfDay());
        } elseif ($date_type == 'custom_date' && !empty($from) && !empty($to)) {
            $start_date_custom = Carbon::parse($from)->format('Y-m-d 00:00:00');
            $end_date_custom = Carbon::parse($to)->format('Y-m-d 23:59:59');
            return self::seller_earning_query_table($start_date_custom, $end_date_custom);
        }
    }

    /**
    *   seller earning query for table
     */
    public function seller_earning_query_table($start_date, $end_date){
        //seller earn and admin commission
        $seller_earnings_commission = Order::where(['order_status'=>'delivered', 'seller_is'=>'seller'])
            ->whereBetween('updated_at', [$start_date, $end_date])
            ->selectRaw('seller_id, (sum(order_amount) - sum(shipping_cost) + sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as earn_from_order, sum(admin_commission) as admin_commission, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')->latest('updated_at')->get();

        $seller_earn_table = array();
        $commission_given_table = array();
        foreach ($seller_earnings_commission as $data) {
            $seller = Seller::find($data->seller_id);
            $seller_earn_table[$data->seller_id] = array(
                'seller_id'=> $data->seller_id,
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->earn_from_order
            );

            $commission_given_table[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->admin_commission
            );
        }

        //discount_given_bearer_admin
        $discount_given_bearer_admin = Order::where(['coupon_discount_bearer'=>'inhouse', 'discount_type'=>'coupon_discount','order_status'=>'delivered'])
            ->whereBetween('updated_at', [$start_date, $end_date])
            ->selectRaw('sum(discount_amount) as discount_amount, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')
            ->latest('updated_at')->get();

        $discount_given_bearer_admin_table = array();
        foreach ($discount_given_bearer_admin as $data) {
            $seller = Seller::find($data->seller_id);
            $discount_given_bearer_admin_table[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->discount_amount
            );
        }

        //shipping earn
        $shipping_earns = Order::whereHas('deliveryMan', function ($query){
                $query->where('seller_id', '!=', '0');
            })
            ->where([
                'order_type'=>'default_type',
                'order_status'=>'delivered',
                'seller_is'=>'seller',
            ])
            ->whereBetween('updated_at', [$start_date, $end_date])
            ->selectRaw('(sum(CASE WHEN is_shipping_free=0 THEN shipping_cost ELSE 0 END) + sum(CASE WHEN is_shipping_free=1 THEN extra_discount ELSE 0 END)) as shipping_earn, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')
            ->latest('updated_at')->get();

        $shipping_earn_table = array();
        foreach ($shipping_earns as $data) {
            $seller = Seller::find($data->seller_id);
            $shipping_earn_table[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->shipping_earn
            );
        }

        //deliveryman incentive
        $deliveryman_incentives = Order::whereHas('deliveryMan', function ($query){
                $query->where('seller_id', '!=', '0');
            })
            ->where([
                'order_type'=>'default_type',
                'order_status'=>'delivered',
                'seller_is'=>'seller',
            ])
            ->whereBetween('updated_at', [$start_date, $end_date])
            ->selectRaw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility="sellerwise_shipping" THEN deliveryman_charge ELSE 0 END) as deliveryman_incentive, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')
            ->latest('updated_at')->get();

        $deliveryman_incentive = [];
        foreach ($deliveryman_incentives as $data) {
            $seller = Seller::find($data->seller_id);
            $deliveryman_incentive[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->deliveryman_incentive
            );
        }

        //discount_given
        $discounts_given = Order::where(['order_status'=>'delivered'])
            ->whereBetween('updated_at', [$start_date, $end_date])
            ->selectRaw('(sum(CASE WHEN coupon_discount_bearer="seller" AND discount_type="coupon_discount" THEN discount_amount ELSE 0 END) + sum(CASE WHEN is_shipping_free=1 AND free_delivery_bearer="seller" THEN extra_discount ELSE 0 END)) as discount_amount, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')
            ->latest('updated_at')->get();

        $discount_given_table = array();
        foreach ($discounts_given as $data) {
            $seller = Seller::find($data->seller_id);
            $discount_given_table[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->discount_amount
            );
        }

        //vat/tax
        $taxes = OrderTransaction::where(['seller_is'=>'seller', 'status'=>'disburse'])
            ->whereBetween('updated_at', [$start_date, $end_date])
            ->selectRaw('sum(tax) as total_tax, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')
            ->latest('updated_at')->get();

        $total_tax_table = array();
        foreach ($taxes as $data) {
            $seller = Seller::find($data->seller_id);
            $total_tax_table[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->total_tax
            );
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status'=>'paid','paid_by'=>'seller'])
            ->whereBetween('updated_at', [$start_date, $end_date])
            ->selectRaw('sum(amount) as refund_amount, payer_id, YEAR(updated_at) year')
            ->groupBy('payer_id')
            ->latest('updated_at')->get();

        $total_refund_table = array();
        foreach ($refunds as $data) {
            $seller = Seller::find($data->payer_id);
            $total_refund_table[$data->payer_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->refund_amount
            );
        }

        foreach($total_refund_table as $key=>$data){
            if(!array_key_exists($key, $seller_earn_table)){
                $seller_earn_table[$key] = array(
                    'name' => $data['name'],
                    'amount' => 0,
                );
            }
        }

        return [
            'seller_earn_table' => $seller_earn_table,
            'commission_given_table' => $commission_given_table,
            'shipping_earn_table' => $shipping_earn_table,
            'deliveryman_incentive' => $deliveryman_incentive,
            'discount_given_table' => $discount_given_table,
            'discount_given_bearer_admin_table' => $discount_given_bearer_admin_table,
            'total_tax_table' => $total_tax_table,
            'total_refund_table' => $total_refund_table,
        ];
    }

    public function set_date(Request $request)
    {
        $from = $request['from'];
        $to = $request['to'];

        session()->put('from_date', $from);
        session()->put('to_date', $to);

        $previousUrl = strtok(url()->previous(), '?');
        return redirect()->to($previousUrl . '?' . http_build_query(['from_date' => $request['from'], 'to_date' => $request['to']]))->with(['from' => $from, 'to' => $to]);
    }
}
