<?php

namespace App\Services;

class RefundTransactionService
{
    public function getData(object $request, object $refund, object $order): array
    {
        return [
            'order_id'=>  $refund['order_id'],
            'payment_for'=>  'Refund Request',
            'payer_id'=>  $order['seller_id'],
            'payment_receiver_id'=>  $refund['customer_id'],
            'paid_by'=>  $order['seller_is'],
            'paid_to'=>  'customer',
            'payment_method'=>  $request['payment_method'],
            'payment_status'=>  $request['payment_method'] != null ? 'paid' : 'unpaid',
            'amount'=>  $refund['amount'],
            'transaction_type'=>  'Refund',
            'order_details_id'=>  $refund['order_details_id'],
            'refund_id'=>  $refund['id'],
        ];
    }
    public function getPDFData(string $companyPhone,string $companyEmail,string $companyName,string $companyWebLogo,object $refundTransactions,):array
    {
        $totalAmount = 0;
        foreach($refundTransactions as $transaction){
            $totalAmount += $transaction['amount'];
        }
        return [
            'total_amount' => $totalAmount,
            'company_phone' => $companyPhone,
            'company_email' => $companyEmail,
            'company_name' => $companyName,
            'company_web_logo' => $companyWebLogo,
        ];
    }
}
