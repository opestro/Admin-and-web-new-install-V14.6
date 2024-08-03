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

        .reset-password-btn {
            display: flex;
            padding: 10px 25px;
            align-items: center;
            gap: 10px;
            background: #1455AC !important;
            color: #ffffff !important;
            width: 160px;
            height: 38px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .product-image {
            width: 35px;
            height: 35px;
            border: 1px solid #e5e5e5;
            objectFit: cover
        }

        .product-title {
            padding-left: 15px;
            max-width: calc(100% - 35px)
        }
    </style>

</head>


<body style="background-color: #e9ecef;padding:15px">

<table dir="{{ $direction }}" class="main-table">
    <tbody>
    <tr>
        <td class="main-table-td">
            <img class="mail-img-1" src='{{ dynamicAsset(path: 'public/assets/back-end/img/congratulations.png') }}'
                 id="logoViewer" alt="">
            <h2 id="mail-title" class="mt-2 text-center">{{  $data['title'] }}</h2>
            <h3 class="mb-1" id="mail-body">{{ translate('Hi').' '.$data['order']->customer['f_name'].',' }}</h3>
            <div
                class="mb-1">{{translate('thank_you_for_choosing_').$companyName.'! '.translate('your_digital_product_is_ready_for_download').'.'.translate('to_download_your_product').','.translate('_use_your_email_')}}
                <span style="color: #0a53be;">{{$data['order']->customer['email']}}</span>{{translate('_and')}}
                <strong>{{translate('_order').'# '.$data['order']->id. translate('_below')}}</strong></div>
            <br>
            <table class="order-body-table" style="width: 100%;background: #E9F6FF">
                <tr>
                    <td style="text-align:center">
                        <img class="mail-img-2 mt-10"
                             src="{{ getValidImage(path: "storage/app/public/company/".$companyLogo, type:'backend-logo') }}"
                             id="logoViewer" alt="">
                        <br>
                        <h3 class="title text-capitalize">{{translate('order_info')}}</h3>
                        <p>
                            {{translate('to_verify_when_download_your_product').','.translate('_use_your_order_info').'.'}}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px">
                        <table style="width:100%; background: #ffffff">
                            <tr>
                                <td style="text-align:left;padding:15px 15px 5px">{{translate('Order').'#'.$data['order']->id}}</td>
                                <td style="text-align:right;padding:15px 15px 5px">{{$data['order']->customer['phone']}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:5px 15px">
                                    <h4 style="margin:0 0 8px;">{{translate('products')}}</h4>
                                    @foreach($data['order']->details as $details)
                                        @php($product = json_decode($details['product_details']))
                                        @if($product->product_type == 'digital')
                                            <div style="display: flex;align-items:center;margin-bottom: 10px">
                                                <img class="product-image"
                                                     src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product->thumbnail, type: 'backend-product') }}"
                                                     style="" alt="">
                                                <div class="product-title">{{substr($product->name, 0, 50)}}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;padding:16px">
                        <div class="text-capitalize" style="margin-bottom:10px">{{translate('click_below')}}</div>
                        <a href="{{route('digital-product-download-pos.index',['order_id'=>$data['order']->id,'email'=>$data['order']->customer['email']])}}"
                           style="color:#0177CD">{{url('digital-product-download-pos')}}</a>
                    </td>
                </tr>
            </table>
            <br>
            <div style="color: rgba(51, 66, 87, 0.80)">
                {{translate('don’t_share_your_order_information').', '.translate('_it’s_confidential').', '.translate('_if_you_share_this_info').', '.translate('_then_anyone_can_download').'.'}}
            </div>
            <br>
            <div class="mb-1">{{translate('meanwhile_click_here_to_visit_').$companyName.translate('_website')}}.</div>
            <a href="{{route('home')}}" target="_blank" style="text-decoration: underline">{{url('/')}}</a>
            <hr>
            <div class="mb-2" id="mail-footer">
                {{ translate('please_')}}
                <a href="{{route('contacts')}}" target="_blank">{{ translate('_contact_us')}}</a>
                {{ translate('_for_any_queries').','.translate('_we’re_always_happy_to_help').'.' }}
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
