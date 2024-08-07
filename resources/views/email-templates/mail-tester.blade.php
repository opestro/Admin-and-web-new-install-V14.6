<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ translate('test_mail') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('email-templates.partials.style')
</head>
<body>
<?php

$companyPhone = getWebConfig(name: 'company_phone');
$companyEmail = getWebConfig(name: 'company_email');
$companyName = getWebConfig(name: 'company_name');
$companyLogo = getWebConfig(name: 'company_web_logo');
?>

<div class="p-3 px-xl-4 py-sm-5">
    <div class="text-center">
        <img height="60" class="mb-4" id="view-mail-icon"
             src="{{ dynamicStorage(path: 'storage/app/public/company/'.$companyLogo) }}"
             alt="">
        <h3 class="mb-3 text-capitalize">
            {{ translate('test_mail') }} - {{ $companyName }}
        </h3>
        <h3 class="mb-3 text-capitalize">
            {{ translate('mail_received_successfully') }}
        </h3>
    </div>

</div>

</body>
</html>

