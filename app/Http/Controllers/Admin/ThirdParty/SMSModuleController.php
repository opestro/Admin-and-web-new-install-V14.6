<?php

namespace App\Http\Controllers\Admin\ThirdParty;

use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Enums\GlobalConstant;
use App\Enums\ViewPaths\Admin\SMSModule;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SMSModuleUpdateRequest;
use App\Services\SettingService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SMSModuleController extends BaseController
{
    public function __construct(
        private readonly SettingRepositoryInterface $settingRepo,
        private readonly SettingService             $settingService,
    )
    {
    }

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getView();
    }

    public function getView(): View
    {
        $paymentPublishedStatus = config('get_payment_publish_status') ?? 0;
        $paymentGatewayPublishedStatus = isset($paymentPublishedStatus[0]['is_published']) ? $paymentPublishedStatus[0]['is_published'] : 0;
        $smsGatewaysList = $this->settingRepo->getListWhereIn(
            whereInFilters: ['settings_type' => ['sms_config'], 'key_name' => GlobalConstant::DEFAULT_SMS_GATEWAYS],
            dataLimit: 'all',
        );

        $smsGateways = $smsGatewaysList->sortBy(function ($item) {
            return count($item['live_values']);
        })->values()->all();

        $paymentUrl = $this->settingService->getVacationData(type: 'sms_setup');
        return view(SMSModule::VIEW[VIEW], compact('smsGateways', 'paymentGatewayPublishedStatus', 'paymentUrl'));
    }

    public function update(SMSModuleUpdateRequest $request, SettingService $settingService): RedirectResponse
    {
        $service = $settingService->getSMSModuleValidationData(request: $request);
        $this->settingRepo->updateOrInsert(params: ['key_name' => $request['gateway'], 'settings_type' => 'sms_config'], data: [
            'key_name' => $request['gateway'],
            'live_values' => $service,
            'test_values' => $service,
            'settings_type' => 'sms_config',
            'mode' => $request['mode'],
            'is_active' => $request['status'],
        ]);

        if ($request['status'] == 1) {
            foreach (['releans', 'twilio', 'nexmo', '2factor', 'msg91', 'hubtel', 'paradox', 'signal_wire', '019_sms', 'viatech', 'global_sms', 'akandit_sms', 'sms_to', 'alphanet_sms'] as $gateway) {
                $keep = $this->settingRepo->getFirstWhere(params: ['key_name' => $gateway, 'settings_type' => 'sms_config']);
                if (isset($keep)) {
                    $hold = $keep['live_values'];
                    if ($request['gateway'] != $gateway) {
                        $hold['status'] = 0;
                        $this->settingRepo->updateWhere(params: ['key_name' => $gateway, 'settings_type' => 'sms_config'], data: [
                            'live_values' => $hold,
                            'test_values' => $hold,
                            'is_active' => 0,
                        ]);
                    }
                }
            }
        }

        Toastr::success(GATEWAYS_DEFAULT_UPDATE_200['message']);
        return back();
    }
}
