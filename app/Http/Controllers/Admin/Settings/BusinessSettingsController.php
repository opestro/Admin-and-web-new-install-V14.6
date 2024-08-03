<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\CurrencyRepositoryInterface;
use App\Enums\ViewPaths\Admin\BusinessSettings;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\BusinessSettingRequest;
use App\Http\Requests\Admin\LoginSetupRequest;
use App\Http\Requests\Admin\AnalyticsRequest;
use App\Services\BusinessSettingService;
use App\Traits\FileManagerTrait;
use App\Traits\SettingsTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BusinessSettingsController extends BaseController
{

    use SettingsTrait;
    use FileManagerTrait {
        delete as deleteFile;
        update as updateFile;
    }

    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
        private readonly CurrencyRepositoryInterface        $currencyRepo,
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

    public function getView(): View
    {
        $web = $this->businessSettingRepo->getListWhere(dataLimit: 'all');
        $settings = $this->getSettings($web, 'colors');
        $data = json_decode($settings['value'], true);

        $businessSetting = [
            'primary_color' => $data['primary'] ?? '',
            'secondary_color' => $data['secondary'] ?? '',
            'primary_color_light' => $data['primary_light'] ?? '',
            'company_name' => $this->getSettings(object: $web, type: 'company_name')->value ?? '',
            'company_email' => $this->getSettings(object: $web, type: 'company_email')->value ?? '',
            'company_phone' => $this->getSettings(object: $web, type: 'company_phone')->value ?? '',
            'language' => $this->getSettings(object: $web, type: 'language')->value ?? '',
            'web_logo' => $this->getSettings(object: $web, type: 'company_web_logo')->value ?? '',
            'mob_logo' => $this->getSettings(object: $web, type: 'company_mobile_logo')->value ?? '',
            'fav_icon' => $this->getSettings(object: $web, type: 'company_fav_icon')->value ?? '',
            'footer_logo' => $this->getSettings(object: $web, type: 'company_footer_logo')->value ?? '',
            'shop_address' => $this->getSettings(object: $web, type: 'shop_address')->value ?? '',
            'company_copyright_text' => $this->getSettings(object: $web, type: 'company_copyright_text')->value ?? '',
            'system_default_currency' => $this->getSettings(object: $web, type: 'system_default_currency')->value ?? '',
            'currency_symbol_position' => $this->getSettings(object: $web, type: 'currency_symbol_position')->value ?? '',
            'forgot_password_verification' => $this->getSettings(object: $web, type: 'forgot_password_verification')->value ?? '',
            'business_mode' => $this->getSettings(object: $web, type: 'business_mode')->value ?? '',
            'email_verification' => $this->getSettings(object: $web, type: 'email_verification')->value ?? '',
            'otp_verification' => $this->getSettings(object: $web, type: 'otp_verification')->value ?? '',
            'guest_checkout' => $this->getSettings(object: $web, type: 'guest_checkout')->value ?? '',
            'pagination_limit' => $this->getSettings(object: $web, type: 'pagination_limit')->value ?? '',
            'copyright_text' => $this->getSettings(object: $web, type: 'company_copyright_text')->value ?? '',
            'decimal_point_settings' => $this->getSettings(object: $web, type: 'decimal_point_settings')->value ?? 0,
            'maintenance_mode' => $this->getSettings(object: $web, type: 'maintenance_mode')->value ?? '',
            'loader_gif' => $this->getSettings(object: $web, type: 'loader_gif')->value ?? '',
            'default_location' => $this->getSettings(object: $web, type: 'default_location')->value ?? '',
        ];

        $CurrencyList = $this->currencyRepo->getListWhere(dataLimit: 'all');

        return view(BusinessSettings::INDEX[VIEW], [
            'CurrencyList' => $CurrencyList,
            'businessSetting' => $businessSetting,
        ]);
    }

    public function updateSettings(BusinessSettingRequest $request, BusinessSettingService $businessSettingService): RedirectResponse
    {
        if ($request['email_verification'] == 1) {
            $request['phone_verification'] = 0;
        } elseif ($request['phone_verification'] == 1) {
            $request['email_verification'] = 0;
        }
        $this->businessSettingRepo->updateOrInsert(type: 'company_name', value: $request['company_name']);
        $this->businessSettingRepo->updateOrInsert(type: 'company_email', value: $request['company_email']);
        $this->businessSettingRepo->updateOrInsert(type: 'company_phone', value: $request['company_phone']);
        $this->businessSettingRepo->updateOrInsert(type: 'company_copyright_text', value: $request['company_copyright_text']);
        $this->businessSettingRepo->updateOrInsert(type: 'timezone', value: $request['timezone']);
        $this->businessSettingRepo->updateOrInsert(type: 'phone_verification', value: $request['phone_verification']);
        $this->businessSettingRepo->updateOrInsert(type: 'email_verification', value: $request['email_verification']);
        $this->businessSettingRepo->updateOrInsert(type: 'forgot_password_verification', value: $request['forgot_password_verification']);
        $this->businessSettingRepo->updateOrInsert(type: 'decimal_point_settings', value: $request['decimal_point_settings'] ?? 0);
        $this->businessSettingRepo->updateOrInsert(type: 'shop_address', value: $request['shop_address']);
        $this->businessSettingRepo->updateOrInsert(type: 'system_default_currency', value: $request['currency_id']);
        $this->businessSettingRepo->updateOrInsert(type: 'currency_symbol_position', value: $request['currency_symbol_position']);
        $this->businessSettingRepo->updateOrInsert(type: 'business_mode', value: $request['business_mode']);
        $this->businessSettingRepo->updateOrInsert(type: 'country_code', value: $request['country_code']);

        $colors = json_encode(['primary' => $request['primary'], 'secondary' => $request['secondary'], 'primary_light' => $request['primary_light'] ?? '#CFDFFB']);
        $this->businessSettingRepo->updateOrInsert(type: 'colors', value: $colors);

        $defaultLocation = json_encode(['lat' => $request['latitude'], 'lng' => $request['longitude']]);
        $this->businessSettingRepo->updateOrInsert(type: 'default_location', value: $defaultLocation);

        $appAppleStore = json_encode(['status' => $request['app_store_download_status'] ?? 0, 'link' => $request['app_store_download_url']]);
        $this->businessSettingRepo->updateOrInsert(type: 'download_app_apple_stroe', value: $appAppleStore);

        $appGoogleStore = json_encode(['status' => $request['play_store_download_status'] ?? 0, 'link' => $request['play_store_download_url']]);
        $this->businessSettingRepo->updateOrInsert(type: 'download_app_google_stroe', value: $appGoogleStore);

        $webLogo = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'company_web_logo']);
        if ($request->has('company_web_logo')) {
            $webLogoImage = $this->updateFile(dir: 'company/', oldImage: $webLogo['value'], format: 'webp', image: $request->file('company_web_logo'));
            $this->businessSettingRepo->updateWhere(params: ['type'=>'company_web_logo'], data: ['value' => $webLogoImage]);
        }

        $mobileLogo = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'company_mobile_logo']);
        if ($request->has('company_mobile_logo')) {
            $mobileLogoImage = $this->updateFile(dir: 'company/', oldImage: $mobileLogo['value'], format: 'webp', image: $request->file('company_mobile_logo'));
            $this->businessSettingRepo->updateWhere(params: ['type'=>'company_mobile_logo'], data: ['value' => $mobileLogoImage]);
        }

        $webFooterLogo = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'company_footer_logo']);
        if ($request->has('company_footer_logo')) {
            $webFooterLogoImage = $this->updateFile(dir: 'company/', oldImage: $webFooterLogo['value'], format: 'webp', image: $request->file('company_footer_logo'));
            $this->businessSettingRepo->updateWhere(params: ['type'=>'company_footer_logo'], data: ['value' => $webFooterLogoImage]);
        }

        $favIcon = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'company_fav_icon']);
        if ($request->has('company_fav_icon')) {
            $favIconImage = $this->updateFile(dir: 'company/', oldImage: $favIcon['value'], format: 'webp', image: $request->file('company_fav_icon'));
            $this->businessSettingRepo->updateWhere(params: ['type'=>'company_fav_icon'], data: ['value' => $favIconImage]);
        }

        $loaderGif = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'loader_gif']);
        if ($request->has('loader_gif')) {
            $loaderGifImage = $loaderGif ? $this->updateFile(dir: 'company/', oldImage: $loaderGif['value'], format: 'webp', image: $request->file('loader_gif'))
            : $this->upload(dir: 'company/', format: 'webp', image: $request->file('loader_gif'));
            $this->businessSettingRepo->updateOrInsert(type: 'loader_gif', value: $loaderGifImage);
        }

        $language = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'language']);
        $languageArray = $businessSettingService->getLanguageData(request: $request, language: $language);
        $this->businessSettingRepo->updateWhere(params: ['type'=>'language'],data: ['value' => $languageArray]);
        $this->businessSettingRepo->updateOrInsert(type: 'pagination_limit', value: $request['pagination_limit']);
        Toastr::success(translate('updated_successfully'));
        return back();
    }

    public function updateSystemMode(Request $request): JsonResponse
    {
        if (env('APP_MODE') == 'demo') {
            return response()->json([
                'message' => translate('you_can_not_update_this_on_demo_mode'), 401
            ]);
        }

        $this->businessSettingRepo->updateOrInsert(type:'maintenance_mode', value: $request->get('value', 0));
        return response()->json([
            'message' => $request->has('value') && $request['value'] ? translate('Maintenance_is_on') : translate('Maintenance_is_off'),
            'success'=>200
        ]);
    }

    public function getAppSettingsView(): View
    {
        $userApp = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'user_app_version_control']);
        $userAppVersionControl = $userApp ? json_decode($userApp['value'], true) : [];
        $sellerApp = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'seller_app_version_control']);
        $sellerAppVersionControl = $sellerApp ? json_decode($sellerApp['value'], true) : [];
        $deliverymanApp = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'delivery_man_app_version_control']);
        $deliverymanAppVersionControl = $deliverymanApp ? json_decode($deliverymanApp['value'], true) : [];

        return view(BusinessSettings::APP_SETTINGS[VIEW], compact('userAppVersionControl', 'sellerAppVersionControl', 'deliverymanAppVersionControl'));
    }

    public function updateAppSettings(Request $request): RedirectResponse
    {
        if (in_array($request['type'], ['user_app_version_control', 'seller_app_version_control', 'delivery_man_app_version_control'])) {
            $this->businessSettingRepo->updateOrInsert(type: $request['type'], value: json_encode([
                'for_android' => $request['for_android'],
                'for_ios' => $request['for_ios'],
            ]));
        }
        Toastr::success(translate('updated_successfully'));
        return back();
    }

    public function getCookieSettingsView(Request $request): View
    {
        return view(BusinessSettings::COOKIE_SETTINGS[VIEW], [
            'cookieSetting' => getWebConfig(name: 'cookie_setting'),
        ]);
    }

    public function updateCookieSetting(Request $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'cookie_setting', value: json_encode([
            'status' => $request->get('status', 0),
            'cookie_text' => $request['cookie_text'],
        ]));

        Toastr::success(translate('cookie_settings_updated_successfully'));
        return redirect()->back();
    }

    public function getOtpSetupView(): View
    {
        $maximumOtpHit = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'maximum_otp_hit'])->value ?? 0;
        $otpResendTime = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'otp_resend_time'])->value ?? 0;
        $temporaryBlockTime = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'temporary_block_time'])->value ?? 0;
        $maximumLoginHit = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'maximum_login_hit'])->value ?? 0;
        $temporaryLoginBlockTime = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'temporary_login_block_time'])->value ?? 0;
        return view(BusinessSettings::OTP_SETUP[VIEW], compact('maximumOtpHit', 'otpResendTime',
            'temporaryBlockTime', 'maximumLoginHit', 'temporaryLoginBlockTime'));
    }

    public function updateOtpSetup(Request $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'maximum_otp_hit', value: $request['maximum_otp_hit']);
        $this->businessSettingRepo->updateOrInsert(type: 'otp_resend_time', value: $request['otp_resend_time']);
        $this->businessSettingRepo->updateOrInsert(type: 'temporary_block_time', value: $request['temporary_block_time']);
        $this->businessSettingRepo->updateOrInsert(type: 'maximum_login_hit', value: $request['maximum_login_hit']);
        $this->businessSettingRepo->updateOrInsert(type: 'temporary_login_block_time', value: $request['temporary_login_block_time']);
        Toastr::success(translate('Settings_updated'));
        return back();
    }

    public function getAnalyticsView(): View
    {
        return view(BusinessSettings::ANALYTICS_INDEX[VIEW]);
    }

    public function updateAnalytics(Request $request): RedirectResponse
    {
        if ($request['type'] == 'pixel_analytics') {
            $this->businessSettingRepo->updateOrInsert(type: 'pixel_analytics', value: $request['value'] ?? '');
        }

        if ($request['type'] == 'google_tag_manager_id') {
            $this->businessSettingRepo->updateOrInsert(type: 'google_tag_manager_id', value: $request['value'] ?? '');
        }
        Toastr::success(translate('Data_updated'));
        return back();
    }

    public function getLoginSetupView(): View
    {
        return view(BusinessSettings::LOGIN_URL_SETUP[VIEW]);
    }

    public function updateLoginSetupView(LoginSetupRequest $request): RedirectResponse
    {
        $currentUrl = strtolower($request->url);

        if ($request['type'] == 'admin_login_url' || $request->type == 'employee_login_url') {
            $anotherType = ($request['type'] == 'admin_login_url') ? 'employee_login_url' : 'admin_login_url';
            $anotherLoginUrl = $this->businessSettingRepo->getFirstWhere(['type' => $anotherType])->value ?? '';

            if ($anotherLoginUrl != $currentUrl) {
                $this->businessSettingRepo->updateOrInsert(type: $request['type'], value: $currentUrl);
                Toastr::success(translate('Updated_successfully'));
            } else {
                Toastr::error(translate('admin_and_employee_URL_cannot_be_same'));
            }
        }

        return back();
    }

    public function getProductSettingsView(): View
    {
        $digitalProduct = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'digital_product']);
        $brand = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'product_brand']);
        $stockLimit = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'stock_limit']);
        return view(BusinessSettings::PRODUCT_SETTINGS[VIEW], compact('digitalProduct', 'brand', 'stockLimit'));
    }

    public function updateProductSettings(Request $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'stock_limit', value: $request->get('stock_limit', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'product_brand', value: $request->get('product_brand', 0));
        $this->businessSettingRepo->updateOrInsert(type: 'digital_product', value: $request->get('digital_product', 0));
        Toastr::success(translate('updated_successfully'));
        return back();
    }

    public function getAnnouncementView(): View
    {
        $announcement = getWebConfig(name: 'announcement');
        return view(BusinessSettings::ANNOUNCEMENT[VIEW], compact('announcement'));
    }

    public function updateAnnouncement(Request $request): RedirectResponse
    {
        $value = json_encode(['status' => $request['announcement_status'],'color' => $request['announcement_color'],
                'text_color' => $request['text_color'],'announcement' => $request['announcement'],]);
        $this->businessSettingRepo->updateOrInsert(type: 'announcement', value: $value);
        Toastr::success(translate('announcement_updated_successfully'));
        return back();
    }

}
