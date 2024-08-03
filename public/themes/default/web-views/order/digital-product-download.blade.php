@extends('layouts.front-end.app')

@section('title', translate('download_Digital_Product'))

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

    <link rel="stylesheet" media="screen" href="{{theme_asset(path: 'public/assets/front-end/vendor/nouislider/distribute/nouislider.min.css')}}"/>
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/css/intlTelInput.css') }}">
@endpush

@section('content')


    <div class="container pt-4 pb-5 rtl">

        <div class="card border-0 box-shadow-lg">
            <div class="card-body py-5">

                <div class="mx-auto mw-1000">
                    <h2 class="text-center font-bold fs-20 pb-3">{{ translate('download_your_product')}}</h2>

                    <form action="{{ route('digital-product-download-pos.index') }}" type="submit" method="get" class="py-5 border p-4 rounded mb-5">
                        <div class="row g-3">
                            <div class="col-md-5 col-sm-6">
                                <input class="form-control form-control-sm prepended-form-control" type="text" name="order_id"
                                       placeholder="{{ translate('order_ID') }}" value="{{ request('order_id') }}" required>
                            </div>
                            <div class="col-md-5 col-sm-6">
                                <input class="form-control form-control-sm prepended-form-control" type="email"
                                       placeholder="{{ translate('email_address') }}" value="{{ request('email') }}" name="email"
                                       required>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn--primary btn-sm w-100 font-bold" type="submit">
                                    {{ isset($order) ? translate('verified') : translate('verify') }}
                                </button>
                            </div>
                        </div>
                    </form>


                    @if(isset($orderDetails))
                        @if($isDigitalProductExist != 0)
                            @if($isDigitalProductReadyCount == 0)
                                <div class="rounded px-3 py-3 fs-15 text-base font-weight-medium custom-light-primary-color mb-3 d-flex align-items-center gap-3">
                                    <img src="{{ theme_asset('public/assets/front-end/img/icons/info-light.svg') }}" alt="" class="px-2">
                                    <span>
                                        {{ translate('your_digital_product_is_ready.') }}
                                            {{ translate('once_the_seller_has_uploaded_the_product__you_will_be_able_to_download_here_by_using_your_order_info.') }}
                                            {{ translate('if_you_face_any_issue_during_download_please_until_wait_or_contact_admin_via') }}

                                            @if(auth('customer')->check())
                                                <a class="text-base fw-bold text-underline" href="{{route('account-tickets')}}">
                                                {{ translate('support_ticket')}}
                                            </a>
                                            @else
                                                <a class="text-base fw-bold text-underline" href="{{route('customer.auth.login')}}">
                                                {{ translate('support_ticket')}}
                                            </a>
                                            @endif
                                    </span>
                                </div>
                            @else
                                <div class="d-flex flex-column gap-2 bg-secondary p-4 rounded border">
                                    @foreach($orderDetails as $index => $orderDetail)
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                <img width="50" src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$orderDetail->product->thumbnail, type: 'product') }}" alt="" class="border rounded">
                                                <a class="fs-13 font-semi-bold" href="{{ route('product', $orderDetail->product->slug) }}">
                                                    {{ $orderDetail->product->name }}
                                                </a>
                                            </div>
                                            <div>
                                                @php($productDetails = $orderDetail?->product ?? json_decode($orderDetail->product_details) )

                                                @if($productDetails->digital_product_type == 'ready_product')
                                                    @if (File::exists(base_path('storage/app/public/product/digital-product/'. $productDetails->digital_file_ready)))
                                                        <a class="btn p-0" data-toggle="tooltip" title="{{ translate('download') }}" href="{{ dynamicStorage(path: 'storage/app/public/product/digital-product/'.$productDetails->digital_file_ready) }}" download>
                                                            <img src="{{ theme_asset(path: 'public/assets/front-end/img/icons/download-green.svg') }}" alt="">
                                                        </a>
                                                    @else
                                                        <a class="btn p-0" data-toggle="tooltip" title="{{ translate('File_not_found') }}" href="javascript:" download>
                                                            <img src="{{ theme_asset(path: 'public/assets/front-end/img/icons/download-green.svg') }}" alt="">
                                                        </a>
                                                    @endif
                                                @elseif($productDetails->digital_product_type == 'ready_after_sell')
                                                    @if($orderDetail->digital_file_after_sell)
                                                        <a class="btn p-0" data-toggle="tooltip" title="{{ translate('download') }}" href="{{ dynamicStorage(path: 'storage/app/public/product/digital-product/'.$orderDetail->digital_file_after_sell) }}" download>
                                                            <img src="{{ theme_asset(path: 'public/assets/front-end/img/icons/download-green.svg') }}" alt="">
                                                        </a>
                                                    @else
                                                        <a class="btn p-0" href="javascript:" data-toggle="tooltip" title="{{ translate('product_not_uploaded_yet') }}" disabled>
                                                            <img src="{{ theme_asset(path: 'public/assets/front-end/img/icons/download-green.svg') }}" alt="">
                                                        </a>
                                                    @endif
                                                @endif

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="rounded px-3 py-3 fs-15 text-base font-weight-medium custom-light-primary-color mb-3 d-flex align-items-center gap-3">
                                <img src="{{ theme_asset('public/assets/front-end/img/icons/info-light.svg') }}" alt="" class="px-2">
                                <span>
                                    {{ translate('you_have_no_digital_products_in_your_order') }}
                                </span>
                            </div>
                        @endif

                    @endif

                </div>

            </div>
        </div>
    </div>

@endsection
