@extends('layouts.front-end.app')

@section('title', translate('track_Order_Result'))

@push('css_or_js')
    <meta property="og:image" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <meta property="twitter:card" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <link rel="stylesheet" media="screen" href="{{theme_asset(path: 'public/assets/front-end/vendor/nouislider/distribute/nouislider.min.css')}}"/>
@endpush

@section('content')
    <div class="container rtl pt-4 pb-5 text-align-direction tracking-page">
        <div class="card border-0 box-shadow-lg">
            <div class="card-body py-5">
                <h6 class="text-end small font-bold fs-14">
                    <a href="{{ route('track-order.index') }}">
                        <span class="text-primary"><i class="tio-refresh"></i></span>
                        {{ translate('clear') }}
                    </a>
                </h6>
                <div class="mw-1000 mx-auto">
                    <h3 class="text-center text-capitalize font-bold fs-25">{{translate('track_order')}}</h3>
                    <form action="{{ route('track-order.result') }}" type="submit" method="post" class="p-3">
                        @csrf

                        @if(session()->has('Error'))
                            <div class="alert alert-danger alert-block">
                                <span type="" class="closet __closet" data-dismiss="alert">×</span>
                                <strong>{{ session()->get('Error') }}</strong>
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-4 col-sm-6">
                                <input class="form-control form-control-sm prepended-form-control" type="number" value="{{ request('order_id') }}" name="order_id"
                                    placeholder="{{translate('order_id')}}" required>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <input class="form-control form-control-sm prepended-form-control" name="phone_number" type="tel" value="{{ request('phone_number') }}"
                                    placeholder="{{translate('your_phone_number')}}" required>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn--primary btn-sm w-100 font-bold" type="submit" name="trackOrder">{{translate('track_order')}}</button>
                            </div>
                        </div>
                        <div class="mt-5 pt-md-5 mx-auto text-center max-width-350px">
                            <img class="mb-2" src="{{theme_asset(path: 'public/assets/front-end/img/track-truck.svg')}}" alt="">
                            <div class="opacity-50">
                                {{translate('enter_your_order_ID_&_phone_number_to_get_delivery_updates')}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script src="{{theme_asset(path: 'public/assets/front-end/vendor/nouislider/distribute/nouislider.min.js')}}"></script>
@endpush
