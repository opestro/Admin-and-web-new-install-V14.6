@php
    use App\Enums\ViewPaths\Admin\Recaptcha;
    use App\Enums\ViewPaths\Admin\SMSModule;
    use App\Enums\ViewPaths\Admin\SocialMediaChat;
    use App\Enums\ViewPaths\Admin\SocialLoginSettings;
    use App\Enums\ViewPaths\Admin\BusinessSettings;
@endphp
<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/social-media-chat/'.SocialMediaChat::VIEW[URI]) ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.social-media-chat.view')}}">{{translate('social_media_chat')}}</a></li>
        <li class="{{ Request::is('admin/social-login/'.SocialLoginSettings::VIEW[URI]) ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.social-login.view')}}">{{translate('social_media_login')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/mail') ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.business-settings.mail.index')}}">{{translate('mail_config')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/'.SMSModule::VIEW[URI]) ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.business-settings.sms-module')}}">{{translate('SMS_config')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/'.Recaptcha::VIEW[URI]) ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.business-settings.captcha')}}">{{translate('recaptcha')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/map-api') ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.business-settings.map-api')}}">{{translate('google_map_APIs')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/'.BusinessSettings::ANALYTICS_INDEX[URI]) ?'active':'' }}">
            <a href="{{route('admin.business-settings.analytics-index')}}">{{translate('Analytic_Scripts')}}</a>
        </li>
    </ul>
</div>
