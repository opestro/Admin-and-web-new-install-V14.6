<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ translate('Email Verification') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
{{--    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/email-basic.css') }}">--}}
    @include('email-templates.partials.style')
</head>
<body>
<?php

$companyPhone = getWebConfig(name: 'company_phone');
$companyEmail = getWebConfig(name: 'company_email');
$companyName = getWebConfig(name: 'company_name');
$companyLogo = getWebConfig(name: 'company_web_logo');
?>

<div class="main-table">
    @include('admin-views.business-settings.email-template.vendor-mail-template.registration')
</div>
{{--<div class="d-flex justify-content-center align-items-center m-auto vh-100">--}}
{{--    <div class="card">--}}
{{--        <div class="m-auto bg-white pt-40px pb-40px text-center">--}}
{{--            <div class="d-block">--}}
{{--                @if(is_file('storage/app/public/company/'.$companyLogo))--}}
{{--                    <div class="d-flex justify-content-center align-items-center gap-1">--}}
{{--                        <img src="{{ dynamicStorage(path: 'storage/app/public/company/'.$companyLogo) }}" alt="{{ $companyName }}"--}}
{{--                             class="width-auto h-50px">--}}
{{--                        {{ $companyName }}--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                    {{ $companyName }}--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="card-header mb-3 text-center">--}}
{{--            {{ translate('mail_received_successfully') }}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
</body>
</html>

