<?php

namespace App\Http\Controllers\Admin\ThirdParty;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Enums\GlobalConstant;
use App\Enums\ViewPaths\Admin\PaymentMethod;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\PaymentMethodUpdateRequest;
use App\Services\SettingService;
use App\Traits\Processor;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentMethodController extends BaseController
{
    use Processor;

    public function __construct(
        private readonly SettingRepositoryInterface         $settingRepo,
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
        private readonly SettingService                     $settingService,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getListView();
    }

    public function getListView(): View
    {
        $paymentPublishedStatus = config('get_payment_publish_status') ?? 0;
        $paymentGatewayPublishedStatus = isset($paymentPublishedStatus[0]['is_published']) ? $paymentPublishedStatus[0]['is_published'] : 0;
        $paymentGatewaysList = $this->settingRepo->getListWhereIn(
            whereInFilters: ['settings_type' => ['payment_config'], 'key_name' => GlobalConstant::DEFAULT_PAYMENT_GATEWAYS],
            dataLimit: 'all',
        );

        $paymentGatewaysList = $paymentGatewaysList->sortBy(function ($item) {
            return count($item['live_values']);
        })->values()->all();

        $paymentUrl = $this->settingService->getVacationData(type: 'payment_setup');
        return view(PaymentMethod::LIST[VIEW], [
            'paymentGatewaysList' => $paymentGatewaysList,
            'paymentGatewayPublishedStatus' => $paymentGatewayPublishedStatus,
            'paymentUrl' => $paymentUrl,
            'cashOnDelivery' => getWebConfig(name: 'cash_on_delivery'),
            'digitalPayment' => getWebConfig(name: 'digital_payment'),
            'offlinePayment' => getWebConfig(name: 'offline_payment'),
        ]);
    }
    public function getPaymentOptionView():View
    {
        return view(PaymentMethod::PAYMENT_OPTION[VIEW], [
            'cashOnDelivery' => getWebConfig(name: 'cash_on_delivery'),
            'digitalPayment' => getWebConfig(name: 'digital_payment'),
            'offlinePayment' => getWebConfig(name: 'offline_payment'),
        ]);
    }

    public function updatePaymentOption(Request $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'cash_on_delivery', value: json_encode(['status' => $request->get('cash_on_delivery', 0)]));
        $this->businessSettingRepo->updateOrInsert(type: 'digital_payment', value: json_encode(['status' => $request->get('digital_payment', 0)]));
        $this->businessSettingRepo->updateOrInsert(type: 'offline_payment', value: json_encode(['status' => $request->get('offline_payment', 0)]));
        Toastr::success(translate('successfully_updated'));
        return back();
    }

    public function UpdatePaymentConfig(PaymentMethodUpdateRequest $request): RedirectResponse
    {
        collect(['status'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $settings = $this->settingRepo->getFirstWhere(params: ['key_name'=>$request['gateway'], 'settings_type'=>'payment_config']);
        $additionalDataImage = $settings['additional_data'] != null ? json_decode($settings['additional_data']) : null;
        if ($request->has('gateway_image')) {
            $gatewayImage = $this->file_uploader('payment_modules/gateway_image/', 'png', $request['gateway_image'], $additionalDataImage != null ? $additionalDataImage->gateway_image : '');
        } else {
            $gatewayImage = $additionalDataImage != null ? $additionalDataImage->gateway_image : '';
        }
        $request->validate(['gateway_title' => 'required']);

        $this->settingRepo->updateOrInsert(params: ['key_name' => $request['gateway'], 'settings_type' => 'payment_config'], data: [
            'key_name' => $request['gateway'],
            'live_values' => $request->validated(),
            'test_values' => $request->validated(),
            'settings_type' => 'payment_config',
            'mode' => $request['mode'],
            'is_active' => $request['status'] ?? 0,
            'additional_data' => json_encode(['gateway_title' => $request['gateway_title'],'gateway_image' => $gatewayImage]),
        ]);

        Toastr::success(GATEWAYS_DEFAULT_UPDATE_200['message']);
        return back();
    }
}
