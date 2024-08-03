@php
    use App\Enums\ViewPaths\Admin\ThemeSetup;
@endphp
<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/web-config/theme/'.ThemeSetup::VIEW[URI]) ?'active':'' }}">
            <a href="{{route('admin.business-settings.web-config.theme.setup')}}">{{translate('theme_Setup')}}</a>
        </li>
        <li class="{{ Request::is('admin/addon') ?'active':'' }}">
            <a href="{{route('admin.addon.index')}}">{{translate('system_Addons')}}</a>
        </li>

    </ul>
</div>
