@php
    use App\Enums\ViewPaths\Admin\ErrorLogs;
    use App\Enums\ViewPaths\Admin\RobotsMetaContent;
    use App\Enums\ViewPaths\Admin\SEOSettings;
    use App\Enums\ViewPaths\Admin\SiteMap;
@endphp
<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/seo-settings/'.SEOSettings::WEB_MASTER_TOOL[URI]) ? 'active' : '' }}">
            <a href="{{ route('admin.seo-settings.web-master-tool') }}">
                {{ translate('Webmaster_Tools') }}
            </a>
        </li>
        <li class="{{ Request::is('admin/seo-settings/'.SEOSettings::ROBOT_TXT[URI]) ? 'active' : '' }}">
            <a href="{{ route('admin.seo-settings.robot-txt') }}">
                {{ translate('Robots.txt') }}
            </a>
        </li>
        <li class="{{ Request::is('admin/seo-settings/'.SiteMap::SITEMAP[URI]) ? 'active' : '' }}">
            <a href="{{ route('admin.seo-settings.sitemap') }}">
                {{ translate('Sitemap') }}
            </a>
        </li>
        <li class="{{ Request::is('admin/seo-settings/robots-meta-content*') ? 'active' : '' }}">
            <a href="{{ route('admin.seo-settings.robots-meta-content.index') }}">
                {{ translate('Robots_Meta_Content') }}
            </a>
        </li>
        <li class="{{ Request::is('admin/error-logs/'.ErrorLogs::INDEX[URI]) ? 'active' : '' }}">
            <a href="{{ route('admin.error-logs.index') }}">
                {{ translate('404_Logs') }}
            </a>
        </li>
    </ul>
</div>
