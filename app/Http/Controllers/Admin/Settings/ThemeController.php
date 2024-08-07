<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\NotificationRepositoryInterface;
use App\Enums\ViewPaths\Admin\ThemeSetup;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ThemeSetupRequest;
use App\Services\ThemeService;
use App\Traits\PushNotificationTrait;
use App\Traits\SettingsTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Throwable;

class ThemeController extends BaseController
{
    use SettingsTrait;
    use PushNotificationTrait;


    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepo,
        private readonly ThemeService $themeService,
    )
    {}

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

    public function getView(): View
    {
        $themes = $this->themeService->getDirectories();
        return view(ThemeSetup::VIEW[VIEW], compact('themes'));
    }

    public function upload(ThemeSetupRequest $request, ThemeService $themeService): JsonResponse
    {
        $data = $themeService->getUploadData(request: $request);
        return response()->json([
            'status' => $data['status'],
            'message'=> $data['message']
        ]);
    }

    public function publish(Request $request, ThemeService $themeService): JsonResponse
    {
        $data = $themeService->getPublishData(request: $request);
        return response()->json($data);
    }

    public function activation(Request $request, ThemeService $themeService): Redirector|RedirectResponse|Application
    {
        $data = $themeService->getActivationData(request: $request);
        $data ? Toastr::success(translate('activated_successfully')) : Toastr::error(translate('activation failed'));
        return back();
    }

    public function delete(Request $request, ThemeService $themeService): JsonResponse
    {
        $data = $themeService->deleteTheme(request: $request);
        return response()->json($data);
    }


    public function notifyAllTheVendors(Request $request, ThemeService $themeService): JsonResponse
    {
        $status = 0;
        $message = translate('Notification_Sent_to_All_Vendors_Fail');

        try {
            $dataArray = $themeService->getNotifySellersData(request: $request);
            $status = 1;
            $message = translate('Notification_Sent_to_All_Vendors');
            $this->notificationRepo->add($dataArray);
            $this->sendPushNotificationToTopic(data: $dataArray, topic: 'six_valley_seller');
        } catch (Throwable $th) {
        }

        return response()->json([
            'status' => $status,
            'message'=> $message,
        ]);
    }
}
