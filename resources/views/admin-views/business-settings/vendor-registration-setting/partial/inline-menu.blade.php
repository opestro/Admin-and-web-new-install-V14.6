@php
    use App\Enums\ViewPaths\Admin\VendorRegistrationSetting;
@endphp
<div class="d-flex justify-content-between align-items-center gap-3 mb-6">
    <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-registration-setting.png')}}" alt="">
        {{translate('vendor_registration')}}
    </h2>
</div>

<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/vendor-registration-settings/'.VendorRegistrationSetting::INDEX[URI]) ?'active':'' }}"><a
                href="{{ route('admin.business-settings.vendor-registration-settings.index') }}">{{translate('header')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/vendor-registration-settings/'.VendorRegistrationSetting::WITH_US[URI]) ?'active':'' }}"><a
                href="{{ route('admin.business-settings.vendor-registration-settings.with-us') }}">{{translate('why_Sell_With_Us')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/vendor-registration-settings/'.VendorRegistrationSetting::BUSINESS_PROCESS[URI]) ?'active':'' }}"><a
                href="{{ route('admin.business-settings.vendor-registration-settings.business-process') }}">{{translate('business_Process')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/vendor-registration-settings/'.VendorRegistrationSetting::DOWNLOAD_APP[URI]) ?'active':'' }}"><a
                href="{{ route('admin.business-settings.vendor-registration-settings.download-app') }}">{{translate('download_App')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/vendor-registration-settings/'.VendorRegistrationSetting::FAQ[URI]) ?'active':'' }}"><a
                href="{{ route('admin.business-settings.vendor-registration-settings.faq') }}">{{translate('FAQ')}}</a>
        </li>
    </ul>
</div>
