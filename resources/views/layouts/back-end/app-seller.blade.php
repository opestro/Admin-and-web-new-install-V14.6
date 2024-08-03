<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ Session::get('direction') }}"
    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{dynamicStorage(path: 'storage/app/public/company/'.getWebConfig(name: 'company_fav_icon'))}}">

    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/vendor.min.css') }}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/google-fonts.css')}}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/icon-set/style.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/theme.minc619.css?v=1.0') }}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/style.css')}}">
    @if (Session::get('direction') === 'rtl')
        <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/menurtl.css')}}">
    @endif
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/css/lightbox.css') }}">
    @stack('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/toastr.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/custom.css') }}">
    <style>
        select {
            background-image: url('{{dynamicAsset(path: 'public/assets/back-end/img/arrow-down.png')}}');
            background-size: 7px;
            background-position: 96% center;
        }
    </style>
</head>
<body class="footer-offset">
    @include('layouts.back-end.partials._front-settings')
    <div class="row">
        <div class="col-12 position-fixed z-9999 mt-10rem">
            <div id="loading" class="d--none">
                <div id="loader"></div>
            </div>
        </div>
    </div>
    @include('layouts.back-end.partials-seller._header')
    @include('layouts.back-end.partials-seller._side-bar')

    <main id="content" role="main" class="main pointer-event">
        @yield('content')

        @include('layouts.back-end.partials-seller._footer')

        @include('layouts.back-end.partials-seller._modals')

        @include('layouts.back-end.partials-seller._toggle-modal')
        @include('layouts.back-end._translator-for-js')
        @include('layouts.back-end.partials-seller._sign-out-modal')
        @include('layouts.back-end._alert-message')
    </main>

    <audio id="myAudio">
        <source src="{{ dynamicAsset(path: 'public/assets/back-end/sound/notification.mp3') }}" type="audio/mpeg">
    </audio>


    <span class="please_fill_out_this_field" data-text="{{ translate('please_fill_out_this_field') }}"></span>
    <span id="onerror-chatting" data-onerror-chatting="{{dynamicAsset(path: 'public/assets/back-end/img/image-place-holder.png')}}"></span>
    <span id="onerror-user" data-onerror-user="{{dynamicAsset(path: 'public/assets/back-end/img/160x160/img1.jpg')}}"></span>
    <span id="get-root-path-for-toggle-modal-image" data-path="{{dynamicAsset(path: 'public/assets/back-end/img/modal')}}"></span>
    <span id="get-customer-list-route" data-action="{{route('vendor.customer.list')}}"></span>
    <span id="get-search-product-route" data-action="{{route('vendor.products.search-product')}}"></span>
    <span id="get-orders-list-route" data-action="{{route('vendor.orders.list', ['status' => 'all'])}}"></span>
    <span class="system-default-country-code" data-value="{{ getWebConfig(name: 'country_code') ?? 'us' }}"></span>
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
    <span id="getChattingNewNotificationCheckRoute" data-route="{{ route('vendor.messages.new-notification') }}"></span>

    <span id="get-stock-limit-status" data-action="{{route('vendor.products.stock-limit-status')}}"></span>
    <span id="get-product-stock-limit-title" data-title="{{translate('warning')}}"></span>
    <span id="get-product-stock-limit-image" data-warning-image="{{ dynamicAsset(path: 'public/assets/back-end/img/warning-2.png') }}"></span>
    <span id="get-product-stock-limit-message"
          data-message-for-multiple="{{ translate('there_isn’t_enough_quantity_on_stock').' . '.translate('please_check_products_in_limited_stock').'.' }}"
          data-message-for-three-plus-product="{{translate('_more_products_have_low_stock') }}"
          data-message-for-one-product="{{translate('this_product_is_low_on_stock')}}">
    </span>
    <span id="get-product-stock-view"
          data-stock-limit-page="{{route('vendor.products.stock-limit-list')}}"
    >
    </span>

    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/vendor.min.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/theme.min.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/bootstrap.min.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/sweet_alert.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/toastr.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/js/lightbox.min.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/custom.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/app-script.js') }}"></script>
    <span id="get-currency-symbol"
          data-currency-symbol="{{ getCurrencySymbol(currencyCode: getCurrencyCode(type: 'default')) }}"></span>

    {!! Toastr::message() !!}
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif

    <script>
        'use strict'
        setInterval(function() {
            $.get({
                url: '{{ route('vendor.get-order-data') }}',
                dataType: 'json',
                success: function(response) {
                    let data = response.data;
                    if (data.new_order > 0) {
                        playAudio();
                        $('#popup-modal').appendTo("body").modal('show');
                    }
                },
            });
        }, 10000);
    </script>

    <script>
        $('.notification-data-view').on('click',function (){
            let id= $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: "{{route('vendor.notification.index')}}",
                data: {
                    _token: '{{csrf_token()}}',
                    id: id,
                },
                beforeSend: function () {
                },
                success: function (data) {
                    $('.notification_data_new_badge'+id).fadeOut();
                    $('#NotificationModalContent').empty().html(data.view);
                    $('#NotificationModal').modal('show');
                    let notificationDataCount = $('.notification_data_new_count');
                    let notificationCount = parseInt(data.notification_count);
                    notificationCount === 0 ? notificationDataCount.fadeOut() : notificationDataCount.html(notificationCount);
                },
                complete: function () {
                },
            });
        })
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write(
            '<script src="{{ dynamicAsset(path: 'public/assets/back-end') }}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
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

    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/common-script.js') }}"></script>
    @stack('script_2')

</body>

</html>
