<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="">
    <link rel="icon" type="image/png" sizes="32x32" href="">
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <link rel="stylesheet" media="screen" href="{{theme_asset(path: 'public/assets/front-end/css/theme.min.css')}}">
    <link rel="stylesheet" media="screen" href="{{theme_asset(path: 'public/assets/front-end/css/slick.css')}}">
    <link rel="stylesheet" href="{{theme_asset(path: 'public/assets/back-end/css/toastr.css')}}"/>
    <link rel="stylesheet" href="{{theme_asset(path: 'public/assets/front-end/css/master.css')}}"/>
</head>
<body class="toolbar-enabled">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="loading" class="d-none">
                <div class="__inline-19">
                    <img width="200" src="{{theme_asset(path: 'public/assets/front-end/img/loader.gif')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
@yield('content')
<a class="btn-scroll-top" href="javascript:" data-scroll>
    <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2">{{translate('top')}}</span>
    <i class="btn-scroll-top-icon czi-arrow-up"> </i>
</a>

<script src="{{theme_asset(path: 'public/assets/front-end/vendor/jquery/dist/jquery-2.2.4.min.js')}}"></script>
<script src="{{theme_asset(path: 'public/assets/front-end/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{theme_asset(path: 'public/assets/front-end/js/theme.min.js')}}"></script>
<script src="{{theme_asset(path: 'public/assets/front-end/js/slick.min.js')}}"></script>
<script src="{{theme_asset(path: 'public/assets/front-end/js/sweet_alert.js')}}"></script>
<script src={{theme_asset(path: "public/assets/back-end/js/toastr.js")}}></script>
{!! Toastr::message() !!}
<script>
    'use strict';
    function currency_select(val)
    {
        if(val==='multi_currency'){
            toastr.warning("Multi-currency is depends on exchange rate and your gateway configuration, So if you don't need multi-currency it will be better select single currency. (We prefer to use single currency).", {
            CloseButton: true,
            ProgressBar: true
        });
        }
    }
</script>
</body>
</html>
