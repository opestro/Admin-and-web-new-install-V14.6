<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session()->get('direction') ?? 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="_token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ getValidImage(path: 'storage/app/public/company/'.$web_config['fav_icon']->value, type: 'logo') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ getValidImage(path: 'storage/app/public/company/'.$web_config['fav_icon']->value, type: 'logo') }}">
    <link rel="stylesheet" media="screen" href="{{ theme_asset(path: 'public/assets/front-end/vendor/simplebar/dist/simplebar.min.css') }}">
    <link rel="stylesheet" media="screen" href="{{ theme_asset(path: 'public/assets/front-end/vendor/tiny-slider/dist/tiny-slider.css') }}">
    <link rel="stylesheet" media="screen" href="{{ theme_asset(path: 'public/assets/front-end/vendor/drift-zoom/dist/drift-basic.min.css') }}">
    <link rel="stylesheet" media="screen" href="{{ theme_asset(path: 'public/assets/front-end/vendor/lightgallery.js/dist/css/lightgallery.min.css') }}">
    <link rel="stylesheet" media="screen" href="{{ theme_asset(path: 'public/assets/front-end/css/theme.css') }}">
    <link rel="stylesheet" media="screen" href="{{ theme_asset(path: 'public/assets/front-end/css/slick.css') }}">
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/back-end/css/toastr.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/master.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/roboto-font.css')  }}">
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/css/lightbox.css') }}">
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/back-end/vendor/icon-set/style.css') }}">
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/owl.carousel.min.css') }}">

    @stack('css_or_js')

    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/home.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/responsive1.css') }}"/>

    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/style.css') }}">

    <style>
        :root {
            --base: {{ $web_config['primary_color'] }};
            --base-2: {{ $web_config['secondary_color'] }};
            --web-primary: {{ $web_config['primary_color'] }};
            --web-primary-10: {{ $web_config['primary_color'] }}10;
            --web-primary-20: {{ $web_config['primary_color'] }}20;
            --web-primary-40: {{ $web_config['primary_color'] }}40;
            --web-secondary: {{ $web_config['secondary_color'] }};
            --web-direction: {{ Session::get('direction') }};
            --text-align-direction: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }};
            --text-align-direction-alt: {{ Session::get('direction') === "rtl" ? 'left' : 'right'}};
        }

        .dropdown-menu:not(.m-0) {
            margin-{{ Session::get('direction') === "rtl" ? 'right' : 'left' }}: -8px !important;
        }

        @media (max-width: 767px) {
            .navbar-expand-md .dropdown-menu > .dropdown > .dropdown-toggle {
                padding-{{ Session::get('direction') === "rtl" ? 'left' : 'right'}}: 1.95rem;
            }
        }
    </style>

    <link rel="stylesheet" href="{{theme_asset(path: 'public/assets/front-end/css/custom.css')}}">

    @php($google_tag_manager_id = getWebConfig(name: 'google_tag_manager_id'))
    @if($google_tag_manager_id )
    <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{$google_tag_manager_id}}');</script>
    <!-- End Google Tag Manager -->
    @endif

    @php($pixel_analytics_user_code =getWebConfig(name: 'pixel_analytics'))
    @if($pixel_analytics_user_code)
        <!-- Facebook Pixel Code -->
            <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $pixel_analytics_user_code }}');
            fbq('track', 'PageView');
            </script>
            <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $pixel_analytics_user_code }}&ev=PageView&noscript=1"/>
            </noscript>
        <!-- End Facebook Pixel Code -->
    @endif
</head>

<body class="toolbar-enabled">

@if($google_tag_manager_id)
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{$google_tag_manager_id}}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif

@include('layouts.front-end.partials._modals')

@include('layouts.front-end.partials._quick-view-modal')
@include('layouts.front-end.partials.modal._buy-now')

@include('layouts.front-end.partials._header')
@include('layouts.front-end.partials._alert-message')

<span id="authentication-status" data-auth="{{ auth('customer')->check() ? 'true' : 'false' }}"></span>

<div class="row">
    <div class="col-12 loading-parent">
        <div id="loading" class="d--none">
           <div class="text-center">
            <img width="200" alt=""
                 src="{{ getValidImage(path: 'storage/app/public/company/'.getWebConfig(name: 'loader_gif'), type: 'source', source: theme_asset(path: 'public/assets/front-end/img/loader.gif')) }}">
            </div>
        </div>
    </div>
</div>

@yield('content')

