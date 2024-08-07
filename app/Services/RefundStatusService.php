<?php

namespace App\Services;

use App\Traits\CustomerTrait;

class RefundStatusService
{
    use CustomerTrait;

    /**
     * @param object $request
     * @param object $refund
     * @param string $changeBy
     * @return array
     */
    public function getRefundStatusData(object $request, object $refund, string $changeBy):array
    {
        return [
            'refund_request_id' => $refund['id'],
            'change_by' => $changeBy,
            'change_by_id' => $changeBy === 'seller' ? auth('seller')->id() : 1,
            'status' => $request['refund_status'],
            'message' => $request['approved_note'] ?? $request['rejected_note'] ?? null,
            'approved_note' => $request['approved_note'] ?? null,
            'rejected_note' => $request['rejected_note'] ?? null,
        ];
    }

    public function getRefundStatusProcessData(object $request, object $orderDetails, object $refund, string|float|int $loyaltyPoint): array
    {
        $refundStatus = [
            'refund_request_id' => $refund['id'],
            'change_by' => 'admin',
            'change_by_id' => auth('admin')->id(),
            'status' => $request['refund_status'],
        ];

        $refundData = [];
        if ($request['refund_status'] == 'pending') {
            $orderDetails['refund_request'] = 1;
        } elseif ($request['refund_status'] == 'approved') {
            $orderDetails['refund_request'] = 2;
            $refundData['approved_note'] = $request['approved_note'];
            $refundStatus['message'] = $request['approved_note'];
        } elseif ($request['refund_status'] == 'rejected') {
            $orderDetails['refund_request'] = 3;
            $refundData['rejected_note'] = $request['rejected_note'];

            $refundStatus['message'] = $request['rejected_note'];
        } elseif ($request['refund_status'] == 'refunded') {
            $orderDetails['refund_request'] = 4;
            $refundData['payment_info'] = $request['payment_info'];
            $refundStatus['message'] = $request['payment_info'];

            $walletAddRefund = getWebConfig(name: 'wallet_add_refund');
            if ($walletAddRefund == 1 && $request['payment_method'] == 'customer_wallet') {
                $this->createWalletTransaction(user_id: $refund['customer_id'], amount: usdToDefaultCurrency(amount: $refund['amount']), transaction_type: 'order_refund', reference: 'order_refund');
            }
        }
        $refundData['status'] = $request['refund_status'];
        $refundData['change_by'] = 'admin';

        return [
            'refund' => $refundData,
            'orderDetails' => $orderDetails,
            'refundStatus' => $refundStatus,
        ];
    }


}
