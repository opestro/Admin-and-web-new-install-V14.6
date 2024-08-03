<!DOCTYPE html>
<?php

use App\Models\SocialMedia;
use Illuminate\Support\Facades\Session;

$companyPhone = getWebConfig(name: 'company_phone');
$companyEmail = getWebConfig(name: 'company_email');
$companyName = getWebConfig(name: 'company_name');
$companyLogo = getWebConfig(name: 'company_web_logo');
$lang = \App\Utils\Helpers::default_lang();
$direction = Session::get('direction');
?>
<html lang="{{ $lang }}" class="{{ $direction === 'rtl'?'active':'' }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ translate('Vendor_Registration') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;1,400&display=swap');

        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            font-size: 13px;
            line-height: 21px;
            color: #737883;
            background: #f7fbff;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #334257;
        }

        * {
            box-sizing: border-box
        }

        :root {
            --base: #006161
        }

        .main-table {
            width: 500px;
            background: #FFFFFF;
            margin: 0 auto;
            padding: 40px;
        }

        .main-table-td {
        }

        img {
            max-width: 100%;
        }

        .cmn-btn {
            background: var(--base);
            color: #fff;
            padding: 8px 20px;
            display: inline-block;
            text-decoration: none;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        .mb-5 {
            margin-bottom: 25px;
        }

        hr {
            border-color: rgba(0, 170, 109, 0.3);
            margin: 16px 0
        }

        .border-top {
            border-top: 1px solid rgba(0, 170, 109, 0.3);
            padding: 15px 0 10px;
            display: block;
        }

        .d-block {
            display: block;
        }

        .privacy {
            text-align: center;
            display: block;
        }

        .privacy a {
            text-decoration: none;
            color: #334257;
            position: relative;
        }

        .privacy a span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #334257;
            display: inline-block;
            margin: 0 7px;
        }

        .social {
            margin: 15px 0 8px;
            display: block;
        }

        .copyright {
            text-align: center;
            display: block;
        }

        div {
            display: block;
        }

        a {
            text-decoration: none;
        }

        .text-base {
            color: var(--base);
            font-weight: 700
        }

        .mail-img-1 {
            width: 100%;
            height: 136px;
            object-fit: contain
        }

        .mail-img-2 {
            width: 100%;
            height: 45px;
            object-fit: contain
        }

        .mail-img-3 {
            width: 100%;
            height: 172px;
            object-fit: cover
        }

        .social img {
            width: 24px;
        }

        .text-center {
            text-align: center;
        }
    </style>

</head>


<body style="background-color: #e9ecef;padding:15px">

<table dir="{{ $direction }}" class="main-table">
    <tbody>
    <tr>
        <td class="main-table-td">
            <img class="mail-img-1"
                 src='{{ dynamicAsset(path: 'public/assets/back-end/img/'.($data['status'] == 'denied' ? 'registration-denied' : 'registration-success').'.png') }}'
                 id="logoViewer" alt="">
            <h2 id="mail-title" class="mt-2 text-center">{{  $data['title'] }}</h2>
            <h3 class="mb-1" id="mail-body">{{ translate('Hi').' '.$data['name'].',' }}</h3>
            <div class="mb-1">{{$data['message']}}</div>
            @if($data['status'] == 'pending')
                <div class="mb-1">{{translate('meanwhile_click_here_to_visit_').$companyName.translate('_website')}}.
                </div>
                <a href="{{route('home')}}" target="_blank" style="text-decoration: underline">{{url('/')}}</a>
            @elseif($data['status'] == 'approved')
                <div class="mb-1">{{translate('Click here to login to your account').'.'}}</div>
                <a href="{{route('vendor.auth.login')}}" target="_blank"
                   style="text-decoration: underline">{{url('vendor/auth/login')}}</a>
            @endif

            <hr>
            <div class="mb-2" id="mail-footer">
                {{ translate('please_contact_us_for_any_queries').','.translate('_we’re_always_happy_to_help').'.' }}
            </div>
            <div>
                {{ translate('Thanks_&_Regards') }},
            </div>
            <div class="mb-4">
                {{ $companyName }}
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <img class="mail-img-2"
                 src="{{ getValidImage(path: "storage/app/public/company/".$companyLogo, type:'backend-logo') }}"
                 id="logoViewer" alt="">
            <span class="privacy">
                    <a href="{{route('privacy-policy') }}" target="_blank"
                       id="privacy-check">{{ translate('Privacy_Policy') }}</a>
                    <a href="{{route('contacts') }}" target="_blank" id="contact-check"><span class="dot"></span>{{ translate('Contact_Us') }}</a>
                </span>
            <span class="social" style="text-align:center">
                    @php($social_media = SocialMedia::where('active_status', 1)->get())
                @if ($social_media)
                    @foreach ($social_media as $social)
                        <a href="{{ $social->link }}" target=”_blank” style="margin: 0 5px;text-decoration:none;">
                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/'.$social->name.'.png') }}"
                                     width="16" alt="">
                            </a>
                    @endforeach
                @endif
                </span>
            <span class="copyright">
                   {{ translate('All_copy_right_reserved').','.date('Y').' '.$companyName }}
                </span>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