<span id="message-otp-sent-again" data-text="{{ translate('OTP_has_been_sent_again.') }}"></span>
<span id="message-wait-for-new-code" data-text="{{ translate('please_wait_for_new_code.') }}"></span>
<span id="message-please-check-recaptcha" data-text="{{ translate('please_check_the_recaptcha.') }}"></span>
<span id="message-please-retype-password" data-text="{{ translate('please_ReType_Password') }}"></span>
<span id="message-password-not-match" data-text="{{ translate('password_do_not_match') }}"></span>
<span id="message-password-match" data-text="{{ translate('password_match') }}"></span>
<span id="message-password-need-longest" data-text="{{ translate('password_Must_Be_6_Character') }}"></span>
<span id="message-send-successfully" data-text="{{ translate('send_successfully') }}"></span>
<span id="message-update-successfully" data-text="{{ translate('update_successfully') }}"></span>
<span id="message-successfully-copied" data-text="{{ translate('successfully_copied') }}"></span>
<span id="message-copied-failed" data-text="{{ translate('copied_failed') }}"></span>
<span id="message-select-payment-method" data-text="{{ translate('please_select_a_payment_Methods') }}"></span>
<span id="message-please-choose-all-options" data-text="{{ translate('please_choose_all_the_options') }}"></span>
<span id="message-cannot-input-minus-value" data-text="{{ translate('cannot_input_minus_value') }}"></span>
<span id="message-all-input-field-required" data-text="{{ translate('all_input_field_required') }}"></span>
<span id="message-no-data-found" data-text="{{ translate('no_data_found') }}"></span>
<span id="message-minimum-order-quantity-cannot-less-than" data-text="{{ translate('minimum_order_quantity_cannot_be_less_than_') }}"></span>
<span id="message-item-has-been-removed-from-cart" data-text="{{ translate('item_has_been_removed_from_cart') }}"></span>
<span id="message-sorry-stock-limit-exceeded" data-text="{{ translate('sorry_stock_limit_exceeded') }}"></span>
<span id="message-sorry-the-minimum-order-quantity-not-match" data-text="{{ translate('sorry_the_minimum_order_quantity_does_not_match') }}"></span>
<span id="message-cart" data-text="{{ translate('cart') }}"></span>

<span id="route-messages-store" data-url="{{ route('messages_store') }}"></span>
<span id="route-address-update" data-url="{{ route('address-update') }}"></span>
<span id="route-coupon-apply" data-url="{{ route('coupon.apply') }}"></span>
<span id="route-cart-add" data-url="{{ route('cart.add') }}"></span>
<span id="route-cart-remove" data-url="{{ route('cart.remove') }}"></span>
<span id="route-cart-variant-price" data-url="{{ route('cart.variant_price') }}"></span>
<span id="route-cart-nav-cart" data-url="{{ route('cart.nav-cart') }}"></span>
<span id="route-cart-order-again" data-url="{{ route('cart.order-again') }}"></span>
<span id="route-cart-updateQuantity" data-url="{{route('cart.updateQuantity')}}"></span>
<span id="route-cart-updateQuantity-guest" data-url="{{route('cart.updateQuantity.guest')}}"></span>
<span id="route-pay-offline-method-list" data-url="{{ route('pay-offline-method-list') }}"></span>
<span id="route-customer-auth-sign-up" data-url="{{ route('customer.auth.sign-up') }}"></span>
<span id="route-searched-products" data-url="{{ url('/searched-products') }}"></span>
<span id="route-currency-change" data-url="{{ route('currency.change') }}"></span>
<span id="route-store-wishlist" data-url="{{ route('store-wishlist') }}"></span>
<span id="route-delete-wishlist" data-url="{{ route('delete-wishlist') }}"></span>
<span id="route-wishlists" data-url="{{ route('wishlists') }}"></span>
<span id="route-quick-view" data-url="{{ route('quick-view') }}"></span>
<span id="route-checkout-details" data-url="{{ route('checkout-details') }}"></span>
<span id="route-checkout-payment" data-url="{{ route('checkout-payment') }}"></span>
<span id="route-set-shipping-id" data-url="{{ route('customer.set-shipping-method') }}"></span>
<span id="route-order-note" data-url="{{ route('order_note') }}"></span>
<span id="password-error-message" data-max-character="{{translate('at_least_8_characters').'.'}}" data-uppercase-character="{{translate('at_least_one_uppercase_letter_').'(A...Z)'.'.'}}" data-lowercase-character="{{translate('at_least_one_uppercase_letter_').'(a...z)'.'.'}}"
      data-number="{{translate('at_least_one_number').'(0...9)'.'.'}}" data-symbol="{{translate('at_least_one_symbol').'(!...%)'.'.'}}"></span>
