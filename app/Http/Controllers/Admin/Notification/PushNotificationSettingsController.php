<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\NotificationMessageRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ViewPaths\Admin\PushNotification;
use App\Http\Controllers\BaseController;
use App\Services\PushNotificationService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PushNotificationSettingsController extends BaseController
{

    /**
     * @param BusinessSettingRepositoryInterface $businessSettingRepo
     * @param NotificationMessageRepositoryInterface $notificationMessageRepo
     * @param PushNotificationService $pushNotificationService
     * @param TranslationRepositoryInterface $translationRepo
     */
    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
        private readonly NotificationMessageRepositoryInterface $notificationMessageRepo,
        private readonly PushNotificationService $pushNotificationService,
        private readonly TranslationRepositoryInterface $translationRepo,
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
        return $this->getView();
    }

    /**
     * @return View
     */
    public function getView(): View
    {
        $customerMessages = $this->getPushNotificationMessageData(userType: 'customer');
        $vendorMessages = $this->getPushNotificationMessageData(userType: 'seller');
        $deliveryManMessages = $this->getPushNotificationMessageData(userType: 'delivery_man');
        $language = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'pnc_language']);
        return view(PushNotification::INDEX[VIEW],compact('customerMessages','vendorMessages','deliveryManMessages','language'));
    }

    /**
     * @param $userType
     * @return Collection
     */
    protected function getPushNotificationMessageData($userType):Collection
    {
        $pushNotificationMessages = $this->notificationMessageRepo->getListWhere(filters:['user_type'=>$userType]);
        $pushNotificationMessagesKeyArray = $this->pushNotificationService->getMessageKeyData(userType: $userType);
        foreach ($pushNotificationMessagesKeyArray as $value ){
            $checkKey = $pushNotificationMessages->where('key',$value)->first();
            if($checkKey === null){
                $this->notificationMessageRepo->add(
                    data: $this->pushNotificationService->getAddData(userType: $userType, value: $value)
                );
            }
        }
        foreach ($pushNotificationMessages as $value ){
            if (!in_array($value['key'], $pushNotificationMessagesKeyArray)) {
                $this->notificationMessageRepo->delete(params: ['id' => $value['id']]);
            }
        }
        return $this->notificationMessageRepo->getListWhere(filters:['user_type'=>$userType]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePushNotificationMessage(Request $request):RedirectResponse
    {
        $pushNotificationMessages = $this->notificationMessageRepo->getListWhere(filters:['user_type'=>$request['type']]);
        foreach($pushNotificationMessages as $pushNotificationMessage){
            $message = 'message'.$pushNotificationMessage['id'];
            $status = 'status'.$pushNotificationMessage['id'];
            $lang = 'lang'.$pushNotificationMessage['id'];
            $this->notificationMessageRepo->update(
                id:$pushNotificationMessage['id'],
                data: $this->pushNotificationService->getUpdateData(
                    request: $request,
                    message: $message,
                    status: $status,
                    lang: $lang
                )
            );
            foreach ($request->$lang as $index => $value) {
                if ($request->$message[$index] && $value != 'en') {
                    $this->translationRepo->updateData(
                        model: 'App\Models\NotificationMessage',
                        id: $pushNotificationMessage['id'],
                        lang: $value,
                        key:$pushNotificationMessage['key'] ,
                        value: $request->$message[$index]
                    );
                }
            }
        }
        Toastr::success(translate('update_successfully'));
        return redirect()->back();
    }

    /**
     * @return View
     */
    public function getFirebaseConfigurationView():View
    {
        $pushNotificationKey = $this->businessSettingRepo->getFirstWhere(params:['type'=>'push_notification_key'])->value;
        $projectId = $this->businessSettingRepo->getFirstWhere(params:['type'=>'fcm_project_id'])->value;
        return view(PushNotification::FIREBASE_CONFIGURATION[VIEW],compact('pushNotificationKey','projectId'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function getFirebaseConfigurationUpdate(Request $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'fcm_project_id', value: $request['fcm_project_id']);
        $this->businessSettingRepo->updateOrInsert(type: 'push_notification_key', value: $request['push_notification_key']);
        Toastr::success(translate('settings_updated'));
        return back();
    }

}
