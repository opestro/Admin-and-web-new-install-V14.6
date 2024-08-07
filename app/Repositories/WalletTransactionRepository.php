<?php

namespace App\Repositories;

use App\Contracts\Repositories\WalletTransactionRepositoryInterface;
use App\Models\AddFundBonusCategories;
use App\Models\WalletTransaction;
use App\Models\BusinessSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletTransactionRepository implements WalletTransactionRepositoryInterface
{
    public function __construct(
        private readonly WalletTransaction           $walletTransaction,
        private readonly BusinessSetting             $businessSetting,
        private readonly User                        $user,
        private readonly AddFundBonusCategories      $addFundBonusCategories,
    )
    {
    }


    public function add(array $data): string|object
    {
        return $this->walletTransaction->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->walletTransaction->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->walletTransaction->with($relations)->when(!empty($orderBy), function ($query) use ($orderBy) {
            $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
        });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->walletTransaction
            ->when(!empty($filters['from']) && !empty($filters['to']),function($query)use($filters){
                $query->whereBetween('created_at', [$filters['from'].' 00:00:00', $filters['to'].' 23:59:59']);
            })
            ->when(!empty($filters['transaction_type']) && $filters['transaction_type'] != 'all', function($query)use($filters){
                $query->where('transaction_type',$filters['transaction_type']);
            })
            ->when(!empty($filters['customer_id']) &&  $filters['customer_id'] != 'all', function($query)use($filters) {
                $query->where('user_id', $filters['customer_id']);
            })->latest();

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends(['searchValue' => $searchValue]);
    }

    public function getListWhereSelect(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->walletTransaction->selectRaw('sum(credit) as total_credit, sum(debit) as total_debit')
            ->when((!empty($filters['from']) && !empty($filters['to'])),function($query)use($filters){
                $query->whereBetween('created_at', [$filters['from'].' 00:00:00', $filters['to'].' 23:59:59']);
            })
            ->when(!empty($filters['transaction_type']) && $filters['transaction_type'] != 'all', function($query)use($filters){
                $query->where('transaction_type', $filters['transaction_type']);
            })
            ->when(!empty($filters['customer_id']) && $filters['customer_id'] != 'all', function($query)use($filters) {
                $query->where('user_id', $filters['customer_id']);
            })->latest();

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends(['searchValue' => $searchValue]);
    }

    public function update(string $id, array $data): bool
    {
        return $this->walletTransaction->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        $this->walletTransaction->where($params)->delete();
        return true;
    }

    public function addWalletTransaction(string $user_id, float $amount, string $transactionType, string $reference=null, array $payment_data=[]): bool|WalletTransaction
    {
        if($this->businessSetting->where('type','wallet_status')->first()->value != 1) return false;

        $user = $this->user->where('id', $user_id)->first();
        $currentBalance = $user->wallet_balance;

        $wallet_transaction = new WalletTransaction();
        $wallet_transaction['user_id'] = $user->id;
        $wallet_transaction['transaction_id'] = Str::uuid();
        $wallet_transaction['reference'] = $reference;
        $wallet_transaction['transaction_type'] = $transactionType;
        $wallet_transaction['payment_method'] = $payment_data['payment_method'] ?? null;

        $debit = 0.0;
        $credit = 0.0;
        $addFundToWalletBonus = 0;

        if(in_array($transactionType, ['add_fund_by_admin','add_fund','order_refund','loyalty_point'])) {
            $credit = $amount;
            if($transactionType == 'add_fund') {
                $wallet_transaction['admin_bonus'] = $this->addFundToWalletBonus(amount: currencyConverter(amount:$amount ?? 0));
                $addFundToWalletBonus = $this->addFundToWalletBonus(amount: currencyConverter(amount:$amount ?? 0));
            }
            else if($transactionType == 'loyalty_point')
            {
                $credit = (($amount / $this->businessSetting->where('type','loyalty_point_exchange_rate')->first()->value) * currencyConverter(1));
            }
        } else if($transactionType == 'order_place')
        {
            $debit = $amount;
        }

        $credit_amount = currencyConverter(amount: $credit);
        $debit_amount = currencyConverter(amount: $debit);
        $wallet_transaction['credit'] = $credit_amount;
        $wallet_transaction['debit'] = $debit_amount;
        $wallet_transaction['balance'] = $currentBalance + $credit_amount - $debit_amount;
        $wallet_transaction['created_at'] = now();
        $wallet_transaction['updated_at'] = now();

        try{
            DB::beginTransaction();
            $this->user->where('id', $user_id)->update([
                'wallet_balance' => $currentBalance + $addFundToWalletBonus + $credit_amount - $debit_amount,
            ]);
            $wallet_transaction->save();
            DB::commit();
            if(in_array($transactionType, ['loyalty_point','order_place','add_fund_by_admin'])) return $wallet_transaction;
            return true;
        }catch(\Exception $ex) {
            DB::rollback();
        }
        return false;
    }

    public function addFundToWalletBonus(float $amount): string|float
    {
        $bonuses = $this->addFundBonusCategories->where('is_active', 1)
                    ->whereDate('start_date_time', '<=', now())
                    ->whereDate('end_date_time', '>=', now())
                    ->where('min_add_money_amount', '<=', $amount)
                    ->get();

        $applicableBonuses = $bonuses->where('min_add_money_amount', $bonuses->max('min_add_money_amount'));

        foreach ($applicableBonuses as $key => $item) {
            $item->applied_bonus_amount = $item->bonus_type == 'percentage' ? ($amount * $item->bonus_amount) / 100 : $item->bonus_amount;

            //max bonus check
            if ($item->bonus_type == 'percentage' && $item->applied_bonus_amount > $item->max_bonus_amount) {
                $item->applied_bonus_amount = $item->max_bonus_amount;
            }
        }
        return $bonuses->max('applied_bonus_amount') ?? 0;
    }
}
