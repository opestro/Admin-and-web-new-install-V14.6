<?php

namespace App\Http\Controllers\Admin\Order;

use App\Contracts\Repositories\AdminWalletRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\LoyaltyPointTransactionRepositoryInterface;
use App\Contracts\Repositories\OrderDetailRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\RefundRequestRepositoryInterface;
use App\Contracts\Repositories\RefundStatusRepositoryInterface;
use App\Contracts\Repositories\RefundTransactionRepositoryInterface;
use App\Contracts\Repositories\VendorWalletRepositoryInterface;
use App\Enums\ViewPaths\Admin\RefundRequest;
use App\Enums\ExportFileNames\Admin\RefundRequest as RefundRequestExportFile;
use App\Events\RefundEvent;
use App\Exports\RefundRequestExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\RefundStatusRequest;
use App\Models\RefundStatus;
use App\Services\RefundStatusService;
use App\Services\RefundTransactionService;
use App\Traits\CustomerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RefundController extends BaseController
{
    use CustomerTrait;

    public function __construct(
        private readonly RefundRequestRepositoryInterface           $refundRequestRepo,
        private readonly CustomerRepositoryInterface                $customerRepo,
        private readonly OrderRepositoryInterface                   $orderRepo,
        private readonly OrderDetailRepositoryInterface             $orderDetailRepo,
        private readonly AdminWalletRepositoryInterface             $adminWalletRepo,
        private readonly VendorWalletRepositoryInterface            $vendorWalletRepo,
        private readonly RefundStatusRepositoryInterface            $refundStatusRepos,
        private readonly RefundTransactionRepositoryInterface       $refundTransactionRepo,
        private readonly LoyaltyPointTransactionRepositoryInterface $loyaltyPointTransactionRepo
    )
    {
    }

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getListView(request: $request, status: $type);
    }

    public function getListView(Request $request, $status): View
    {
        $refundList = $this->refundRequestRepo->getListWhereHas(
            orderBy: ['id' => 'desc'],
            searchValue: $request['searchValue'],
            filters: ['status' => $status],
            whereHas: 'order',
            whereHasFilters: ['seller_is' => $request['type']],
            relations: ['order', 'order.seller', 'order.deliveryMan', 'product'],
            dataLimit: getWebConfig('pagination_limit'),
        );
        return view(RefundRequest::LIST[VIEW], compact('refundList','status'));
    }

    public function getDetailsView($id): View
    {
        $refund = $this->refundRequestRepo->getFirstWhere(params: ['id' => $id], relations: ['order.details'],);
        $order = $refund->order;
        $totalProductPrice = 0;
        foreach ($order->details as $key => $or_d) {
            $totalProductPrice += ($or_d->qty * $or_d->price) + $or_d->tax - $or_d->discount;
        }
        $subtotal = $refund->orderDetails->price * $refund->orderDetails->qty - $refund->orderDetails->discount + $refund->orderDetails->tax;
        $couponDiscount = ($order->discount_amount * $subtotal) / $totalProductPrice;
        $refundAmount = $subtotal - $couponDiscount;

        $walletStatus = getWebConfig(name: 'wallet_status');
        $walletAddRefund = getWebConfig(name: 'wallet_add_refund');

        return view(RefundRequest::DETAILS[VIEW],
            compact('refund',
                'order',
                'totalProductPrice',
                'subtotal',
                'couponDiscount',
                'refundAmount',
                'walletStatus',
                'walletAddRefund'
            ));
    }

    public function updateRefundStatus(RefundStatusRequest $request, RefundStatusService $refundStatusService, RefundTransactionService $refundTransactionService): JsonResponse
    {
        $refund = $this->refundRequestRepo->getFirstWhere(params: ['id' => $request['id']]);
        if($refund['status'] == 'refunded'){
            return response()->json(['error'=>translate('when_refund_status_refunded').','.translate('then_you_can`t_change_refund_status').'.']);
        }
        $user = $this->customerRepo->getFirstWhere(params: ['id' => $refund['customer_id']]);

        if (!isset($user)) {
            return response()->json(['error'=>translate('this_account_has_been_deleted_you_can_not_modify_the_status').'.']);

        }

        $loyaltyPointStatus = getWebConfig(name: 'loyalty_point_status');
        $loyaltyPoint = $this->countLoyaltyPointForAmount(id: $refund['order_details_id']);

        if ($loyaltyPointStatus == 1) {
            if ($user['loyalty_point'] < $loyaltyPoint && ($request['refund_status'] == 'refunded' || $request['refund_status'] == 'approved')) {
                return response()->json(['error'=>translate('customer_has_not_sufficient_loyalty_point_to_take_refund_for_this_order').'.']);

            }
        }

        $order = $this->orderRepo->getFirstWhere(params: ['id' => $refund['order_id']]);
        if ($request['refund_status'] == 'refunded' && $refund['status'] != 'refunded') {
            if ($order['seller_is'] == 'admin') {
                $adminWallet = $this->adminWalletRepo->getFirstWhere(params: ['admin_id' => $order['seller_id']]);
                $this->adminWalletRepo->updateWhere(params: ['admin_id' => $order['seller_id']], data: ['inhouse_earning' => $adminWallet['inhouse_earning'] - $refund['amount']]);
            } else {
                $sellerWallet = $this->vendorWalletRepo->getFirstWhere(params: ['seller_id' => $order['seller_id']]);
                $this->vendorWalletRepo->updateWhere(params: ['seller_id' => $order['seller_id']], data: ['total_earning' => $sellerWallet['total_earning'] - $refund['amount']]);
            }
            $this->refundTransactionRepo->add(data: $refundTransactionService->getData(request: $request, refund: $refund, order: $order));
        }

        if ($refund['status'] != 'refunded') {
            $orderDetails = $this->orderDetailRepo->getFirstWhere(params: ['id' => $refund['order_details_id']]);
            $dataArray = $refundStatusService->getRefundStatusProcessData(request: $request, orderDetails: $orderDetails, refund: $refund, loyaltyPoint: $loyaltyPoint);

            if ($request['refund_status'] == 'refunded' && $loyaltyPoint > 0 && getWebConfig(name: 'loyalty_point_status') == 1) {
                $this->loyaltyPointTransactionRepo->addLoyaltyPointTransaction(userId: $refund['customer_id'], reference: $refund['order_id'], amount: $loyaltyPoint, transactionType: 'refund_order');
            }

            $this->orderDetailRepo->update(id: $refund['order_details_id'], data: ['refund_request' => $dataArray['orderDetails']['refund_request']]);
            $this->refundRequestRepo->update(id: $request['id'], data: $dataArray['refund']);
            $this->refundStatusRepos->add(data: $dataArray['refundStatus']);

            RefundEvent::dispatch($request['refund_status'], $order);
            return response()->json(['message'=>translate('refund_status_updated').'.']);

        } else {
            return response()->json(['error'=>translate('refunded_status_can_not_be_changed').'.']);

        }
    }

    public function exportList(Request $request, $status): BinaryFileResponse
    {
        $refundList = $this->refundRequestRepo->getListWhereHas(
            orderBy: ['id' => 'desc'],
            searchValue: $request['searchValue'],
            filters: ['status' => $status],
            whereHas: 'order',
            whereHasFilters: ['seller_is' => $request['type']],
            relations: ['order', 'order.seller', 'order.deliveryMan', 'product'],
            dataLimit: 'all',
        );
        return Excel::download(new RefundRequestExport([
            'data-from' => 'admin',
            'refundList' => $refundList,
            'search' => $request['searchValue'],
            'status' => $status,
            'filter_By' => $request->get('type', 'all'),
        ]), RefundRequestExportFile::EXPORT_XLSX);
    }

}
