@php
    $companyName = getWebConfig(name: 'company_name');
    $companyLogo = getWebConfig(name: 'company_web_logo');
    $title = $template['title'] ?? null;
    $body = $template['body'] ?? null;
    $copyrightText = $template['copyright_text'] ?? null;
    $footerText = $template['footer_text'] ?? null;
    $buttonName = $template['button_name'] ?? null;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ translate('Email Verification') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('email-templates.partials.style')
</head>
<body>
<div class="main-table">
    @include('admin-views.business-settings.email-template.'.$template['user_type'].'-mail-template'.'.'.$template['template_design_name'])
</div>
</body>
</html>

