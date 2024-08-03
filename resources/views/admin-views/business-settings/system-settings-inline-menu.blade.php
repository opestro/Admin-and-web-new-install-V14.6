@php
    use App\Enums\ViewPaths\Admin\BusinessSettings;
    use App\Enums\ViewPaths\Admin\Currency;
    use App\Enums\ViewPaths\Admin\DatabaseSetting;
    use App\Enums\ViewPaths\Admin\EnvironmentSettings;
    use App\Enums\ViewPaths\Admin\SiteMap;
    use App\Enums\ViewPaths\Admin\SoftwareUpdate;
@endphp
<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/web-config/'.EnvironmentSettings::VIEW[URI]) ?'active':'' }}">
            <a href="{{route('admin.business-settings.web-config.environment-setup')}}">{{translate('Environment_Settings')}}</a>
        </li>

        <li class="{{ Request::is('admin/business-settings/web-config/'.BusinessSettings::APP_SETTINGS[URI]) ?'active':'' }}">
            <a href="{{route('admin.business-settings.web-config.app-settings')}}">{{translate('app_Settings')}}</a>
        </li>

        <li class="{{ Request::is('admin/system-settings/'.SoftwareUpdate::VIEW[URI]) ?'active':'' }}">
            <a href="{{route('admin.system-settings.software-update')}}">{{translate('software_Update')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/language') ?'active':'' }}">
            <a href="{{route('admin.business-settings.language.index')}}">{{translate('language')}}</a>
        </li>
        <li class="{{ Request::is('admin/currency/'.Currency::LIST[URI]) ?'active':'' }}">
            <a href="{{route('admin.currency.view')}}">{{translate('Currency')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/'.BusinessSettings::COOKIE_SETTINGS[URI]) ? 'active':'' }}">
            <a href="{{ route('admin.business-settings.cookie-settings') }}">{{translate('cookies')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/web-config/'.DatabaseSetting::VIEW[URI]) ?'active':'' }}">
            <a href="{{route('admin.business-settings.web-config.db-index')}}">{{translate('Clean_Database')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/web-config/'.SiteMap::VIEW[URI]) ?'active':'' }}">
            <a href="{{route('admin.business-settings.web-config.mysitemap')}}">{{translate('site_Map')}}</a>
        </li>

    </ul>
</div>
