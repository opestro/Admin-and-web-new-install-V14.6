@php
    use function App\Utils\hex_to_rgb;
@endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session()->get('direction') }}">
<head>
    <title>@yield('title')</title>
    <meta name="base-url" content="{{ url('/') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="_token" content="{{csrf_token()}}">
    <link rel="shortcut icon" href="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/css/fonts-init.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/css/bootstrap-icons.min.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/magnific-popup-1.1.0/magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/swiper/swiper-bundle.min.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/sweet_alert/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/toastr.css') }}"/>

    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/intl-tel-input/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/style.css') }}"/>
    @stack('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset('assets/css/custom.css') }}"/>
    <style>
        :root {
            --bs-primary: {{ $web_config['primary_color'] }};
            --bs-primary-rgb: {{ hex_to_rgb($web_config['primary_color']) }};
            --primary-dark: {{ $web_config['primary_color'] }};
            --primary-light: {{ $web_config['primary_color_light'] }};
            --bs-secondary: {{ $web_config['secondary_color'] }};
            --bs-secondary-rgb: {{ hex_to_rgb($web_config['secondary_color']) }};
        }

        .announcement-color {
            background-color: {{ $web_config['announcement']['color'] }};
            color: {{$web_config['announcement']['text_color']}};
        }
        .btn-outline-success {
            --bs-btn-hover-bg: {{ $web_config['primary_color'] }} !important;
            --bs-btn-active-bg: {{ $web_config['primary_color'] }} !important;
            --bs-btn-border-color: {{ $web_config['primary_color'] }} !important;
        }
        .btn-outline-success:active {
            background-color: var(--bg-color) !important;
            color: {{ $web_config['primary_color'] }} !important;
            --bs-btn-border-color: {{ $web_config['primary_color'] }} !important;
        }
    </style>
    @php($google_tag_manager_id = getWebConfig(name: 'google_tag_manager_id'))
    @if($google_tag_manager_id )
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                let f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l !== 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{$google_tag_manager_id}}');
        </script>
    @endif
    @php($pixel_analytices_user_code =getWebConfig(name: 'pixel_analytics'))
    @if($pixel_analytices_user_code)
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $pixel_analytices_user_code }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img class="d-none" height="1" width="1"
                 src="https://www.facebook.com/tr?id={{ $pixel_analytices_user_code }}&ev=PageView&noscript=1" alt=""/>
        </noscript>
    @endif
</head>
<body class="toolbar-enabled">
<script>
    'use strict';
    function setThemeMode() {
        if (localStorage.getItem('theme') === null) {
            document.body.setAttribute('theme', 'light');
        } else {
            document.body.setAttribute('theme', localStorage.getItem('theme'));
        }
    }
    setThemeMode();
</script>
@if($google_tag_manager_id)
    <noscript>
        <iframe class="d-none visibility-hidden" src="https://www.googletagmanager.com/ns.html?id={{$google_tag_manager_id}}"
                height="0" width="0"></iframe>
    </noscript>
@endif
<div class="preloader d--none" id="loading">
    <img width="200" alt="" src="{{ getValidImage(path: 'storage/app/public/company/'.getWebConfig(name: 'loader_gif'), type: 'source', source: theme_asset('assets/img/loader.gif')) }}">
</div>
@include('theme-views.layouts.partials._alert-message')
@include('theme-views.layouts.partials._header')
@include('theme-views.layouts.partials._settings-sidebar')
@yield('content')
@include('theme-views.layouts.partials._feature')
@include('theme-views.layouts.partials._footer')
<a href="#" class="back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>
<div class="app-bar px-sm-2 d-xl-none" id="mobile_app_bar">
    @include('theme-views.layouts.partials._app-bar')
</div>
<span class="customize-text"
      data-textno="{{ translate('no') }}"
      data-textyes="{{ translate('yes') }}"
      data-textnow="{{ translate('now') }}"
      data-textsuccessfullycopied="{{ translate('successfully_copied') }}"
      data-text-no-discount="{{ translate('no_discount') }}"
      data-stock-available="{{ translate('stock_available') }}"
      data-stock-not-available="{{ translate('stock_not_available') }}"
      data-update-this-address="{{ translate('update_this_Address') }}"
      data-password-characters-limit="{{ translate('your_password_must_be_at_least_8_characters') }}"
      data-password-not-match="{{ translate('password_does_not_Match') }}"
      data-textpleaseselectpaymentmethods="{{ translate('please_select_a_payment_Methods') }}"
      data-reviewmessage="{{ translate('you_can_review_after_the_product_is_delivered') }}"
      data-refundmessage="{{ translate('you_can_refund_request_after_the_product_is_delivered') }}"
      data-textshoptemporaryclose="{{ translate('This_shop_is_temporary_closed_or_on_vacation').' '.translate('You_cannot_add_product_to_cart_from_this_shop_for_now') }}"
></span>
<span class="system-default-country-code" data-value="{{ getWebConfig(name: 'country_code') ?? 'us' }}"></span>
<span class="cannot_use_zero" data-text="{{ translate('cannot_Use_0_only') }}"></span>
<span class="system-default-country-code" data-value="{{ getWebConfig(name: 'country_code') ?? 'us' }}"></span>
@php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
@if($cookie && $cookie['status']==1)
    <section id="cookie-section"></section>
@endif

@include('theme-views.layouts.partials.modal._register')
@include('theme-views.layouts.partials.modal._login')
@include('theme-views.layouts.partials.modal._quick-view')
@include('theme-views.layouts.partials.modal._buy-now')
@include('theme-views.layouts.partials.modal._initial')

@php($whatsapp = getWebConfig(name: 'whatsapp'))
<div class="social-chat-icons">
    @if(isset($whatsapp['status']) && $whatsapp['status'] == 1 )
        <div class="">
            <a href="https://wa.me/{{ $whatsapp['phone'] }}?text=Hello%20there!" target="_blank">
                <img src="{{theme_asset('assets/img/whatsapp.svg')}}" width="35" class="chat-image-shadow"
                     alt="Chat with us on WhatsApp">
            </a>
        </div>
    @endif
</div>

@include('theme-views.layouts.partials._translate-text-for-js')
@include('theme-views.layouts.partials._route-for-js')
@include('theme-views.layouts.main-script')

{!! Toastr::message() !!}
@stack('script')

</body>
</html>
