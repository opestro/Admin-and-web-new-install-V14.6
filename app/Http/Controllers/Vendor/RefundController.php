<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\OrderDetailRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\RefundRequestRepositoryInterface;
use App\Contracts\Repositories\RefundStatusRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\ExportFileNames\Admin\RefundRequest as RefundRequestExportFile;
use App\Enums\ViewPaths\Vendor\Refund;
use App\Events\RefundEvent;
use App\Exports\RefundRequestExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Vendor\RefundStatusRequest;
use App\Repositories\VendorRepository;
use App\Services\RefundStatusService;
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
        private readonly RefundRequestRepositoryInterface $refundRequestRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly OrderDetailRepositoryInterface $orderDetailRepo,
        private readonly RefundStatusRepositoryInterface $refundStatusRepo,
        private readonly RefundStatusService $refundStatusService,
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly VendorRepositoryInterface $vendorRepo,

    )
    {

    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     */
    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getList(request:$request ,status:$type);
    }

    /**
     * @param object $request
     * @param string $status
     * @return View
     */
    public function getList(object $request, string $status):View
    {
        $vendorId = auth('seller')->id();
        $searchValue =  $request['search'] ?? null;
        $refundList = $this->refundRequestRepo->getListWhereHas(
            orderBy: ['id'=>'desc'],
            searchValue: $searchValue,
            filters: ['status'=>$status],
            whereHas: 'order',
            whereHasFilters: [ 'seller_is'=>'seller', 'seller_id' => $vendorId],
            dataLimit: getWebConfig('pagination_limit'),

        );
        return view(Refund::INDEX[VIEW],compact('refundList','searchValue'));
    }

    /**
     * @param string|int $id
     * @return View
     */
    public function getDetailsView(string|int $id):View
    {
        $vendorId = auth('seller')->id();
        $refund = $this->refundRequestRepo->getFirstWhereHas(
            params: ['id'=>$id],
            whereHas: 'order',
            whereHasFilters: [ 'seller_is'=>'seller', 'seller_id' => $vendorId],
            relations: ['order.details'],
        );
        $order = $refund->order;
        $totalProductPrice = 0;
        foreach ($order->details as $key => $orderDetails) {
            $totalProductPrice += ($orderDetails->qty * $orderDetails->price) + $orderDetails->tax - $orderDetails->discount;
        }
        $subtotal = $refund->orderDetails->price * $refund->orderDetails->qty - $refund->orderDetails->discount + $refund->orderDetails->tax;
        $couponDiscount = ($order->discount_amount * $subtotal) / $totalProductPrice;
        $refundAmount = $subtotal - $couponDiscount;

        return view(Refund::DETAILS[VIEW],compact('refund', 'order','refundAmount','subtotal','couponDiscount','refundAmount'));
    }

    /**
     * @param RefundStatusRequest $request
     * @return JsonResponse
     */
    public function updateStatus(RefundStatusRequest $request):JsonResponse
    {
        $vendorId = auth('seller')->id();
        $refund = $this->refundRequestRepo->getFirstWhereHas(
            params:['id'=>$request['id']],
            whereHas: 'order',
            whereHasFilters: [ 'seller_is'=>'seller', 'seller_id' => $vendorId],
        );
        if (($request['refund_status'] == 'approved' && $refund['approved_count'] >=2) || $request['refund_status'] == 'rejected' && $refund['denied_count'] >=2){
            return response()->json(['error'=>translate('you_already_changed_').($request['refund_status']=='approved'?'approve' : 'reject').translate('_status_two_times').'!!']);
        }
        $customer = $this->customerRepo->getFirstWhere(params:['id'=>$refund['customer_id']]);
        if(!isset($customer))
        {
            return response()->json(['error'=>translate('this_account_has_been_deleted').','.translate('you_can_not_modify_the_status').'!!']);
        }
        $loyaltyPointStatus = getWebConfig('loyalty_point_status');
        $orderDetails = $this->orderDetailRepo->getFirstWhere(['id' => $refund['order_details_id']]);
        if($loyaltyPointStatus == 1){
            $loyaltyPoint = $this->convertAmountToLoyaltyPoint(orderDetails:$orderDetails);
            if($customer['loyalty_point'] < $loyaltyPoint && $request['refund_status'] == 'approved')
            {
                return response()->json(['error'=>translate('customer_has_not_sufficient_loyalty_point_to_take_refund_for_this_order').'!!']);
            }
        }

        if($refund['change_by'] =='admin'){
            return response()->json(['error'=>translate('refunded_status_can_not_be_changed').'!!'.('admin_already_changed_the_status') .': '.$refund['status'].'!!']);
        }
        if($refund['status'] != 'refunded'){
            $statusMapping = [
                'pending' => 1,
                'approved' => 2,
                'rejected' => 3,
                'refunded' => 4,
            ];
            $this->orderDetailRepo->update(
                id:$orderDetails['id'],
                data: ['refund_request'=>$statusMapping[$request['refund_status']]]
            );
            $this->refundStatusRepo->add($this->refundStatusService->getRefundStatusData(
                request:$request,
                refund: $refund,
                changeBy: 'seller'
            ));
            $this->refundRequestRepo->update(
                id:$refund['id'],
                data: [
                    'status'=> $request['refund_status'],
                    'approved_count' => $request['refund_status'] == 'approved' ? ($refund['approved_count']+1) : $refund['approved_count'],
                    'denied_count' => $request['refund_status'] == 'rejected' ? ($refund['denied_count']+1) : $refund['denied_count'],
                    'rejected_note' => $request['refund_status'] == 'rejected' ? $request['rejected_note'] : null,
                    'approved_note' => $request['refund_status'] == 'approved' ? $request['approved_note'] : null,
                    'change_by'=>'seller',
                    ]
            );
            $order = $this->orderRepo->getFirstWhere(params: ['id'=>$refund['order_id']]);
            RefundEvent::dispatch($request['refund_status'], $order);
            return response()->json(['message'=>translate('refund_status_updated') . '!!']);
        }else {
            return response()->json(['message'=>translate('refunded_status_can_not_be_changed') . '!!']);
        }
    }
    public function exportList(Request $request, $status): BinaryFileResponse
    {
        $vendorId = auth('seller')->id();
        $vendor = $this->vendorRepo->getFirstWhere(params:['id' => $vendorId]);
        $refundList = $this->refundRequestRepo->getListWhereHas(
            orderBy: ['id' => 'desc'],
            searchValue: $request['search'],
            filters: ['status' => $status],
            whereHas: 'order',
            whereHasFilters: [ 'seller_is'=>'seller', 'seller_id' => $vendorId],
            relations: ['order', 'order.seller', 'order.deliveryMan', 'product'],
            dataLimit: 'all',
        );
        return Excel::download(new RefundRequestExport([
            'data-from' => 'vendor',
            'vendor' => $vendor,
            'refundList' => $refundList,
            'search' => $request['search'],
            'status' => $status,
            'filter_By' => $request->get('type', 'all'),
        ]), RefundRequestExportFile::EXPORT_XLSX);
    }
}
