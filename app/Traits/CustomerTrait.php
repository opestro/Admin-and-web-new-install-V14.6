<?php

namespace App\Traits;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\LoyaltyPointTransactionRepositoryInterface;
use App\Contracts\Repositories\OrderDetailRepositoryInterface;
use App\Models\BusinessSetting;
use App\Models\User;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Utils\Helpers;
use App\Utils\Convert;

trait CustomerTrait
{
    public function __construct(
        private readonly OrderDetailRepositoryInterface             $orderDetailRepo,
        private readonly CustomerRepositoryInterface                $customerRepo,
        private readonly LoyaltyPointTransactionRepositoryInterface $loyaltyPointTransactionRepo,
    )
    {
    }

    protected function convertAmountToLoyaltyPoint(object $orderDetails):int
    {
        $loyaltyPointStatus = getWebConfig('loyalty_point_status');
        $loyaltyPoint = 0;
        if($loyaltyPointStatus == 1)
        {
            $getLoyaltyPointOnPurchase =getWebConfig('loyalty_point_item_purchase_point');
            $subtotal = ($orderDetails['price'] * $orderDetails['qty']) - $orderDetails['discount'] + $orderDetails['tax'];
            $loyaltyPoint = (int)(usdToDefaultCurrency(amount: $subtotal) * $getLoyaltyPointOnPurchase /100);
        }
        return $loyaltyPoint;
    }

    protected function createWalletTransaction($user_id, float $amount, $transaction_type, $reference, $payment_data=[]): bool|WalletTransaction
    {
        if(BusinessSetting::where('type','wallet_status')->first()->value != 1) return false;
        $user = User::find($user_id);
        $currentBalance = $user->wallet_balance;

        $walletTransaction = new WalletTransaction();
        $walletTransaction->user_id = $user->id;
        $walletTransaction->transaction_id = \Str::uuid();
        $walletTransaction->reference = $reference;
        $walletTransaction->transaction_type = $transaction_type;
        $walletTransaction->payment_method = isset($payment_data['payment_method']) ? $payment_data['payment_method'] : null;

        $debit = 0.0;
        $credit = 0.0;
        $addFundToWalletBonus = 0;

        if(in_array($transaction_type, ['add_fund_by_admin','add_fund','order_refund','loyalty_point']))
        {
            $credit = $amount;
            if($transaction_type == 'add_fund')
            {
                $walletTransaction->admin_bonus = Helpers::add_fund_to_wallet_bonus(Convert::usd($amount ?? 0));
                $addFundToWalletBonus = Helpers::add_fund_to_wallet_bonus(Convert::usd($amount ?? 0));
            }
            else if($transaction_type == 'loyalty_point')
            {
                $credit = (($amount / BusinessSetting::where('type','loyalty_point_exchange_rate')->first()->value)*Convert::default(1));
            }
        }
        else if($transaction_type == 'order_place')
        {
            $debit = $amount;
        }

        $creditAmount = currencyConverter($credit);
        $debitAmount = currencyConverter($debit);
        $walletTransaction->credit = $creditAmount;
        $walletTransaction->debit = $debitAmount;
        $walletTransaction->balance = $currentBalance + $creditAmount - $debitAmount;
        $walletTransaction->created_at = now();
        $walletTransaction->updated_at = now();
        $user->wallet_balance = $currentBalance + $addFundToWalletBonus + $creditAmount - $debitAmount;

        try{
            DB::beginTransaction();
            $user->save();
            $walletTransaction->save();
            DB::commit();
            if(in_array($transaction_type, ['loyalty_point','order_place','add_fund_by_admin'])) return $walletTransaction;
            return true;
        }catch(Exception $ex) {
            info($ex);
            DB::rollback();
            return false;
        }
    }

    public function countLoyaltyPointForAmount(string|int $id): int|float
    {
        $orderDetails = $this->orderDetailRepo->getFirstWhere(params: ['id' => $id]);
        $loyaltyPointStatus = getWebConfig(name: 'loyalty_point_status');
        $loyaltyPoint = 0;
        if ($loyaltyPointStatus == 1) {
            $loyaltyPointItemPurchasePoint = getWebConfig(name: 'loyalty_point_item_purchase_point');
            $subtotal = ($orderDetails['price'] * $orderDetails['qty']) - $orderDetails['discount'] + $orderDetails['tax'];
            return (int)(usdToDefaultCurrency($subtotal) * $loyaltyPointItemPurchasePoint / 100);
        }
        return $loyaltyPoint;
    }


}
