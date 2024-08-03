<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Enums\ViewPaths\Admin\NotificationSetup;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationSetupController extends BaseController
{
    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return match ($type){
            'customer'=>$this->getCustomerView(),
            'vendor'=>$this->getVendorView(),
            'deliveryMan'=>$this->getDeliveryManView(),
        };
    }
    private function getCustomerView():View
    {
        return view(NotificationSetup::CUSTOMER[VIEW]);
    }
    private function getVendorView():View
    {
        return view(NotificationSetup::VENDOR[VIEW]);
    }
    private function getDeliveryManView():View
    {
        return view(NotificationSetup::DELIVERYMAN[VIEW]);
    }

}
