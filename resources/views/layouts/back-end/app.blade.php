@php
    use App\Utils\Helpers;
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{Session::get('direction')}}"
      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <meta name="_token" content="{{csrf_token()}}">
    <link rel="shortcut icon" href="{{dynamicStorage(path: 'storage/app/public/company/'.getWebConfig(name: 'company_fav_icon'))}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/vendor.min.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/google-fonts.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/custom.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/vendor/icon-set/style.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/theme.minc619.css?v=1.0')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/style.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/toastr.css')}}">
    @if(Session::get('direction') === "rtl")
        <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/menurtl.css')}}">
    @endif
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/css/lightbox.css')}}">
    @stack('css_or_js')
    <script
        src="{{dynamicAsset(path: 'public/assets/back-end/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js')}}"></script>
    <style>
        select {
            background-image: url('{{dynamicAsset(path: 'public/assets/back-end/img/arrow-down.png')}}');
            background-size: 7px;
            background-position: 96% center;
        }
    </style>
    @if(Request::is('admin/payment/configuration/addon-payment-get'))
        <style>
            .form-floating > label {
                position: relative;
                display: block;
                margin-bottom: 12px;
                padding: 0;
                inset-inline: 0 !important;
            }
        </style>
    @endif
</head>

<body class="footer-offset">

@include('layouts.back-end.partials._front-settings')
<span class="d-none" id="placeholderImg" data-img="{{dynamicAsset(path: 'public/assets/back-end/img/400x400/img3.png')}}"></span>
<div class="row">
    <div class="col-12 position-fixed z-9999 mt-10rem">
        <div id="loading" class="d--none">
            <div id="loader"></div>
        </div>
    </div>
</div>
@include('layouts.back-end.partials._header')
@include('layouts.back-end.partials._side-bar')
@include('layouts.back-end._translator-for-js')
<span id="get-root-path-for-toggle-modal-image" data-path="{{dynamicAsset(path: 'public/assets/back-end/img/modal')}}"></span>

<main id="content" role="main" class="main pointer-event">
    @yield('content')
    @include('layouts.back-end.partials._footer')
    @include('layouts.back-end.partials._modals')
    @include('layouts.back-end.partials._toggle-modal')
    @include('layouts.back-end.partials._sign-out-modal')
    @include('layouts.back-end._alert-message')
</main>
<span class="please_fill_out_this_field" data-text="{{ translate('please_fill_out_this_field') }}"></span>
<span class="get-application-environment-mode" data-value="{{ env('APP_MODE') == 'demo' ? 'demo':'live' }}"></span>
<span id="get-currency-symbol"
      data-currency-symbol="{{ getCurrencySymbol(currencyCode: getCurrencyCode(type: 'default')) }}"></span>

<span id="message-select-word" data-text="{{ translate('select') }}"></span>
<span id="message-yes-word" data-text="{{ translate('yes') }}"></span>
<span id="message-no-word" data-text="{{ translate('no') }}"></span>
<span id="message-cancel-word" data-text="{{ translate('cancel') }}"></span>
<span id="message-are-you-sure" data-text="{{ translate('are_you_sure') }} ?"></span>
<span id="message-invalid-date-range" data-text="{{ translate('invalid_date_range') }}"></span>
<span id="message-status-change-successfully" data-text="{{ translate('status_change_successfully') }}"></span>
<span id="message-are-you-sure-delete-this" data-text="{{ translate('are_you_sure_to_delete_this') }} ?"></span>
<span id="message-you-will-not-be-able-to-revert-this"
      data-text="{{ translate('you_will_not_be_able_to_revert_this') }}"></span>

<span id="get-customer-list-route" data-action="{{route('admin.customer.customer-list-search')}}"></span>
<span id="get-customer-list-without-all-customer-route" data-action="{{route('admin.customer.customer-list-without-all-customer')}}"></span>

<span id="get-search-product-route" data-action="{{route('admin.products.search-product')}}"></span>
<span id="get-orders-list-route" data-action="{{route('admin.orders.list',['status'=>'all'])}}"></span>
<span id="get-stock-limit-status" data-action="{{route('admin.products.stock-limit-status',['type'=>'in_house'])}}"></span>
<span id="get-product-stock-limit-title" data-title="{{translate('warning')}}"></span>
<span id="get-product-stock-limit-image" data-warning-image="{{ dynamicAsset(path: 'public/assets/back-end/img/warning-2.png') }}"></span>
<span id="get-product-stock-limit-message"
      data-message-for-multiple="{{ translate('there_isn’t_enough_quantity_on_stock').' . '.translate('please_check_products_in_limited_stock').'.' }}"
      data-message-for-three-plus-product="{{translate('_more_products_have_low_stock') }}"
      data-message-for-one-product="{{translate('this_product_is_low_on_stock')}}">
</span>
<span id="get-product-stock-view"
      data-stock-limit-page="{{route('admin.products.stock-limit-list',['in_house'])}}"
>
</span>
<span id="getChattingNewNotificationCheckRoute" data-route="{{ route('admin.messages.new-notification') }}"></span>
<span class="system-default-country-code" data-value="{{ getWebConfig(name: 'country_code') ?? 'us' }}"></span>

<audio id="myAudio">
    <source src="{{ dynamicAsset(path: 'public/assets/back-end/sound/notification.mp3') }}" type="audio/mpeg">
</audio>


<script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/theme.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/bootstrap.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/sweet_alert.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/toastr.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/js/lightbox.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/custom.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/app-script.js')}}"></script>

{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        'use strict';
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

@stack('script')

@if(Helpers::module_permission_check('order_management') && env('APP_MODE')!='dev')
<script>
    'use strict'
        setInterval(function () {
            $.get({
                url: '{{route('admin.orders.get-order-data')}}',
                dataType: 'json',
                success: function (response) {
                    let data = response.data;
                    if (data.new_order > 0) {
                        playAudio();
                        $('#popup-modal').appendTo("body").modal('show');
                    }
                },
            });
        }, 5000);
</script>
@endif
@if(env('APP_MODE') == 'demo')
    <script>
        'use strict'
        function checkDemoResetTime() {
            let currentMinute = new Date().getMinutes();
            if (currentMinute > 55 && currentMinute <= 60) {
                $('#demo-reset-warning').addClass('active');
            } else {
                $('#demo-reset-warning').removeClass('active');
            }
        }
        checkDemoResetTime();
        setInterval(checkDemoResetTime, 60000);
    </script>
@endif


<script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/common-script.js') }}"></script>

@stack('script_2')

</body>
</html>
