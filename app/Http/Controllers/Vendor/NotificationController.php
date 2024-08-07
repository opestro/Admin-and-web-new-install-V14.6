<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Repositories\NotificationRepositoryInterface;
use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Enums\ViewPaths\Vendor\Notification;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Vendor\NotificationModalViewRequest;
use App\Repositories\NotificationSeenRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationController extends BaseController
{
    /**
     * @param ShopRepositoryInterface $shopRepo
     * @param NotificationRepositoryInterface $notificationRepo
     * @param NotificationSeenRepository $notificationSeenRepo
     */
    public function __construct(
        private readonly ShopRepositoryInterface $shopRepo,
        private readonly NotificationRepositoryInterface $notificationRepo,
        private readonly NotificationSeenRepository $notificationSeenRepo,
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
        return null;
    }

    /**
     * @param NotificationModalViewRequest $request
     * @return JsonResponse
     */
    public function getNotificationModalView(NotificationModalViewRequest $request ):JsonResponse
    {
        $shop = $this->shopRepo->getFirstWhere(params:['seller_id'=> auth('seller')->id()]);
        $companyName = getWebConfig('company_name') ?? '';

        $notificationSeenId = $this->notificationSeenRepo->getFirstWhere(params:['seller_id' => auth('seller')->id(), 'notification_id' => $request['id']]);

        $this->notificationSeenRepo->update(id: $notificationSeenId['id'],data: [ 'created_at' => now()]);

        $data = $this->notificationRepo->getFirstWhere(params:['id' => $request['id']]);

        $notification = $this->notificationRepo->getListWhereBetween(params:[auth('seller')->user()->created_at, now()],filters: ['sent_to'=>'seller'],relations: 'notificationSeenBy');

        $notificationCount = count($notification);
        return response()->json([
            'notification_count' => $notificationCount,
            'view' => view(Notification::INDEX[VIEW], compact('shop', 'companyName', 'data'))->render(),
        ]);
    }
}
