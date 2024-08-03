@extends('layouts.front-end.app')

@section('title', translate('My_Shopping_Cart'))

@push('css_or_js')
    <meta property="og:image" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <link rel="stylesheet" href="{{dynamicStorage(path: 'public/assets/front-end/css/shop-cart.css')}}">
@endpush

@section('content')
    <div class="container mt-3 rtl px-0 px-md-3 text-align-direction" id="cart-summary">
        @include(VIEW_FILE_NAMES['products_cart_details_partials'])
    </div>

    <span id="get-cart-select-cart-items" data-route="{{ route('cart.select-cart-items') }}"></span>
@endsection

@push('script')
    <script>
        cartQuantityInitialize();
    </script>
@endpush
