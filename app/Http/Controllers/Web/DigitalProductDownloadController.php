<?php

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\OrderDetailRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DigitalProductDownloadController extends Controller
{

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly OrderDetailRepositoryInterface $orderDetailRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getListView($request);
    }

    public function getListView(Request $request): View
    {
        $order = $this->orderRepo->getFirstWhere(params: ['id' => $request['order_id']], relations: ['customer', 'orderDetails.product']);
        $orderDetails = $this->orderDetailRepo->getListWhere(
            filters: ['order_id' => $request['order_id']],
            relations: ['order.customer', 'product'],
            dataLimit: 'all'
        );

        if ($order && $order->customer && $order->customer['email'] == $request['email']) {

            $isDigitalProductReadyCount = 0;
            $isDigitalProductExist = 0;
            foreach($orderDetails as $orderDetail) {
                if ($orderDetail->product->product_type == 'digital') {
                    $isDigitalProductExist++;
                }
                if ($orderDetail->product->product_type == 'digital' && $orderDetail->product->digital_product_type == 'ready_after_sell' && $orderDetail->digital_file_after_sell) {
                    $isDigitalProductReadyCount++;
                }

                if ($orderDetail->product->product_type == 'digital' && $orderDetail->product->digital_product_type == 'ready_product') {
                    $isDigitalProductReadyCount++;
                }
            }

            return view(VIEW_FILE_NAMES['digital_product_download'], [
                'order' => $order,
                'orderDetails' => $orderDetails,
                'isDigitalProductExist' => $isDigitalProductExist,
                'isDigitalProductReadyCount' => $isDigitalProductReadyCount,
            ]);
        } else {
            Toastr::warning(translate('invalid_order'));
            return view(VIEW_FILE_NAMES['digital_product_download']);
        }
    }

}
