@extends('theme-views.layouts.app')

@section('title', translate('About_Us').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@push('css_or_js')
    <meta property="og:image" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="About {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <meta property="twitter:card" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="about {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
@endpush
@section('content')
    <main class="main-content d-flex flex-column gap-3 pb-3">
        <div class="page-title overlay py-5 __opacity-half background-custom-fit"
             @if ($pageTitleBanner)
                 @if (File::exists(base_path('storage/app/public/banner/'.json_decode($pageTitleBanner['value'])->image)))
                     data-bg-img="{{ dynamicStorage(path: 'storage/app/public/banner/'.json_decode($pageTitleBanner['value'])->image) }}"
                 @else
                     data-bg-img="{{theme_asset('assets/img/media/page-title-bg.png')}}"
                 @endif
             @else
                 data-bg-img="{{theme_asset('assets/img/media/page-title-bg.png')}}"
            @endif
        >
            <div class="container">
                <h1 class="absolute-white text-center text-capitalize">{{translate('about_our_company')}}</h1>
            </div>
        </div>
        <div class="container">
            <div class="card my-4">
                <div class="card-body p-lg-4 text-dark page-paragraph">
                    {!! $aboutUs !!}
                </div>
            </div>
        </div>
    </main>
@endsection
