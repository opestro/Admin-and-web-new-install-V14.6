@php use App\Utils\Helpers; @endphp
@extends('theme-views.layouts.app')
@section('title', translate('coupons').' | '.$web_config['name']->value.' '.translate('ecommerce'))
@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5>{{translate('coupons')}}</h5>
                                <a href="{{ route('user-profile') }}"
                                   class="btn-link text-secondary d-flex align-items-baseline">
                                    <i class="bi bi-chevron-left fs-12"></i> {{translate('go_back')}}
                                </a>
                            </div>
                            <div class="mt-4">
                                <div class="row g-3">
                                    @foreach ($coupons as $item)
                                        <div class="col-md-6">
                                            <div class="ticket-box">
                                                <div class="ticket-start">
                                                    @if ($item->coupon_type == "free_delivery")
                                                        <img width="30"
                                                             src="{{ theme_asset('assets/img/icons/bike.png') }}"
                                                             alt="">
                                                    @elseif ($item->discount_type == "percentage")
                                                        <img width="30"
                                                             src="{{ theme_asset('assets/img/icons/fire.png') }}"
                                                             alt="">
                                                    @elseif ($item->discount_type == "amount")
                                                        <img width="30"
                                                             src="{{ theme_asset('assets/img/icons/dollar.png') }}"
                                                             alt="">
                                                    @endif
                                                    <h2 class="ticket-amount">
                                                        @if ($item->coupon_type == "free_delivery")
                                                            {{ translate('free_Delivery') }}
                                                        @else
                                                            {{ ($item->discount_type == 'percentage')? $item->discount.'%'.translate('off') : Helpers::currency_converter($item->discount)}}
                                                        @endif
                                                    </h2>
                                                    <p class="text-capitalize">
                                                        {{ translate('on') }}
                                                        @if($item->seller_id == '0')
                                                            {{ translate('all_shops') }}
                                                        @elseif($item->seller_id == NULL)
                                                            <a class="shop-name" href="{{route('shopView',['id'=>0])}}">
                                                                {{ $web_config['name']->value }}
                                                            </a>
                                                        @else
                                                            <a class="shop-name"
                                                               href="{{isset($item->seller->shop) ? route('shopView',['id'=>$item->seller->shop['id']]) : 'javascript:'}} ">
                                                                {{ isset($item->seller->shop) ? $item->seller->shop->name : translate('shop_not_found') }}
                                                            </a>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="ticket-border"></div>
                                                <div class="ticket-end click-to-copy-code-div">
                                                    <button
                                                        class="ticket-welcome-btn click-to-copy-code coupon-id coupon-id-{{ $item->code }}"
                                                        data-copy-code="{{ $item->code }}">{{ $item->code }}
                                                    </button>
                                                    <button
                                                        class="ticket-welcome-btn coupon-id-hide coupon-hide-id-{{ $item->code }} d-none">{{ translate('copied') }}</button>
                                                    <h6>{{ translate('valid_till') }} {{ $item->expire_date->format('d M, Y') }}</h6>
                                                    <p class="m-0">{{ translate('available_from_minimum_purchase') }} {{Helpers::currency_converter($item->min_purchase)}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if(count($coupons) == 0)
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 w-100">
                                                <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-coupon.svg') }}" alt="">
                                                <h5 class="text-center text-muted">
                                                    {{ translate('No_coupon_available') }}!
                                                </h5>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-12 mt-5">
                                        {{ $coupons->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
