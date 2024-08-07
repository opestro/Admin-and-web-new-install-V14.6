@extends('layouts.front-end.app')

@section('title', translate('about_us'))

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
