<?php
    $companyPhone = getWebConfig(name: 'company_phone');
    $companyEmail = getWebConfig(name: 'company_email');
    $companyName = getWebConfig(name: 'company_name');
    $companyLogo = getWebConfig(name: 'company_web_logo');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ translate('test_Mail') }} - {{ translate('mail_configuration_check') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            border: 2px solid #007bff;
            padding: 10px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="text-center">
            <img height="60" class="mb-4" id="view-mail-icon"
                 src="{{ getStorageImages(path: $companyLogo, type: 'backend-logo') }}"
                 alt="">
        </div>
        <h1>{{ translate('test_Mail') }} - {{ translate('Mail_Configuration_Check') }}</h1>
    </div>
    <div class="content">
        <p>{{ translate('Dear') }} {{ translate('Sir') }}/{{ translate('Mam') }},</p>
        <p>{{ translate('this_is_a_test_email_to_confirm_that_mail_configuration_is_working_correctly.') }} {{ translate('if_you_received_this_message,_it_means_everything_is_set_up_properly.') }}</p>
        <p>{{ translate('thank_you') }}</p>
    </div>
    <div class="footer">
        <p>{{ translate('best_regards') }},</p>
        <p>{{ $companyName }}</p>
        <p><strong>{{ translate('phone') }}:</strong> {{ $companyPhone }}</p>
        <p><strong>{{ translate('Email') }}:</strong> {{ $companyEmail }}</p>
    </div>
</div>
</body>
</html>
