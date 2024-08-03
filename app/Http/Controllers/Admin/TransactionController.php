<?php

namespace App\Http\Controllers\Admin;

use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\OrderTransaction;
use App\User;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class TransactionController extends Controller
{
    public function list(Request $request)
    {
        $query_param  = [];
        $search       = $request['search'];
        $from         = $request['from'];
        $to           = $request['to'];
        $customer_id  = $request['customer_id'];
        $status       = $request['status'];

        $customers = User::whereNotIn('id',[0])->get();

        $transactions = OrderTransaction::with(['seller','customer'])
                        ->when($search, function($q) use($search){
                            $q->orWhere('order_id', 'like', "%{$search}%")
                                ->orWhere('transaction_id', 'like', "%{$search}%");
                        })
                        ->when($customer_id, function($q) use($customer_id){
                            $q->where('customer_id', $customer_id);
                        })
                        ->when($status == 'all', function ($q) use($status){
                            $q;
                        })
                        ->when(!empty($status) && ($status != 'all'), function ($q) use($status){
                            $q->where('status', 'like', "%{$status}%");
                        })
                        ->when(!empty($from) && !empty($to),function($query) use($from,$to){
                            $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
                        })
                        ->latest()->paginate(Helpers::pagination_limit())->appends([
                                        'customer_id'=>$customer_id,
                                        'status'=>$status,
                                        'from'=>$from,
                                        'to'=>$to,
                                        'search'=>$search]);


        return view('admin-views.transaction.list', compact('customers', 'transactions','search','status', 'from', 'to', 'customer_id'));
    }

    /**
     * Transaction report export by excel
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse|string
    {
        $from         = $request['from'];
        $to           = $request['to'];
        $customer_id  = $request['customer_id'];
        $status       = $request['status'];

        $transactions = OrderTransaction::with(['seller','customer'])
            ->when($customer_id, function($q) use($customer_id){
                $q->where('customer_id', $customer_id);
            })
            ->when($status == 'all', function ($q) use($status){
                $q;
            })
            ->when(!empty($status) && ($status != 'all'), function ($q) use($status){
                $q->where('status', 'like', "%{$status}%");
            })
            ->when(!empty($from) && !empty($to),function($query) use($from,$to){
                $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
            })
            ->latest()->get();

        $tranData = array();
        foreach($transactions as $tran){
            if($tran['seller_is'] == 'admin'){
                $seller_name = \App\Utils\Helpers::get_business_settings('company_name');
            }else{
                $seller_name = $tran->seller ? $tran->seller->f_name .' '. $tran->seller->l_name : translate('not_found');
            }

            $tranData[] = array(
                'Seller Name' => $seller_name,
                'Customer Name' => $tran->customer ? $tran->customer->f_name.' '.$tran->customer->l_name : translate('not_found'),
                'Order ID' => $tran->order_id,
                'Transaction ID' => $tran->transaction_id,
                'Order Amount' => \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($tran->order_amount)),
                'Seller Amount' => \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($tran->seller_amount)),
                'Admin Commission' => \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($tran->admin_commission)),
                'Received By' => $tran->received_by,
                'Delivered By' => $tran->delivered_by,
                'Delivery Charge' => $tran->delivery_charge,
                'Payment Method' => $tran->payment_method,
                'Tax' => $tran->tax,
                'Date' => date('d M Y',strtotime($tran->created_at)),
                'Status' => $tran->status,
            );
        }

        return (new FastExcel($tranData))->download('Transaction_All_details.xlsx');

    }
}
