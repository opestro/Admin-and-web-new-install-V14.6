@php
    use App\Enums\ViewPaths\Admin\BusinessSettings;
@endphp
<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/'.BusinessSettings::OTP_SETUP[URI]) ? 'active':'' }}">
            <a href="{{ route('admin.business-settings.otp-setup') }}">{{translate('OTP_&_Login_Attempts')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/web-config/'.BusinessSettings::LOGIN_URL_SETUP[URI]) ?'active':'' }}">
            <a href="{{route('admin.business-settings.web-config.login-url-setup')}}">{{translate('login_Url')}}</a>
        </li>
    </ul>
</div>
