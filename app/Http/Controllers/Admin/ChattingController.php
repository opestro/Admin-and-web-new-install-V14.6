<?php

namespace App\Http\Controllers\Admin;


use App\Contracts\Repositories\ChattingRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\DeliveryManRepositoryInterface;
use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Enums\ViewPaths\Admin\Chatting;
use App\Events\ChattingEvent;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ChattingRequest;
use App\Services\ChattingService;
use App\Traits\PushNotificationTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ChattingController extends BaseController
{
    use PushNotificationTrait;

    /**
     * @param ChattingRepositoryInterface $chattingRepo
     * @param ShopRepositoryInterface $shopRepo
     * @param ChattingService $chattingService
     * @param DeliveryManRepositoryInterface $deliveryManRepo
     * @param CustomerRepositoryInterface $customerRepo
     */
    public function __construct(
        private readonly ChattingRepositoryInterface $chattingRepo,
        private readonly ShopRepositoryInterface $shopRepo,
        private readonly ChattingService $chattingService,
        private readonly DeliveryManRepositoryInterface $deliveryManRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
    )
    {
    }


    /**
     * @param Request|null $request
     * @param string|array|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     */
    public function index(?Request $request, string|array $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {

        return $this->getListView(type:$type);
    }

    /**
     * @param string|array $type
     * @return View
     */
    public function getListView(string|array $type):View
    {
        $shop = $this->shopRepo->getFirstWhere(params: ['seller_id' => auth('seller')->id()]);
        $adminId = 0;
        if ($type == 'delivery-man') {
            $allChattingUsers = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' =>$adminId],
                whereNotNull: ['delivery_man_id','admin_id'],
                relations: ['deliveryMan'],
                dataLimit: 'all'
            )->unique('delivery_man_id');

            if (count($allChattingUsers) > 0) {
                $lastChatUser = $allChattingUsers[0]->deliveryMan;
                $this->chattingRepo->updateAllWhere(
                    params: ['admin_id' => $adminId, 'delivery_man_id' => $lastChatUser['id']],
                    data: ['seen_by_admin' => 1]
                );

                $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                    orderBy: ['created_at' => 'DESC'],
                    filters: ['admin_id' =>$adminId, 'delivery_man_id'=>$lastChatUser->id],
                    whereNotNull: ['delivery_man_id','admin_id'],
                    relations: ['deliveryMan'],
                    dataLimit: 'all'
                );

                return view(Chatting::INDEX[VIEW], [
                    'userType' => $type,
                    'allChattingUsers' => $allChattingUsers,
                    'lastChatUser' => $lastChatUser,
                    'chattingMessages' => $chattingMessages,
                ]);
            }
        } elseif ($type == 'customer') {
            $allChattingUsers = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' =>$adminId],
                whereNotNull: ['user_id','admin_id'],
                relations: ['customer'],
                dataLimit: 'all'
            )->unique('user_id');

            if (count($allChattingUsers) > 0) {
                $lastChatUser = $allChattingUsers[0]->customer;
                $this->chattingRepo->updateAllWhere(
                    params: ['admin_id' => $adminId, 'user_id' => $lastChatUser['id']],
                    data: ['seen_by_admin' => 1]
                );

                $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                    orderBy: ['created_at' => 'DESC'],
                    filters: ['admin_id' =>$adminId, 'user_id'=>$lastChatUser->id],
                    whereNotNull: ['user_id','admin_id'],
                    relations: ['customer'],
                    dataLimit: 'all'
                );
                return view(Chatting::INDEX[VIEW], [
                    'userType' => $type,
                    'allChattingUsers' => $allChattingUsers,
                    'lastChatUser' => $lastChatUser,
                    'chattingMessages' => $chattingMessages,
                ]);
            }
        }
        return view(Chatting::INDEX[VIEW], compact('shop'));

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getMessageByUser(Request $request): JsonResponse
    {
        $adminId = 0;
        $data = [];
        if ($request->has(key: 'delivery_man_id')) {
            $getUser = $this->deliveryManRepo->getFirstWhere(params: ['id' => $request['delivery_man_id']]);
            $this->chattingRepo->updateAllWhere(
                params: ['admin_id' => $adminId, 'delivery_man_id' => $request['delivery_man_id']],
                data: ['seen_by_admin' => 1]);

            $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => $adminId, 'delivery_man_id' => $request['delivery_man_id']],
                whereNotNull: ['delivery_man_id', 'admin_id'],
                dataLimit: 'all'
            );
            $data = self::getRenderMessagesView(user: $getUser, message: $chattingMessages, type: 'delivery_man');
        } elseif ($request->has(key: 'user_id')) {
            $getUser = $this->customerRepo->getFirstWhere(params: ['id' => $request['user_id']]);
            $this->chattingRepo->updateAllWhere(
                params: ['admin_id' => $adminId, 'user_id' => $request['user_id']],
                data: ['seen_by_admin' => 1]
            );

            $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => $adminId, 'user_id' => $request['user_id']],
                whereNotNull: ['user_id', 'admin_id'],
                dataLimit: 'all'
            );
            $data = self::getRenderMessagesView(user: $getUser, message: $chattingMessages, type: 'customer');
        }
        return response()->json($data);
    }

    /**
     * @param ChattingRequest $request
     * @return JsonResponse
     */
    public function addAdminMessage(ChattingRequest $request):JsonResponse
    {
        $data = [];
        $messageForm = (object)[
            'f_name'=>'admin',
            'shop'=> [
                'name'=> getWebConfig(name: 'company_name')
            ]
        ];
        if ($request->has(key: 'delivery_man_id')) {
            $this->chattingRepo->add(
                data: $this->chattingService->addChattingData(
                    request: $request,
                    type:'delivery-man',
                )
            );
            $deliveryMan = $this->deliveryManRepo->getFirstWhere(params: ['id' => $request['delivery_man_id']]);
            ChattingEvent::dispatch('message_from_admin', 'delivery_man', $deliveryMan, $messageForm);

            $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => 0, 'delivery_man_id' => $request['delivery_man_id']],
                whereNotNull: ['delivery_man_id', 'admin_id'],
                dataLimit: 'all'
            );
            $data = self::getRenderMessagesView(user: $deliveryMan, message: $chattingMessages, type: 'delivery_man');
        } elseif ($request->has(key: 'user_id')) {
            $this->chattingRepo->add(
                data: $this->chattingService->addChattingData(
                    request: $request,
                    type:'customer',
                )
            );
            $customer = $this->customerRepo->getFirstWhere(params: ['id' => $request['user_id']]);
            ChattingEvent::dispatch('message_from_admin', 'customer', $customer, $messageForm);

            $chattingMessages = $this->chattingRepo->getListWhereNotNull(
                orderBy: ['created_at' => 'DESC'],
                filters: ['admin_id' => 0, 'user_id' => $request['user_id']],
                whereNotNull: ['user_id', 'admin_id'],
                dataLimit: 'all'
            );
            $data = self::getRenderMessagesView(user: $customer, message: $chattingMessages, type: 'customer');
        }
        return response()->json($data);
    }

    /**
     * @param string $tableName
     * @param string $orderBy
     * @param string|int|null $id
     * @return Collection
     */
    protected function getChatList(string $tableName, string $orderBy, string|int $id = null) :Collection
    {
        $adminId = 0;
        $columnName = $tableName == 'users' ? 'user_id' : 'delivery_man_id';
        $filters = isset($id) ? ['chattings.admin_id' => $adminId, $columnName => $id] : ['chattings.admin_id' => $adminId];
        return $this->chattingRepo->getListBySelectWhere(
            joinColumn: [$tableName, $tableName . '.id', '=', 'chattings.' . $columnName],
            select: ['chattings.*', $tableName . '.f_name', $tableName . '.l_name', $tableName . '.image', $tableName . '.country_code', $tableName . '.phone'],
            filters: $filters,
            orderBy: ['chattings.created_at' => $orderBy],
        );
    }

    /**
     * @param object $user
     * @param object $message
     * @param string $type
     * @return array
     */
    protected function getRenderMessagesView(object $user, object $message, string $type): array
    {
        $userData = ['name' => $user['f_name'].' '.$user['l_name'],'phone' => $user['country_code'].$user['phone']];

        if ($type == 'customer') {
            $userData['image'] = getValidImage(path: 'storage/app/public/profile/' . ($user['image']), type: 'backend-profile');
        }else {
            $userData['image'] = getValidImage(path: 'storage/app/public/delivery-man/' . ($user['image']), type: 'backend-profile');
        }

        return [
            'userData' => $userData,
            'chattingMessages' => view('admin-views.chatting.messages', [
                'lastChatUser' => $user,
                'userType' => $type,
                'chattingMessages' => $message
            ])->render(),
        ];
    }

    public function getNewNotification(): JsonResponse
    {
        $chatting = $this->chattingRepo->getListWhereNotNull(
            filters: ['admin_id' => 0, 'seen_by_admin' => 0, 'notification_receiver' => 'admin', 'seen_notification' => 0],
            whereNotNull: ['admin_id'],
        )->count();

        $this->chattingRepo->updateListWhereNotNull(
            filters: ['admin_id' => 0, 'seen_by_admin' => 0, 'notification_receiver' => 'admin', 'seen_notification' => 0],
            whereNotNull: ['admin_id'],
            data: ['seen_notification' => 1]
        );

        return response()->json([
            'newMessagesExist' => $chatting,
            'message' => $chatting > 1 ? $chatting .' '.translate('New_Message') : translate('New_Message'),
        ]);
    }

}
