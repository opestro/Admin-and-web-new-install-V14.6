@extends('layouts.front-end.app')

@section('title', translate('about_us'))

@push('css_or_js')
    <meta property="og:image" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="About {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <meta property="twitter:card" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="about {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
@endpush

@section('content')
    <div class="container for-container rtl">
        <h2 class="text-center py-4 fs-24 font-semi-bold text-capitalize">{{ translate('about_Our_Company')}}</h2>


        @if(!empty($aboutUs))
            <div class="for-padding text-justify">
                {!! $aboutUs !!}
            </div>
        @else
            <div class="d-flex flex-column justify-content-center align-items-center gap-3">
                <img src="{{ dynamicStorage(path: 'public/assets/front-end/img/empty-icons/empty-about-us.svg') }}"
                     alt="{{ translate('brand') }}" class="img-fluid" width="100">
                <h5 class="text-muted fs-14 font-semi-bold text-center">{{ translate('there_is_no_about_us') }}</h5>
            </div>
        @endif
    </div>
@endsection
