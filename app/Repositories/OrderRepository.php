<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\AdminWallet;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderExpectedDeliveryHistory;
use App\Models\OrderTransaction;
use App\Models\Product;
use App\Models\SellerWallet;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{

    public function __construct(
        private readonly Order                        $order,
        private readonly OrderExpectedDeliveryHistory $orderExpectedDeliveryHistory,
        private readonly Product                      $product,
        private readonly OrderDetail                  $orderDetail,
        private readonly AdminWallet                  $adminWallet,
        private readonly SellerWallet                 $sellerWallet,
        private readonly Transaction                  $transaction,
        private readonly OrderTransaction             $orderTransaction,
    )
    {
    }


    public function add(array $data): string|object
    {
        return $this->order->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->order->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->order
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(key($orderBy), current($orderBy));
            })->get();
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->order->with($relations)
            ->when(isset($filters['seller_is']) && $filters['seller_is'] != 'all', function ($query) use ($filters) {
                return $query->where('seller_is', $filters['seller_is']);
            })
            ->when(isset($filters['seller_id']) && $filters['seller_id'] != 'all', function ($query) use ($filters) {
                return $query->where('seller_id', $filters['seller_id']);
            })
            ->when(isset($filters['order_type']) && $filters['order_type'] != 'all', function ($query) use ($filters) {
                return $query->where('order_type', $filters['order_type']);
            })
            ->when(isset($filters['order_status']) && $filters['order_status'] != 'all', function ($query) use ($filters) {
                return $query->where('order_status', $filters['order_status']);
            })
            ->when(isset($filters['customer_id']) && $filters['customer_id'] != 'all', function ($query) use ($filters) {
                return $query->where('customer_id', $filters['customer_id']);
            })
            ->when(isset($filters['is_guest']), function ($query) use ($filters) {
                return $query->where('is_guest', $filters['is_guest']);
            })
            ->when(isset($filters['customer_type']), function ($query) use ($filters) {
                return $query->where('is_guest', $filters['customer_type']);
            })
            ->when(isset($filters['coupon_code']), function ($query) use ($filters) {
                return $query->where('coupon_code', $filters['coupon_code']);
            })
            ->when(isset($filters['checked']), function ($query) use ($filters) {
                return $query->where('checked', $filters['checked']);
            })
            ->when(isset($filters['filter']), function ($query) use ($filters) {
                    $query->when($filters['filter'] == 'all', function ($query) {
                        return $query;
                    })
                    ->when($filters['filter'] == 'POS', function ($query) {
                        return $query->where('order_type', 'POS');
                    })
                    ->when($filters['filter'] == 'default_type', function ($query) {
                        return $query->where('order_type', 'default_type');
                    })
                    ->when($filters['filter'] == 'admin' || $filters['filter'] == 'seller', function ($query) use ($filters) {
                        return $query->whereHas('details', function ($query) use ($filters) {
                            return $query->whereHas('product', function ($query) use ($filters) {
                                return $query->where('added_by', $filters['filter']);
                            });
                        });
                    });
            })
            ->when(isset($filters['date_type']) && $filters['date_type'] == "this_year", function ($query) {
                $current_start_year = date('Y-01-01');
                $current_end_year = date('Y-12-31');
                return $query->whereDate('created_at', '>=', $current_start_year)
                    ->whereDate('created_at', '<=', $current_end_year);
            })
            ->when(isset($filters['date_type']) && $filters['date_type'] == "this_month", function ($query) {
                $current_month_start = date('Y-m-01');
                $current_month_end = date('Y-m-t');
                return $query->whereDate('created_at', '>=', $current_month_start)
                    ->whereDate('created_at', '<=', $current_month_end);
            })
            ->when(isset($filters['date_type']) && $filters['date_type'] == "this_week", function ($query) {
                $start_week = Carbon::now()->subDays(7)->startOfWeek()->format('Y-m-d');
                $end_week = Carbon::now()->startOfWeek()->format('Y-m-d');
                return $query->whereDate('created_at', '>=', $start_week)
                    ->whereDate('created_at', '<=', $end_week);
            })
            ->when(isset($filters['date_type']) && $filters['date_type'] == "custom_date" && isset($filters['from']) && isset($filters['to']), function ($query) use ($filters) {
                return $query->whereDate('created_at', '>=', $filters['from'])
                    ->whereDate('created_at', '<=', $filters['to']);
            })
            ->when(isset($filters['delivery_man_id']), function ($query) use ($filters) {
                return $query->where(['delivery_man_id' => $filters['delivery_man_id']]);
            })
            ->when($searchValue, function ($query) use ($searchValue) {
                return $query->where(function ($query) use ($searchValue) {
                    return $query->where('id', 'like', "%{$searchValue}%")
                        ->orWhere('order_status', 'like', "%{$searchValue}%")
                        ->orWhere('transaction_ref', 'like', "%{$searchValue}%");
                });
            })
            ->when(isset($filters['whereHas_deliveryMan']), function ($query) use($filters) {
                return $query->whereHas('deliveryMan', function($query) use ($filters){
                    $query->where('seller_id',$filters['whereHas_deliveryMan']);
                });
            })
            ->when(isset($filters['whereIn_order_status']) && $filters['whereIn_order_status'] != 'all', function ($query) use($filters) {
                $query->whereIn('order_status',$filters['whereIn_order_status']);
            })
            ->when(isset($filters['whereIn_payment_status']) && $filters['whereIn_payment_status'] != 'all', function ($query) use($filters) {
                $query->whereIn('payment_status',$filters['whereIn_payment_status']);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereDate(array $filters = [], string $dateType = null, array $filterDate = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->order->with($relations)
            ->when($filters['order_status'], function ($query) use ($filters) {
                $query->where(['order_status' => $filters['order_status']]);
            })
            ->when($filters['seller_is'] == 'seller', function ($query) use ($filters) {
                $query->where(['seller_is' => 'seller', 'seller_id' => $filters['seller_id']]);
            })
            ->when($dateType == 'today', function ($query) use ($filterDate) {
                $query->whereDate('created_at', Carbon::today());
            })
            ->when($dateType == 'thisMonth', function ($query) use ($filterDate) {
                $query->whereMonth('created_at', Carbon::now());
            })
            ->get();
    }

    public function getListWhereCount(string $searchValue = null, array $filters = [], array $relations = []): int
    {
        return $this->order->with($relations)
            ->when(isset($filters['customer_id']), function ($query) use ($filters) {
                return $query->where(['customer_id' => $filters['customer_id']]);
            })->count();
    }

    public function getDeliveryManOrderListWhere(string $addedBy, string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->order->select('id', 'deliveryman_charge', 'order_status', 'delivery_man_id')->where($filters)
            ->whereHas('deliveryMan', function ($query) use ($addedBy) {
                $query->when($addedBy === 'seller', function ($subQuery) {
                    $subQuery->where(['seller_id' => auth('seller')->id()]);
                });
            })
            ->when($searchValue, function ($query) use ($searchValue) {
                $query->where('id', 'like', "%$searchValue%");
            })
            ->latest()
            ->paginate($dataLimit)
            ->appends(['searchValue' => $searchValue]);
    }

    public function update(string $id, array $data): bool
    {
        return $this->order->where('id', $id)->update($data);
    }

    public function updateWhere(array $params, array $data): bool
    {
        return $this->order->where($params)->update($data);
    }

    public function delete(array $params): bool
    {
        $this->order->where($params)->delete();
        return true;
    }

    public function getListWhereNotIn(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], array $nullFields = [], array $whereNotIn = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->order->where($filters)
            ->when(!empty($searchValue), function ($query) use ($searchValue) {
                $query->whereHas('order', function ($query) use ($searchValue) {
                    $query->where('id', 'like', "%{$searchValue}%");
                });
            })
            ->when(!empty($whereNotIn), function ($query) use ($whereNotIn) {
                foreach ($whereNotIn as $key => $whereNotInIndex) {
                    $query->whereNotIn($key, $whereNotInIndex);
                }
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function updateAmountDate(object $request, string|int $userId, string $userType): bool
    {
        $fieldName = $request['field_name'];
        $fieldValues = $request['field_val'];
        $cause = $request['cause'] ?? null;

        if($fieldName == 'deliveryman_charge'){
            $fieldValues = currencyConverter(amount: $fieldValues);
        }

        try {
            DB::beginTransaction();

            if ($fieldName == 'expected_delivery_date') {
                $this->orderExpectedDeliveryHistory->create([
                    'order_id' => $request['order_id'],
                    'user_id' => $userId,
                    'user_type' => $userType,
                    'expected_delivery_date' => $fieldValues,
                    'cause' => $cause
                ]);
            }

            $this->order->where(['id' => $request['order_id']])->update([$fieldName => $fieldValues]);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return false;
        }
        return true;
    }

    public function updateStockOnOrderStatusChange(string|int $orderId, string $status): bool
    {
        $order = $this->order->with('details.product')->find($orderId);
        if ($status == 'returned' || $status == 'failed' || $status == 'canceled') {
            foreach ($order['details'] as $detail) {
                if ($detail['is_stock_decreased'] == 1) {
                    $product = $detail->product;
                    $type = $detail['variant'];
                    $variations = [];
                    if($product['variation']){
                        foreach (json_decode($product['variation'], true) as $variation) {
                            if ($type == $variation['type']) {
                                $variation['qty'] += $detail['qty'];
                            }
                            $variations[] = $variation;
                        }
                    }
                    $this->product->where(['id' => $product['id']])->update([
                        'variation' => json_encode($variations),
                        'current_stock' => $product['current_stock'] + $detail['qty'],
                    ]);
                    $this->orderDetail->where(['id' => $detail['id']])->update([
                        'is_stock_decreased' => 0,
                        'delivery_status' => $status
                    ]);
                }
            }
        } else {
            foreach ($order['details'] as $detail) {
                if ($detail['is_stock_decreased'] == 0) {
                    $product = $detail->product;
                    $type = $detail['variant'];
                    $variations = [];
                    foreach (json_decode($product['variation'], true) as $variation) {
                        if ($type == $variation['type']) {
                            $variation['qty'] -= $detail['qty'];
                        }
                        $variations[] = $variation;
                    }
                    $this->product->where(['id' => $product['id']])->update([
                        'variation' => json_encode($variations),
                        'current_stock' => $product['current_stock'] - $detail['qty'],
                    ]);
                    $this->orderDetail->where(['id' => $detail['id']])->update([
                        'is_stock_decreased' => 1,
                        'delivery_status' => $status
                    ]);
                }
            }
        }

        return true;
    }

    public function manageWalletOnOrderStatusChange(object $order, string $receivedBy): bool
    {
        $order = $this->order->find($order['id']);
        $orderSummary = getOrderSummary(order: $order);
        $orderAmount = $orderSummary['subtotal'] - $orderSummary['total_discount_on_product'] - $order['discount_amount'];
        $commission = $order['admin_commission'];
        $shippingModel = $order->shipping_responsibility;

        $adminWallet = $this->adminWallet->where('admin_id', 1)->first();
        if (!$adminWallet) {
            $adminWalletData = [
                'admin_id' => 1,
                'withdrawn' => 0,
                'commission_earned' => 0,
                'inhouse_earning' => 0,
                'delivery_charge_earned' => 0,
                'pending_amount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $this->adminWallet->create($adminWalletData);
        }

        $sellerWallet = $this->sellerWallet->where('seller_id', $order['seller_id'])->first();
        if (!$sellerWallet) {
            $sellerWalletData = [
                'seller_id' => $order['seller_id'],
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $this->sellerWallet->create($sellerWalletData);
        }

        // coupon transaction start
        if ($order['coupon_code'] && $order['coupon_code'] != '0' && $order['seller_is'] == 'seller' && $order['discount_type'] == 'coupon_discount') {
            if ($order['coupon_discount_bearer'] == 'inhouse') {
                $sellerWallet = $this->sellerWallet->where('seller_id', $order['seller_id'])->first();
                $sellerWallet['total_earning'] += $order['discount_amount'];
                $sellerWallet->save();

                $paidBy = 'admin';
                $payerId = 1;
                $paymentReceiverId = $order['seller_id'];
                $paidTo = 'seller';

            } elseif ($order->coupon_discount_bearer == 'seller') {
                $paidBy = 'seller';
                $payerId = $order['seller_id'];
                $paymentReceiverId = $order['seller_id'];
                $paidTo = 'admin';
            }

            $transaction = [
                'order_id' => $order->id,
                'payment_for' => 'coupon_discount',
                'payer_id' => $payerId,
                'payment_receiver_id' => $paymentReceiverId,
                'paid_by' => $paidBy,
                'paid_to' => $paidTo,
                'payment_status' => 'disburse',
                'amount' => $order['discount_amount'],
                'transaction_type' => 'expense',
            ];
            $this->transaction->create($transaction);
        }
        // coupon transaction end

        // free delivery over amount transaction start
        if ($order['is_shipping_free'] && $order['seller_is'] == 'seller') {

            $sellerWallet = $this->sellerWallet->where('seller_id', $order['seller_id'])->first();
            $adminWallet = $this->adminWallet->where('admin_id', 1)->first();

            if ($order['free_delivery_bearer'] == 'admin' && $order['shipping_responsibility'] == 'sellerwise_shipping') {
                $sellerWallet->delivery_charge_earned += $order->extra_discount;
                $sellerWallet->total_earning += $order->extra_discount;

                $adminWallet->delivery_charge_earned -= $order->extra_discount;
                $adminWallet->inhouse_earning -= $order->extra_discount;

                $paidBy = 'admin';
                $payerId = 1;
                $paymentReceiverId = $order->seller_id;
                $paidTo = 'seller';

            } elseif ($order->free_delivery_bearer == 'seller' && $order->shipping_responsibility == 'inhouse_shipping') {
                $sellerWallet->delivery_charge_earned -= $order->extra_discount;
                $sellerWallet->total_earning -= $order->extra_discount;

                $adminWallet->delivery_charge_earned += $order->extra_discount;
                $adminWallet->inhouse_earning += $order->extra_discount;

                $paidBy = 'seller';
                $payerId = $order->seller_id;
                $paymentReceiverId = $order->seller_id;
                $paidTo = 'admin';
            }elseif ($order['free_delivery_bearer'] == 'admin' && $order['shipping_responsibility'] == 'inhouse_shipping') {
                $paidBy = 'admin';
                $payerId = 1;
                $paymentReceiverId = $order->seller_id;
                $paidTo = 'admin';
            }elseif ($order->free_delivery_bearer == 'seller' && $order->shipping_responsibility == 'sellerwise_shipping') {
                $paidBy = 'seller';
                $payerId = $order->seller_id;
                $paymentReceiverId = $order->seller_id;
                $paidTo = 'seller';
            }


            $sellerWallet->save();
            $adminWallet->save();

            $transaction = [
                'order_id' => $order->id,
                'payment_for' => 'free_shipping_over_order_amount',
                'payer_id' => $payerId,
                'payment_receiver_id' => $paymentReceiverId,
                'paid_by' => $paidBy,
                'paid_to' => $paidTo,
                'payment_status' => 'disburse',
                'amount' => $order['discount_amount'],
                'transaction_type' => 'expense',
            ];
            $this->transaction->create($transaction);
        }
        // free delivery over amount transaction end


        if ($order['payment_method'] == 'cash_on_delivery' || $order['payment_method'] == 'offline_payment') {
            $transaction = [
                'transaction_id' => getUniqueId(),
                'customer_id' => $order['customer_id'],
                'seller_id' => $order['seller_id'],
                'seller_is' => $order['seller_is'],
                'order_id' => $order['id'],
                'order_amount' => $orderAmount,
                'seller_amount' => $orderAmount - $commission,
                'admin_commission' => $commission,
                'received_by' => $receivedBy,
                'status' => 'disburse',
                'delivery_charge' => $order['shipping_cost'] - ($order['is_shipping_free'] ? $order['extra_discount'] : 0),
                'tax' => $orderSummary['total_tax'],
                'delivered_by' => $receivedBy,
                'payment_method' => $order['payment_method'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $this->orderTransaction->create($transaction);

            $wallet = $this->adminWallet->where('admin_id', 1)->first();
            $wallet->commission_earned += $commission;
            ($shippingModel == 'inhouse_shipping' && !$order['is_shipping_free']) ? $wallet->delivery_charge_earned += $order['shipping_cost'] : null;
            $wallet->save();

            if ($order['seller_is'] == 'admin') {
                $wallet = $this->adminWallet->where('admin_id', 1)->first();
                $wallet->inhouse_earning += $orderAmount;
                ($shippingModel == 'sellerwise_shipping' && !$order['is_shipping_free']) ? $wallet->delivery_charge_earned += $order['shipping_cost'] : null;

                $wallet->total_tax_collected += $orderSummary['total_tax'];
                $wallet->save();
            } else {
                $wallet = $this->sellerWallet->where('seller_id', $order['seller_id'])->first();
                $wallet->commission_given += $commission;
                $wallet->total_tax_collected += $orderSummary['total_tax'];

                if ($shippingModel == 'sellerwise_shipping') {
                    !$order['is_shipping_free'] ? $wallet->delivery_charge_earned += $order['shipping_cost'] : null;
                    $wallet->collected_cash += $order['order_amount']; //total order amount
                } else {
                    $wallet->total_earning += ($orderAmount - $commission) + $orderSummary['total_tax'];
                }

                $wallet->save();
            }
        } else {
            $transaction = $this->orderTransaction->where(['order_id' => $order['id']])->first();
            $transaction->status = 'disburse';
            $transaction->save();

            $wallet = $this->adminWallet->where('admin_id', 1)->first();
            $wallet->commission_earned += $commission;
            $wallet->pending_amount -= $order['order_amount'];
            ($shippingModel == 'inhouse_shipping' && !$order['is_shipping_free']) ? $wallet->delivery_charge_earned += $order['shipping_cost'] : null;
            $wallet->save();

            if ($order['seller_is'] == 'admin') {
                $wallet = $this->adminWallet->where('admin_id', 1)->first();
                $wallet->inhouse_earning += $orderAmount;
                ($shippingModel == 'sellerwise_shipping' && !$order['is_shipping_free']) ? $wallet->delivery_charge_earned += $order['shipping_cost'] : null;
                $wallet->total_tax_collected += $orderSummary['total_tax'];
                $wallet->save();
            } else {
                $wallet = $this->sellerWallet->where('seller_id', $order['seller_id'])->first();
                $wallet->commission_given += $commission;

                if ($shippingModel == 'sellerwise_shipping') {
                    !$order['is_shipping_free'] ? $wallet->delivery_charge_earned += $order['shipping_cost'] : null;
                    $wallet->total_earning += ($orderAmount - $commission) + $orderSummary['total_tax'] + $order['shipping_cost'];
                } else {
                    $wallet->total_earning += ($orderAmount - $commission) + $orderSummary['total_tax'];
                }

                $wallet->total_tax_collected += $orderSummary['total_tax'];
                $wallet->save();
            }
        }

        return true;
    }
    public function getListWhereBetween(array $filters = [], string $selectColumn = null, string $whereBetween = null, array $whereBetweenFilters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->order->with($relations)->where($filters)
            ->when($selectColumn == 'order_amount', function ($query) {
                $query->select(
                    DB::raw('IFNULL(sum(order_amount),0) as sums'),
                    DB::raw('YEAR(created_at) year, MONTH(created_at) month,DAY(created_at) day,DAYNAME(created_at) day_of_week')
                );
            })
            ->whereBetween($whereBetween, $whereBetweenFilters)
            ->groupby('year', 'month','day_of_week')
            ->get();
    }

    public function getTopCustomerList(array $filters = [] ,array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->order
            ->with($relations)
            ->where($filters)
            ->select('customer_id', DB::raw('COUNT(customer_id) as count'))
            ->whereHas('customer', function ($query) {
                $query->where('id', '!=', 0);
            })
            ->groupBy('customer_id')
            ->orderBy("count", 'desc');

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }
    public function getTopVendorListByOrderReceived(array $filters = [] ,array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->order
            ->with($relations)
            ->whereHas('seller', function ($query) {
                return $query;
            })
            ->where('seller_is', 'seller')
            ->select('seller_id', DB::raw('COUNT(id) as count'))
            ->groupBy('seller_id')
            ->orderBy("count", 'desc');

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }
}
