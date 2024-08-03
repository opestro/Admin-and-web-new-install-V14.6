<?php

namespace App\Http\Controllers\Vendor\DeliveryMan;

use App\Contracts\Repositories\DeliveryManRepositoryInterface;
use App\Contracts\Repositories\DeliveryManTransactionRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Enums\ViewPaths\Vendor\Dashboard;
use App\Enums\ViewPaths\Vendor\DeliveryManWallet;
use App\Events\CashCollectEvent;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Vendor\DeliveryManWalletRequest;
use App\Repositories\DeliveryManWalletRepository;
use App\Repositories\OrderStatusHistoryRepository;
use App\Services\DeliveryManService;
use App\Services\DeliveryManTransactionService;
use App\Services\DeliveryManWalletService;
use App\Traits\CommonTrait;
use App\Traits\PushNotificationTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeliveryManWalletController extends BaseController
{
    use CommonTrait;
    use PushNotificationTrait;

    /**
     * @param DeliveryManRepositoryInterface $deliveryManRepo
     * @param DeliveryManWalletRepository $deliveryManWalletRepo
     * @param DeliveryManWalletService $deliveryManWalletService
     * @param DeliveryManTransactionRepositoryInterface $deliveryManTransactionRepo
     * @param DeliveryManTransactionService $deliveryManTransactionService
     * @param OrderRepositoryInterface $orderRepo
     * @param OrderStatusHistoryRepository $orderStatusHistoryRepo
     * @param DeliveryManService $deliveryManService
     */
    public function __construct(
        private readonly DeliveryManRepositoryInterface $deliveryManRepo,
        private readonly DeliveryManWalletRepository    $deliveryManWalletRepo,
        private readonly DeliveryManWalletService       $deliveryManWalletService,
        private readonly DeliveryManTransactionRepositoryInterface  $deliveryManTransactionRepo,
        private readonly DeliveryManTransactionService  $deliveryManTransactionService,
        private readonly OrderRepositoryInterface       $orderRepo,
        private readonly OrderStatusHistoryRepository   $orderStatusHistoryRepo,
        private readonly DeliveryManService             $deliveryManService,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|int|null $type
     * @return \Illuminate\Contracts\View\View|Collection|LengthAwarePaginator|callable|\Illuminate\Http\RedirectResponse|null
     */
    public function index(?Request $request, string|int $type = null): \Illuminate\Contracts\View\View|Collection|LengthAwarePaginator|null|callable|\Illuminate\Http\RedirectResponse
    {
        return $this->getView(id: $type);
    }


    /**
     * @param string|int $id
     * @return RedirectResponse|View
     */
    public function getView(string|int $id): RedirectResponse|View
    {
        if (!$this->deliveryManService->checkConditions()){
            return redirect()->route(Dashboard::INDEX[ROUTE]);
        }
        $deliveryMan = $this->deliveryManRepo->getFirstWhere(params: ['seller_id' => auth('seller')->id(), 'id' => $id], relations: ['wallet']);
        if (isset($delivery_man->wallet)) {
            $withdrawAbleBalance = $this->delivery_man_withdrawable_balance($deliveryMan['id']);
        } else {
            $withdrawAbleBalance = null;
        }
        return view(DeliveryManWallet::INDEX[VIEW], compact('deliveryMan', 'withdrawAbleBalance'));
    }

    /**
     * @param Request $request
     * @param string|int $id
     * @return RedirectResponse|View
     */
    public function getOrderHistory(Request $request, string|int $id): RedirectResponse|View
    {
        if (!$this->deliveryManService->checkConditions()){
            return redirect()->route(Dashboard::INDEX[ROUTE]);
        }
        $searchValue = $request['search'];
        $deliveryMan = $this->deliveryManRepo->getFirstWhere(params: ['seller_id' => auth('seller')->id(), 'id' => $id], relations: ['wallet']);
        $orders = $this->orderRepo->getDeliveryManOrderListWhere(addedBy: 'seller', searchValue: $searchValue, filters: ['delivery_man_id' => $id], dataLimit: getWebConfig(name: 'pagination_limit'));
        return view(DeliveryManWallet::ORDER_HISTORY[VIEW], compact('deliveryMan', 'orders', 'searchValue'));
    }

    /**
     * @param string|int $order
     * @return View
     */
    public function getOrderStatusHistory(string|int $order): View
    {
        $histories = $this->orderStatusHistoryRepo->getListWhere(filters: ['order_id' => $order]);
        return view(DeliveryManWallet::ORDER_STATUS_HISTORY[VIEW], compact('histories'));
    }

    /**
     * @param Request $request
     * @param string|int $id
     * @return RedirectResponse|View
     */
    public function getEarningListView(Request $request, string|int $id): RedirectResponse|View
    {
        if (!$this->deliveryManService->checkConditions()){
            return redirect()->route(Dashboard::INDEX[ROUTE]);
        }
        $vendorId = auth('seller')->id();
        $searchValue = $request['search'];
        $deliveryMan = $this->deliveryManRepo->getFirstWhere(params: ['seller_id' => auth('seller')->id(), 'id' => $id], relations: ['wallet']);
        $orders = $this->orderRepo->getListWhere(
            searchValue: $searchValue,
            filters: [
                'delivery_man_id'=>  $id,
                'seller_id'=>  $vendorId,
                ],
            dataLimit: getWebConfig(name: 'pagination_limit')
        );
        $totalEarn = self::delivery_man_total_earn($id);
        $withdrawableBalance = self::delivery_man_withdrawable_balance($id);
        return view(DeliveryManWallet::EARNING[VIEW], compact('deliveryMan', 'orders', 'totalEarn', 'withdrawableBalance', 'searchValue'));
    }

    /**
     * @param string|int $id
     * @return View
     *
     */
    public function getCashCollectView(string|int $id): View
    {
        $vendorId = auth('seller')->id();
        $deliveryMan = $this->deliveryManRepo->getFirstWhere(
            params: ['seller_id' => $vendorId, 'id' => $id],
            relations: ['wallet']
        );
        $transactions = $this->deliveryManTransactionRepo->getListWhere(
            orderBy:['id'=>'desc'],
            filters: ['delivery_man_id' =>$deliveryMan['id']],
            dataLimit:getWebConfig(name: 'pagination_limit'),
        );
        return view(DeliveryManWallet::CASH_COLLECT[VIEW], compact('deliveryMan', 'transactions'));
    }

    /**
     * @param DeliveryManWalletRequest $request
     * @param string|int $id
     * @return RedirectResponse
     * @function receiveCashAdd ,receiving cash from delivery man
     */
    public function collectCash(DeliveryManWalletRequest $request, string|int $id): RedirectResponse
    {
        $wallet = $this->deliveryManWalletRepo->getFirstWhere(params: ['delivery_man_id' => $id]);
        if (empty($wallet) || currencyConverter($request->input('amount')) > $wallet['cash_in_hand']) {
            Toastr::warning(translate('receive_amount_can_not_be_more_than_cash_in_hand').'!');
            return redirect()->back();
        }
        $wallet['cash_in_hand'] -= $request->input('amount');
        $amount = $request->amount ?? 0;
        $this->deliveryManTransactionRepo->add(
            $this->deliveryManTransactionService->getDeliveryManTransactionData(
                amount: $amount,
                addedBy: 'seller',
                id: $id,
                transactionType: 'cash_in_hand')
        );
        $deliveryMan = $this->deliveryManRepo->getFirstWhere(params: ['id' => $id]);
        if ($wallet->update()) {
            if (!empty($deliveryMan['fcm_token'])) {
                CashCollectEvent::dispatch('cash_collect_by_seller_message','delivery_man',$deliveryMan['app_language'] ?? getDefaultLanguage(),$amount,$deliveryMan['fcm_token']);
            }
            Toastr::success(translate('amount_receive_successfully') . '!');
            return back();
        }
        Toastr::error(translate('amount_receive_failed') . '!');
        return back();
    }
}