<span class="system-default-country-code" data-value="{{ getWebConfig(name: 'country_code') ?? 'us' }}"></span>
<span id="system-session-direction" data-value="{{ session()->get('direction') ?? 'ltr' }}"></span>

<span id="is-request-customer-auth-sign-up" data-value="{{ Request::is('customer/auth/sign-up*') ? 1:0 }}"></span>
<span id="is-customer-auth-active" data-value="{{ auth('customer')->check() ? 1:0 }}"></span>

<span id="storage-flash-deals" data-value="{{ $web_config['flash_deals']['start_date'] ?? '' }}"></span>

@include('layouts.front-end.partials._footer')
@include('layouts.front-end.partials.modal._dynamic-modals')

<a class="btn-scroll-top btn--primary" href="#top" data-scroll>
    <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2">{{ translate('top')}}</span>
    <i class="btn-scroll-top-icon czi-arrow-up"></i>
</a>
<div class="__floating-btn">
    @php($whatsapp = getWebConfig(name: 'whatsapp'))
    @if(isset($whatsapp['status']) && $whatsapp['status'] == 1 )
        <div class="wa-widget-send-button">
            <a href="https://wa.me/{{ $whatsapp['phone'] }}?text=Hello%20there!" target="_blank">
                <img src="{{theme_asset(path: 'public/assets/front-end/img/whatsapp.svg')}}" class="wa-messenger-svg-whatsapp wh-svg-icon" alt="{{ translate('Chat_with_us_on_WhatsApp') }}">
            </a>
        </div>
    @endif
</div>

<script src="{{ theme_asset(path: 'public/assets/front-end/vendor/jquery/dist/jquery-2.2.4.min.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/vendor/bs-custom-file-input/dist/bs-custom-file-input.min.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/vendor/simplebar/dist/simplebar.min.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/vendor/tiny-slider/dist/min/tiny-slider.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js') }}"></script>
<script src="{{ theme_asset(path: 'public/js/lightbox.min.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/vendor/drift-zoom/dist/Drift.min.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/vendor/lightgallery.js/dist/js/lightgallery.min.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/vendor/lg-video.js/dist/lg-video.min.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/js/owl.carousel.min.js')}}"></script>
<script src="{{ theme_asset(path: "public/assets/back-end/js/toastr.js" )}}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/js/theme.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/js/slick.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/js/sweet_alert.js') }}"></script>
<script src="{{ theme_asset(path: "public/assets/back-end/js/toastr.js") }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/js/custom.js') }}"></script>

{!! Toastr::message() !!}

<script>
    "use strict";

    @if(Request::is('/') &&  \Illuminate\Support\Facades\Cookie::has('popup_banner')==false)
    $(document).ready(function () {
        $('#popup-modal').modal('show');
    });
    @php(\Illuminate\Support\Facades\Cookie::queue('popup_banner', 'off', 1))
    @endif

    @if ($errors->any())
    @foreach($errors->all() as $error)
    toastr.error('{{$error}}', Error, {
        CloseButton: true,
        ProgressBar: true
    });
    @endforeach
    @endif

    $(document).mouseup(function (e) {
        let container = $(".search-card");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });

    function route_alert(route, message) {
        Swal.fire({
            title: '{{ translate("are_you_sure")}}?',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '{{$web_config['primary_color']}}',
            cancelButtonText: '{{ translate("no")}}',
            confirmButtonText: '{{ translate("yes")}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = route;
            }
        })
    }

    @php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
    let cookie_content = `
        <div class="cookie-section">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between column-gap-4 row-gap-3">
                    <div class="text-wrapper">
                        <h5 class="title">{{ translate("Your_Privacy_Matter")}}</h5>
                        <div>{{ $cookie ? $cookie['cookie_text'] : '' }}</div>
                    </div>
                    <div class="btn-wrapper">
                        <button class="btn bg-dark text-white cursor-pointer" id="cookie-reject">{{ translate("no_thanks")}}</button>
                        <button class="btn btn-success cookie-accept" id="cookie-accept">{{ translate('i_Accept')}}</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    $(document).on('click','#cookie-accept',function() {
        document.cookie = '6valley_cookie_consent=accepted; max-age=' + 60 * 60 * 24 * 30;
        $('#cookie-section').hide();
    });
    $(document).on('click','#cookie-reject',function() {
        document.cookie = '6valley_cookie_consent=reject; max-age=' + 60 * 60 * 24;
        $('#cookie-section').hide();
    });

    $(document).ready(function() {
        if (document.cookie.indexOf("6valley_cookie_consent=accepted") !== -1) {
            $('#cookie-section').hide();
        }else{
            $('#cookie-section').html(cookie_content).show();
        }
    });
</script>
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

@stack('script')

</body>
</html>
