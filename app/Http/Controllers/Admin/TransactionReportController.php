<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Report;
use App\Exports\ExpenseTransactionReportExport;
use App\Exports\OrderTransactionReportExport;
use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shop;
use App\User;
use App\Utils\BackEndHelper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransactionReportController extends Controller
{
    public function __construct(
        private readonly VendorRepositoryInterface $vendorRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
    )
    {
    }
    public function order_transaction_list(Request $request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';
        $payment_status = $request['payment_status'] ?? 'all';

        $transactions = self::order_transaction_table_data_filter($request);

        $query_param = ['search' => $search, 'status' => $status, 'customer_id' => $customer_id, 'date_type' => $date_type, 'from' => $from, 'to' => $to];
        $transactions = $transactions->latest('updated_at')->paginate(Helpers::pagination_limit())->appends($query_param);
        $order_transaction_chart = self::order_transaction_chart_filter($request);

        $customers = User::whereNotIn('id', [0])->get();
        $sellers = Seller::where(['status' => 'approved'])->get();

        $in_house_orders_query = Order::where(['seller_is' => 'admin']);
        $in_house_orders = self::order_transaction_count_query($in_house_orders_query, $request)->count();

        $seller_orders_query = Order::where(['seller_is' => 'seller']);
        $seller_orders = self::order_transaction_count_query($seller_orders_query, $request)->count();
        $total_orders = $in_house_orders + $seller_orders;


        $total_in_house_product_query = Product::where(['added_by' => 'admin'])
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['user_id' => 1]);
                });
            });
        $total_in_house_products = self::date_wise_common_filter($total_in_house_product_query, $date_type, $from, $to)->count();

        $total_seller_product_query = Product::where(['added_by' => 'seller'])
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['user_id' => $seller_id]);
                });
            });
        $total_seller_products = self::date_wise_common_filter($total_seller_product_query, $date_type, $from, $to)->count();

        $total_stores_query = Shop::when($seller_id != 'all', function ($query) use ($seller_id) {
            $query->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                $q->where(['seller_id' => $seller_id]);
            });
        });
        $total_stores = self::date_wise_common_filter($total_stores_query, $date_type, $from, $to)->count();

        $order_data = [
            'total_orders' => $total_orders,
            'in_house_orders' => $in_house_orders,
            'seller_orders' => $seller_orders,
            'total_in_house_products' => $total_in_house_products,
            'total_seller_products' => $total_seller_products,
            'total_stores' => $total_stores,
        ];

        $digital_payment_query = Order::whereNotIn('payment_method', ['cash', 'cash_on_delivery', 'pay_by_wallet', 'offline_payment']);
        $digital_payment = self::order_transaction_piechart_query($request, $digital_payment_query)->sum('order_amount');

        $cash_payment_query = Order::whereIn('payment_method', ['cash', 'cash_on_delivery']);
        $cash_payment = self::order_transaction_piechart_query($request, $cash_payment_query)->sum('order_amount');

        $wallet_payment_query = Order::where(['payment_method' => 'pay_by_wallet']);
        $wallet_payment = self::order_transaction_piechart_query($request, $wallet_payment_query)->sum('order_amount');

        $offline_payment_query = Order::where(['payment_method' => 'offline_payment']);
        $offline_payment = self::order_transaction_piechart_query($request, $offline_payment_query)->sum('order_amount');

        $total_payment = $cash_payment + $wallet_payment + $digital_payment +$offline_payment;

        $payment_data = [
            'digital_payment' => $digital_payment,
            'cash_payment' => $cash_payment,
            'wallet_payment' => $wallet_payment,
            'offline_payment' => $offline_payment,
            'total_payment' => $total_payment,
        ];

        return view('admin-views.transaction.order-list', compact('customers', 'sellers', 'transactions', 'search', 'status',
            'from', 'to', 'customer_id', 'seller_id', 'payment_status', 'order_data', 'date_type', 'payment_data', 'order_transaction_chart'));
    }

    /**
     * Order transaction report export by excel
     */
    public function orderTransactionExportExcel(Request $request):BinaryFileResponse
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $dateType = $request['date_type'] ?? 'this_year';
        $vendor = $request['seller_id']!='all' ? $this->vendorRepo->getFirstWhere(params:['id' => $request['seller_id']]):'all';
        $customer =  isset($request['customer_id']) && $request['customer_id'] !='all' ? $this->customerRepo->getFirstWhere(params:['id' => $request['customer_id']]):'all';
        $transactions = self::order_transaction_table_data_filter($request)->latest('updated_at')->get();
        $transactions->map(function ($transaction) {
            $transaction['adminCouponDiscount'] = ($transaction->order->coupon_discount_bearer == 'inhouse' && $transaction->order->discount_type == 'coupon_discount') ? $transaction->order->discount_amount : 0;
            $transaction['adminShippingDiscount'] = ($transaction->order->free_delivery_bearer == 'admin' && $transaction->order->is_shipping_free) ? $transaction->order->extra_discount : 0;
            $transaction['vendorCouponDiscount'] = ($transaction->order->coupon_discount_bearer == 'seller' && $transaction->order->discount_type == 'coupon_discount') ? $transaction->order->discount_amount : 0;
            $transaction['vendorShippingDiscount'] = ($transaction->order->free_delivery_bearer=='seller' && $transaction->order->is_shipping_free) ? $transaction->order->extra_discount : 0;
            $adminNetIncome = 0;
            if ($transaction['seller_is'] == 'admin') {
                $adminNetIncome += $transaction['order_amount'] + $transaction['tax'];
            }
            if (isset($transaction->order->deliveryMan) && $transaction->order->deliveryMan->seller_id == 0) {
                $adminNetIncome += $transaction['delivery_charge'];
            }
            $adminNetIncome += $transaction['admin_commission'];

            if($transaction->order->delivery_type == 'self_delivery' && ($transaction->order->shipping_responsibility == 'inhouse_shipping' || $transaction->order->seller_is == 'admin')){
                $adminNetIncome -= $transaction->order->deliveryman_charge;
            }
            if ($transaction['seller_is'] == 'seller') {
                if ($transaction->order->shipping_responsibility == 'inhouse_shipping') {
                    $adminNetIncome -= $transaction->order->coupon_discount_bearer == 'inhouse' ? $transaction['adminCouponDiscount'] : 0;
                    $adminNetIncome += ($transaction->order->coupon_discount_bearer == 'seller' && isset($transaction->order->coupon) && $transaction->order->coupon->coupon_type == 'free_delivery') ? $transaction['vendorCouponDiscount'] : 0;
                    $adminNetIncome += ($transaction->order->free_delivery_bearer == 'seller') ? $transaction['vendorShippingDiscount'] : 0;

                } elseif ($transaction->order->shipping_responsibility == 'sellerwise_shipping') {
                    $adminNetIncome -= $transaction->order->coupon_discount_bearer == 'inhouse' ? $transaction['adminCouponDiscount'] : 0;
                    $adminNetIncome -= $transaction->order->free_delivery_bearer == 'admin' ? $transaction['adminShippingDiscount'] : 0;
                }
            }
            $transaction['adminNetIncome'] = $adminNetIncome;
            $vendorNetIncome = 0;
            if (isset($transaction->order->deliveryMan) && $transaction->order->deliveryMan->seller_id != '0') {
                $vendorNetIncome += $transaction['delivery_charge'];
            }

            if ($transaction['seller_is'] == 'seller') {
                $vendorNetIncome += $transaction['order_amount'] + $transaction['tax'] - $transaction['admin_commission'];
            }

            if($transaction->order->delivery_type == 'self_delivery' && $transaction->order->shipping_responsibility == 'sellerwise_shipping' && $transaction->order->seller_is == 'seller'){
                $vendorNetIncome -= $transaction->order->deliveryman_charge;
            }
            if ($transaction['seller_is'] == 'seller') {
                if ($transaction->order->shipping_responsibility == 'inhouse_shipping') {
                    $vendorNetIncome += $transaction->order->coupon_discount_bearer == 'inhouse' ? $transaction['adminCouponDiscount'] : 0;
                    $vendorNetIncome -= ($transaction->order->coupon_discount_bearer == 'seller' && isset($transaction->order->coupon) && $transaction->order->coupon->coupon_type == 'free_delivery') ? $transaction['adminCouponDiscount'] : 0;
                    $vendorNetIncome -= ($transaction->order->free_delivery_bearer == 'seller') ? $transaction['adminShippingDiscount'] : 0;

                } elseif ($transaction->order->shipping_responsibility == 'sellerwise_shipping') {
                    $vendorNetIncome += $transaction->order->coupon_discount_bearer == 'inhouse' ? $transaction['adminCouponDiscount'] : 0;
                    $vendorNetIncome += $transaction->order->free_delivery_bearer == 'admin' ? $transaction['adminShippingDiscount'] : 0;
                    $transaction['vendorShippingDiscount'] = 0;
                }
            }
            $transaction['vendorNetIncome'] = $vendorNetIncome;
        });
        $data = [
            'data-from' => 'admin',
            'search' => $search,
            'from' => $from,
            'to' => $to,
            'dateType' => $dateType,
            'vendor' => $vendor,
            'customer' => $customer,
            'transactions' => $transactions,
        ];
        return Excel::download(new OrderTransactionReportExport($data), Report::ORDER_TRANSACTION_REPORT_LIST);
    }

    /**
     * order transaction summary pdf
     */
    public function order_transaction_summary_pdf(Request $request)
    {

        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $from = $request['from'];
        $to = $request['to'];
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $duration = str_replace('_', ' ', $date_type);
        if ($date_type == 'custom_date') {
            $duration = 'From ' . $from . ' To ' . $to;
        }

        $seller_info = $seller_id == 'all' || $seller_id == 'inhouse' ? $seller_id : Shop::where('seller_id', $seller_id)->name;
        $customer_info = 'all';
        if ($customer_id != 'all') {
            $customer = User::select()->find($customer_id);
            $customer_info = $customer->f_name . ' ' . $customer->l_name;
        }

        $transactions = self::order_transaction_table_data_filter($request)->latest('updated_at')->get();

        $total_ordered_product_price = 0;
        $total_product_discount = 0;
        $total_coupon_discount = 0;
        $total_discounted_amount = 0;
        $total_tax = 0;
        $total_delivery_charge = 0;
        $total_order_amount = 0;
        $total_admin_discount = 0;
        $total_seller_discount = 0;
        $total_admin_commission = 0;
        $total_admin_net_income = 0;
        $total_seller_net_income = 0;
        $total_admin_shipping_discount = 0;
        $total_seller_shipping_discount = 0;
        $total_deliveryman_incentive = 0;
        foreach ($transactions as $transaction) {
            if($transaction->order) {
                $admin_coupon_discount = ($transaction->order->coupon_discount_bearer == 'inhouse' && $transaction->order->discount_type == 'coupon_discount') ? $transaction->order->discount_amount : 0;
                $admin_shipping_discount = ($transaction->order->is_shipping_free && $transaction->order->free_delivery_bearer=='admin') ? $transaction->order->extra_discount : 0;
                $total_admin_shipping_discount += $admin_shipping_discount;

                $seller_coupon_discount = ($transaction->order->coupon_discount_bearer == 'seller' && $transaction->order->discount_type == 'coupon_discount') ? $transaction->order->discount_amount : 0;
                $seller_shipping_discount = ($transaction->order->is_shipping_free && $transaction->order->free_delivery_bearer=='seller') ? $transaction->order->extra_discount : 0;
                $total_seller_shipping_discount += $seller_shipping_discount;

                $total_ordered_product_price += $transaction->orderDetails[0]?->order_details_sum_price??0;
                $total_product_discount += $transaction->orderDetails[0]?->order_details_sum_discount ??0;
                $total_coupon_discount += $transaction->order->discount_amount;
                $total_discounted_amount += ($transaction->orderDetails[0]?->order_details_sum_price??0) - ($transaction->orderDetails[0]?->order_details_sum_discount??0) - (isset($transaction->order->coupon) && $transaction->order->coupon->coupon_type != 'free_delivery'?$transaction->order->discount_amount:0);
                $total_tax += $transaction->tax;
                $total_delivery_charge += $transaction->order->shipping_cost;
                $total_order_amount += $transaction->order->order_amount;

                $total_admin_discount += $admin_coupon_discount+$admin_shipping_discount;
                $total_seller_discount += $seller_coupon_discount+$seller_shipping_discount;
                $total_admin_commission += $transaction->admin_commission;

                $total_deliveryman_incentive += $transaction->order->deliveryman_charge;

                // admin net income calculation start
                $admin_net_income = 0;
                if($transaction['seller_is'] == 'admin'){
                    $admin_net_income += $transaction['order_amount'] + $transaction['tax'];
                }
                if(isset($transaction->order->deliveryMan) && $transaction->order->deliveryMan->seller_id == '0'){
                    $admin_net_income += $transaction['delivery_charge'];
                }
                $admin_net_income += $transaction['admin_commission'];

                if($transaction->order->delivery_type == 'self_delivery' && ($transaction->order->shipping_responsibility == 'inhouse_shipping' || $transaction->order->seller_is == 'admin') && $transaction->order->delivery_man_id){
                    $admin_net_income -= $transaction->order->deliveryman_charge;
                }

                if($transaction['seller_is'] == 'seller'){
                    if($transaction->order->shipping_responsibility == 'inhouse_shipping'){
                        $admin_net_income -= $transaction->order->coupon_discount_bearer == 'inhouse' ? $admin_coupon_discount : 0;
                        $admin_net_income += ($transaction->order->coupon_discount_bearer == 'seller' && isset($transaction->order->coupon) && $transaction->order->coupon->coupon_type == 'free_delivery') ? $seller_coupon_discount:0;
                        $admin_net_income += ($transaction->order->free_delivery_bearer == 'seller') ? $seller_shipping_discount:0;

                    }elseif($transaction->order->shipping_responsibility == 'sellerwise_shipping'){
                        $admin_net_income -= $transaction->order->coupon_discount_bearer == 'inhouse' ? $admin_coupon_discount : 0;
                        $admin_net_income -= $transaction->order->free_delivery_bearer == 'admin' ? $admin_shipping_discount : 0;
                    }
                }
                $total_admin_net_income += $admin_net_income;
                // admin net income calculation end


                // seller net income calculation start
                $seller_net_income = 0;
                if(isset($transaction->order->deliveryMan) && $transaction->order->deliveryMan->seller_id != '0'){
                    $seller_net_income += $transaction['delivery_charge'];
                }

                if($transaction['seller_is'] == 'seller'){
                    $seller_net_income += $transaction['order_amount'] + $transaction['tax'] - $transaction['admin_commission'];
                }

                if($transaction->order->delivery_type == 'self_delivery' && $transaction->order->shipping_responsibility == 'sellerwise_shipping' && $transaction->order->delivery_man_id && $transaction->order->seller_is == 'seller'){
                    $seller_net_income -= $transaction->order->deliveryman_charge;
                }

                if($transaction['seller_is'] == 'seller'){
                    if($transaction->order->shipping_responsibility == 'inhouse_shipping'){
                        $seller_net_income += $transaction->order->coupon_discount_bearer == 'inhouse' ? $admin_coupon_discount : 0;
                        $seller_net_income -= ($transaction->order->coupon_discount_bearer == 'seller' && $transaction->order->coupon->coupon_type == 'free_delivery') ? $admin_coupon_discount:0;
                        $seller_net_income -= ($transaction->order->free_delivery_bearer == 'seller') ? $admin_shipping_discount:0;

                    }elseif($transaction->order->shipping_responsibility == 'sellerwise_shipping'){
                        $seller_net_income += $transaction->order->coupon_discount_bearer == 'inhouse' ? $admin_coupon_discount : 0;
                        $seller_net_income += $transaction->order->free_delivery_bearer == 'admin' ? $admin_shipping_discount : 0;
                        $seller_shipping_discount=0;
                    }
                }
                $total_seller_net_income += $seller_net_income-$seller_shipping_discount;
                // seller net income calculation end
            }
        }

        $in_house_orders_query = Order::where(['seller_is' => 'admin']);
        $in_house_orders = self::order_transaction_count_query($in_house_orders_query, $request)->count();

        $seller_orders_query = Order::where(['seller_is' => 'seller']);
        $seller_orders = self::order_transaction_count_query($seller_orders_query, $request)->count();
        $total_orders = $in_house_orders + $seller_orders;


        $total_in_house_product_query = Product::where(['added_by' => 'admin'])
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['user_id' => 1]);
                });
            });
        $total_in_house_products = self::date_wise_common_filter($total_in_house_product_query, $date_type, $from, $to)->count();

        $total_seller_product_query = Product::where(['added_by' => 'seller'])
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id]);
                });
            });
        $total_seller_products = self::date_wise_common_filter($total_seller_product_query, $date_type, $from, $to)->count();

        $total_stores_query = Shop::when($seller_id != 'all', function ($query) use ($seller_id) {
            $query->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                $q->where(['seller_id' => $seller_id]);
            });
        });
        $total_stores = self::date_wise_common_filter($total_stores_query, $date_type, $from, $to)->count();

        $data = array(
            'total_ordered_product_price' => $total_ordered_product_price,
            'total_product_discount' => $total_product_discount,
            'total_coupon_discount' => $total_coupon_discount,
            'total_discounted_amount' => $total_discounted_amount,
            'total_tax' => $total_tax,
            'total_delivery_charge' => $total_delivery_charge,
            'total_deliveryman_incentive' => $total_deliveryman_incentive,
            'total_order_amount' => $total_order_amount,
            'total_admin_discount' => $total_admin_discount,
            'total_seller_discount' => $total_seller_discount,
            'total_admin_commission' => $total_admin_commission,
            'total_admin_net_income' => $total_admin_net_income,
            'total_seller_net_income' => $total_seller_net_income,
            'total_orders' => $total_orders,
            'in_house_orders' => $in_house_orders,
            'seller_orders' => $seller_orders,
            'total_in_house_products' => $total_in_house_products,
            'total_seller_products' => $total_seller_products,
            'total_stores' => $total_stores,
        );

        $mpdf_view = View::make('admin-views.transaction.order_transaction_summary_report_pdf', compact('data', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'status', 'duration', 'seller_info', 'customer_info'));
        Helpers::gen_mpdf($mpdf_view, 'order_transaction_summary_report_', $date_type);

    }

    public function pdf_order_wise_transaction(Request $request)
    {
        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $transaction = OrderTransaction::with(['seller.shop', 'customer', 'order', 'orderDetails'])
            ->withSum('orderDetails', 'price')
            ->withSum('orderDetails', 'discount')
            ->withSum('orderDetails','qty')
            ->where('order_id', $request->order_id)->first();

        $mpdf_view = View::make('admin-views.transaction.order_wise_pdf', compact('company_phone', 'company_name', 'company_email', 'company_web_logo', 'transaction'));
        Helpers::gen_mpdf($mpdf_view, 'order_transaction_', $request->order_id);

    }

    public function order_transaction_count_query($query, $request)
    {
        $from = $request['from'];
        $to = $request['to'];
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $query_data = $query->when($status != 'all', function ($query) use ($status) {
                return $query->whereHas('orderTransaction', function ($q) use ($status) {
                    $q->where(['status' => $status]);
                });
            })
            ->when($customer_id != 'all', function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            });

        return self::date_wise_common_filter($query_data, $date_type, $from, $to);
    }

    public function order_transaction_piechart_query($request, $query)
    {
        $from = $request['from'];
        $to = $request['to'];
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $query_data = $query->where(['payment_status' => 'paid'])
            ->whereHas('orderTransaction', function ($query) use ($status) {
                $query->when($status != 'all', function ($query) use ($status) {
                    $query->where(['status' => $status]);
                });
            })
            ->when($customer_id != 'all', function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            });

        return self::date_wise_common_filter($query_data, $date_type, $from, $to);
    }

    public function order_transaction_chart_filter($request)
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

            return self::order_transaction_same_year($request, $current_start_year, $current_end_year, $from_year, $number, $default_inc);
        } elseif ($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d', strtotime($current_month_end));

            return self::order_transaction_same_month($request, $current_month_start, $current_month_end, $month, $number, $inc);
        } elseif ($date_type == 'this_week') {
            return self::order_transaction_this_week($request);
        } elseif ($date_type == 'today') {
            return self::getOrderTransactionForToday($request);
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
                return self::order_transaction_different_year($request, $start_date, $end_date, $from_year, $to_year);
            } elseif ($from_month != $to_month) {
                return self::order_transaction_same_year($request, $start_date, $end_date, $from_year, $to_month, $from_month);
            } elseif ($from_month == $to_month) {
                return self::order_transaction_same_month($request, $start_date, $end_date, $from_month, $to_day, $from_day);
            }

        }
    }

    public function order_transaction_same_month($request, $start_date, $end_date, $month_date, $number, $default_inc)
    {
        $year_month = date('Y-m', strtotime($start_date));
        $month = substr(date("F", strtotime("$year_month")), 0, 3);
        $orders = self::order_transaction_date_common_query($request, $start_date, $end_date)
            ->selectRaw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility="inhouse_shipping" THEN (order_amount - deliveryman_charge) ELSE order_amount END) as order_amount, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
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

    public function order_transaction_this_week($request)
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        $orders = self::order_transaction_date_common_query($request, $start_date, $end_date)
            ->select(
                DB::raw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility="inhouse_shipping" THEN (order_amount - deliveryman_charge) ELSE order_amount END) as order_amount'),
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

    public function getOrderTransactionForToday($request): array
    {
        $number = 1;
        $dayName = [Carbon::today()->format('l')];
        $start_date = Carbon::now()->startOfDay();
        $end_date = Carbon::now()->endOfDay();

        $orders = self::order_transaction_date_common_query($request, $start_date, $end_date)
            ->select(
                DB::raw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility="inhouse_shipping" THEN (order_amount - deliveryman_charge) ELSE order_amount END) as order_amount'),
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

    public function order_transaction_same_year($request, $start_date, $end_date, $from_year, $number, $default_inc)
    {

        $orders = self::order_transaction_date_common_query($request, $start_date, $end_date)
            ->selectRaw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility="inhouse_shipping" THEN (order_amount - deliveryman_charge) ELSE order_amount END) as order_amount, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = substr(date("F", strtotime("2023-$inc-01")), 0, 3);
            $order_amount[$month] = 0;
            foreach ($orders as $match) {
                if ($match['month'] == $inc) {
                    $order_amount[$month] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function order_transaction_different_year($request, $start_date, $end_date, $from_year, $to_year)
    {

        $orders = self::order_transaction_date_common_query($request, $start_date, $end_date)
            ->selectRaw('sum(CASE WHEN delivery_type="self_delivery" AND shipping_responsibility="inhouse_shipping" THEN (order_amount - deliveryman_charge) ELSE order_amount END) as order_amount, YEAR(updated_at) year')
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

    public function order_transaction_date_common_query($request, $start_date, $end_date)
    {
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';

        return Order::with('orderTransaction')
            ->where('payment_status', 'paid')
            ->when($status != 'all', function ($query) use ($status) {
                $query->whereHas('orderTransaction', function ($query) use ($status) {
                    $query->where(['status' => $status]);
                });
            })
            ->when($customer_id != 'all', function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            })
            ->whereBetween('updated_at', [$start_date, $end_date]);
    }

    public function order_transaction_table_data_filter($request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $transaction_query = OrderTransaction::with(['seller.shop', 'customer', 'order.deliveryMan', 'order'])
            ->with(['orderDetails'=> function ($query) {
                $query->selectRaw("*, sum(qty*price) as order_details_sum_price, sum(discount) as order_details_sum_discount")
                    ->groupBy('order_id');
            }])
            ->when($search, function ($q) use ($search) {
                $q->orWhere('order_id', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%");
            })
            ->when($status != 'all', function ($query) use ($status) {
                $query->where(['status' => $status]);
            })
            ->when($customer_id != 'all', function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            });

        return self::date_wise_common_filter($transaction_query, $date_type, $from, $to);
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

    public function expense_transaction_list(Request $request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';
        $query_param = ['search' => $search, 'date_type' => $date_type, 'from' => $from, 'to' => $to];

        $expense_transaction_chart = self::expense_transaction_chart_filter($request);

        $expense_calculate_query = Order::with(['orderTransaction', 'coupon'])
            ->where([
                'order_type'=> 'default_type',
                'coupon_discount_bearer'=> 'inhouse',
                'order_status'=>'delivered'
            ])
            ->where(function($query) {
                $query->whereNotIn('coupon_code', ['0', 'NULL'])
                    ->orWhere(function($query) {
                        $query->where([
                            'extra_discount_type'=>'free_shipping_over_order_amount',
                            'free_delivery_bearer'=>'seller'
                        ]);
                    });
            })
            ->whereHas('orderTransaction', function ($query) use($search){
                $query->where(['status'=>'disburse']);
            });
        $expense_calculate = self::date_wise_common_filter($expense_calculate_query, $date_type, $from, $to)->latest('updated_at')->get();

        $total_expense = 0;
        $free_delivery = 0;
        $coupon_discount = 0;
        if($expense_calculate){
            foreach ($expense_calculate as $calculate){
                $total_expense += ($calculate->coupon_discount_bearer == 'inhouse'?$calculate->discount_amount:0) + ($calculate->free_delivery_bearer=='admin'?$calculate->extra_discount:0);
                if(isset($calculate->coupon->coupon_type) && $calculate->coupon_discount_bearer == 'inhouse' && $calculate->coupon->coupon_type == 'free_delivery'){
                    $free_delivery += $calculate->discount_amount;
                }else{
                    $coupon_discount += $calculate->coupon_discount_bearer == 'inhouse'?$calculate->discount_amount:0;
                }

                if($calculate->is_shipping_free && $calculate->free_delivery_bearer=='admin'){
                    $free_delivery += $calculate->extra_discount;
                }
            }
        }

        $expense_transaction_query = Order::with(['orderTransaction', 'coupon'])
            ->where([
                'order_type'=> 'default_type',
                'coupon_discount_bearer'=> 'inhouse',
                'order_status'=>'delivered'
            ])
            ->where(function($query) {
                $query->whereNotIn('coupon_code', ['0', 'NULL'])
                    ->orWhere(function($query) {
                        $query->where([
                            'extra_discount_type'=>'free_shipping_over_order_amount',
                            'free_delivery_bearer'=>'seller'
                        ]);
                    });
            })
            ->whereHas('orderTransaction', function ($query) use($search){
                $query->where(['status'=>'disburse'])
                ->when($search, function ($q) use ($search) {
                    $q->Where('order_id', 'like', "%{$search}%")
                        ->orWhere('transaction_id', 'like', "%{$search}%");
                });
            });
        $expense_transactions_table = self::date_wise_common_filter($expense_transaction_query, $date_type, $from, $to);
        $expense_transactions_table = $expense_transactions_table->latest('updated_at')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.transaction.expense-list', compact('expense_transactions_table', 'expense_transaction_chart', 'search', 'from', 'to', 'date_type', 'total_expense', 'free_delivery', 'coupon_discount'));
    }

    /**
     * expense transaction report export by excel
     */
    public function expenseTransactionExportExcel(Request $request):BinaryFileResponse
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $dateType = $request['date_type'] ?? 'this_year';
        $expense_transaction_query = Order::with(['orderTransaction', 'coupon'])
            ->where([
                'coupon_discount_bearer'=> 'inhouse',
                'order_status'=>'delivered',
                'order_type'=> 'default_type',
            ])
            ->where(function($query) {
                $query->whereNotIn('coupon_code', ['0', 'NULL'])
                    ->orWhere(function($query) {
                        $query->where([
                            'extra_discount_type'=>'free_shipping_over_order_amount',
                            'free_delivery_bearer'=>'seller'
                        ]);
                    });
            })
            ->whereHas('orderTransaction', function ($query) use($search){
                $query->where(['status'=>'disburse'])
                    ->when($search, function ($q) use ($search) {
                        $q->where('order_id', 'like', "%{$search}%")
                            ->orWhere('transaction_id', 'like', "%{$search}%");
                    });
            });
        $transactions = self::date_wise_common_filter($expense_transaction_query, $dateType, $from, $to)->latest('updated_at')->get();
        $data = [
            'search' => $search,
            'from' => $from,
            'to' => $to,
            'dateType' => $dateType,
            'transactions' => $transactions,
        ];
        return Excel::download(new ExpenseTransactionReportExport($data), Report::EXPENSE_TRANSACTION_REPORT_LIST);
    }

    /**
     * expense transaction summary pdf
     */
    public function expense_transaction_summary_pdf(Request $request)
    {
        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';

        $duration = str_replace('_', ' ', $date_type);
        if ($date_type == 'custom_date') {
            $duration = 'From ' . $from . ' To ' . $to;
        }

        $expense_transaction_query = Order::with(['orderTransaction', 'coupon'])
            ->where([
                'order_type'=> 'default_type',
                'coupon_discount_bearer'=> 'inhouse',
                'order_status'=>'delivered'
            ])
            ->where(function($query) {
                $query->whereNotIn('coupon_code', ['0', 'NULL'])
                    ->orWhere(function($query) {
                        $query->where([
                            'extra_discount_type'=>'free_shipping_over_order_amount',
                            'free_delivery_bearer'=>'seller'
                        ]);
                    });
            })
            ->whereHas('orderTransaction', function ($query) use($search){
                $query->where(['status'=>'disburse'])
                    ->when($search, function ($q) use ($search) {
                        $q->where('order_id', 'like', "%{$search}%")
                            ->orWhere('transaction_id', 'like', "%{$search}%");
                    });
            });
        $expense_transactions = self::date_wise_common_filter($expense_transaction_query, $date_type, $from, $to)->get();
        $total_expense = 0;
        $free_delivery = 0;
        $coupon_discount = 0;
        $free_over_amount_discount = 0;

        if($expense_transactions){
            foreach ($expense_transactions as $transaction){
                $total_expense += ($transaction->coupon_discount_bearer == 'inhouse'?$transaction->discount_amount:0) + ($transaction->free_delivery_bearer=='admin'?$transaction->extra_discount:0);
                if(isset($transaction->coupon->coupon_type) && $transaction->coupon->coupon_type == 'free_delivery'){
                    $free_delivery += $transaction->discount_amount;
                }else{
                    $coupon_discount += $transaction->coupon_discount_bearer == 'inhouse'?$transaction->discount_amount:0;
                }

                $free_over_amount_discount += $transaction->free_delivery_bearer == 'admin' ? $transaction->extra_discount:0;
            }
        }

        $data = array(
            'total_expense' => $total_expense,
            'free_delivery' => $free_delivery,
            'coupon_discount' => $coupon_discount,
            'free_over_amount_discount' => $free_over_amount_discount,
            'company_phone' => $company_phone,
            'company_name' => $company_name,
            'company_email' => $company_email,
            'company_web_logo' => $company_web_logo,
            'duration' => $duration,
        );

        $mpdf_view = View::make('admin-views.transaction.expense_transaction_summary_report_pdf', compact('data'));
        Helpers::gen_mpdf($mpdf_view, 'expense_transaction_summary_report_', $date_type);

    }

    public function pdf_order_wise_expense_transaction(Request $request)
    {
        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $transaction = Order::with(['orderTransaction', 'coupon'])
            ->where('id', $request->id)->first();

        $mpdf_view = View::make('admin-views.transaction.order_wise_expense_pdf', compact('company_phone', 'company_name', 'company_email', 'company_web_logo', 'transaction'));
        Helpers::gen_mpdf($mpdf_view, 'expense_transaction_', $request->id);

    }

    public function expense_transaction_chart_filter($request)
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

            return self::expense_transaction_same_year($request, $current_start_year, $current_end_year, $from_year, $number, $default_inc);
        } elseif ($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d', strtotime($current_month_end));
            return self::expense_transaction_same_month($request, $current_month_start, $current_month_end, $month, $number, $inc);
        } elseif ($date_type == 'this_week') {
            return self::expense_transaction_this_week($request);
        } elseif ($date_type == 'today') {
            return self::getExpenseTransactionForToday($request);
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
                return self::expense_transaction_different_year($request, $start_date, $end_date, $from_year, $to_year);
            } elseif ($from_month != $to_month) {
                return self::expense_transaction_same_year($request, $start_date, $end_date, $from_year, $to_month, $from_month);
            } elseif ($from_month == $to_month) {
                return self::expense_transaction_same_month($request, $start_date, $end_date, $from_month, $to_day, $from_day);
            }

        }
    }

    public function expense_transaction_same_month($request, $start_date, $end_date, $month_date, $number, $default_inc)
    {
        $year_month = date('Y-m', strtotime($start_date));
        $month = substr(date("F", strtotime("$year_month")), 0, 3);
        $orders = self::expense_chart_common_query($request)
            ->selectRaw('sum((CASE WHEN coupon_discount_bearer="inhouse" THEN discount_amount ELSE 0 END) + (CASE WHEN free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as discount_amount, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $discount_amount[$inc] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $inc) {
                    $discount_amount[$inc] = $match['discount_amount'];
                }
            }
        }

        return array(
            'discount_amount' => $discount_amount,
        );
    }

    public function expense_transaction_this_week($request)
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        $orders = self::expense_chart_common_query($request)
            ->select(
                DB::raw('sum((CASE WHEN coupon_discount_bearer="inhouse" THEN discount_amount ELSE 0 END) + (CASE WHEN free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as discount_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $discount_amount[$day_name[$inc]] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $discount_amount[$day_name[$inc]] = $match['discount_amount'];
                }
            }
        }

        return array(
            'discount_amount' => $discount_amount,
        );
    }

    public function getExpenseTransactionForToday($request): array
    {
        $number = 1;
        $dayName = [Carbon::today()->format('l')];
        $orders = self::expense_chart_common_query($request)
            ->select(
                DB::raw('sum((CASE WHEN coupon_discount_bearer="inhouse" THEN discount_amount ELSE 0 END) + (CASE WHEN free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as discount_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc < $number; $inc++) {
            $discountAmount[$dayName[$inc]] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $dayName[$inc]) {
                    $discountAmount[$dayName[$inc]] = $match['discount_amount'];
                }
            }
        }

        return [
            'discount_amount' => $discountAmount ?? [],
        ];
    }

    public function expense_transaction_same_year($request, $start_date, $end_date, $from_year, $number, $default_inc)
    {

        $orders = self::expense_chart_common_query($request)
            ->selectRaw('sum((CASE WHEN coupon_discount_bearer="inhouse" THEN discount_amount ELSE 0 END) + (CASE WHEN free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as discount_amount, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $discount_amount[$month] = 0;
            foreach ($orders as $match) {
                if ($match['month'] == $inc) {
                    $discount_amount[$month] = $match['discount_amount'];
                }
            }
        }

        return array(
            'discount_amount' => $discount_amount,
        );
    }

    public function expense_transaction_different_year($request, $start_date, $end_date, $from_year, $to_year)
    {
        $orders = self::expense_chart_common_query($request)
            ->selectRaw('sum((CASE WHEN coupon_discount_bearer="inhouse" THEN discount_amount ELSE 0 END) + (CASE WHEN free_delivery_bearer="admin" THEN extra_discount ELSE 0 END)) as discount_amount, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $discount_amount[$inc] = 0;
            foreach ($orders as $match) {
                if ($match['year'] == $inc) {
                    $discount_amount[$inc] = $match['discount_amount'];
                }
            }
        }

        return array(
            'discount_amount' => $discount_amount,
        );

    }

    public function expense_chart_common_query($request){
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';

        $order_query = Order::where([
            'order_type'=> 'default_type',
            'coupon_discount_bearer'=> 'inhouse',
            'order_status'=>'delivered'
        ])
            ->where(function($query) {
                $query->whereNotIn('coupon_code', ['0', 'NULL'])
                    ->orWhere(function($query) {
                        $query->where([
                            'extra_discount_type'=>'free_shipping_over_order_amount',
                            'free_delivery_bearer'=>'seller'
                        ]);
                    });
            })
            ->whereHas('orderTransaction', function ($query){
                $query->where(['status'=>'disburse']);
            });

        return self::date_wise_common_filter($order_query, $date_type, $from, $to);
    }

    public function wallet_bonus(Request $request)
    {
        return view('admin-views.transaction.wallet-bonus');
    }

}
