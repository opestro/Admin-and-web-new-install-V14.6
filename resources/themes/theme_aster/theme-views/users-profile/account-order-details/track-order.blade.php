@php use function App\Utils\order_status_history; @endphp
@extends('theme-views.layouts.app')

@section('title', translate('order_details').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-4">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            @include('theme-views.users-profile.account-order-details._order-details-head', ['order'=>$orderDetails])
                            <div class="mt-4 card px-xl-5">
                                <div class="card-body mb-xl-5">
                                        <div class="pt-3">
                                            <div id="timeline">
                                                <div
                                                    @if($orderDetails['order_status']=='processing')
                                                        class="bar progress two"
                                                    @elseif($orderDetails['order_status']=='out_for_delivery')
                                                        class="bar progress three"
                                                    @elseif($orderDetails['order_status']=='delivered')
                                                        class="bar progress four"
                                                    @elseif(in_array($orderDetails['order_status'], ['returned', 'canceled', 'failed']))
                                                        class="bar progress four"
                                                    @else
                                                        class="bar progress one"
                                                    @endif
                                                ></div>
                                                <div class="state" style="{{ in_array($orderDetails['order_status'], ['returned', 'canceled', 'failed']) ? '--items: 2;' : '' }}">
                                                    <ul>
                                                        <li>
                                                            <div class="state-img">
                                                                <img width="30" class="dark-support" alt=""
                                                                     src="{{ theme_asset('assets/img/icons/track1.png') }}">
                                                            </div>
                                                            <div class="badge active">
                                                                <span>{{ translate('1') }}</span>
                                                                <i class="bi bi-check"></i>
                                                            </div>
                                                            <div>
                                                                <div class="state-text">
                                                                    {{ translate('order_placed') }}
                                                                </div>
                                                                <div class="mt-2 fs-12">
                                                                    {{ date('d M, Y h:i A',strtotime($orderDetails->created_at)) }}
                                                                </div>
                                                            </div>
                                                        </li>

                                                        @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed' && $orderDetails['order_status']!='canceled')
                                                            <li>
                                                                <div class="state-img">
                                                                    <img width="30"
                                                                         src="{{theme_asset('assets/img/icons/track2.png')}}"
                                                                         class="dark-support" alt="">
                                                                </div>
                                                                <div class="{{($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                                    <span>{{translate('2')}}</span>
                                                                    <i class="bi bi-check"></i>
                                                                </div>
                                                                <div>
                                                                    <div
                                                                        class="state-text">{{translate('Packaging_order')}}</div>
                                                                    @if(($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                                                        <div class="mt-2 fs-12">
                                                                            @if(order_status_history($orderDetails['id'],'processing'))
                                                                                {{date('d M, Y h:i A',strtotime(order_status_history($orderDetails['id'],'processing')))}}
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="state-img">
                                                                    <img width="30"
                                                                         src="{{theme_asset('assets/img/icons/track4.png')}}"
                                                                         class="dark-support" alt="">
                                                                </div>
                                                                <div
                                                                    class="{{($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                                    <span>{{translate('3')}}</span>
                                                                    <i class="bi bi-check"></i>
                                                                </div>
                                                                <div
                                                                    class="state-text">{{translate('order_is_on_the_way')}}</div>
                                                                @if(($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                                                    <div class="mt-2 fs-12">
                                                                        @if(order_status_history($orderDetails['id'],'out_for_delivery'))
                                                                            {{date('d M, Y h:i A',strtotime(order_status_history($orderDetails['id'],'out_for_delivery')))}}
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </li>
                                                            <li>
                                                                <div class="state-img">
                                                                    <img width="30"
                                                                         src="{{theme_asset('assets/img/icons/track5.png')}}"
                                                                         class="dark-support" alt="">
                                                                </div>
                                                                <div
                                                                    class="{{($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                                    <span>{{translate('4')}}</span>
                                                                    <i class="bi bi-check"></i>
                                                                </div>
                                                                <div
                                                                    class="state-text text-capitalize">{{translate('order_delivered')}}</div>
                                                                @if($orderDetails['order_status']=='delivered')
                                                                    <div class="mt-2 fs-12">
                                                                        @if(order_status_history($orderDetails['id'], 'delivered'))
                                                                            {{date('d M, Y h:i A',strtotime(order_status_history($orderDetails['id'], 'delivered')))}}
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </li>
                                                        @elseif(in_array($orderDetails['order_status'], ['returned', 'canceled']))
                                                            <li>
                                                                <div class="state-img">
                                                                    <img width="30"
                                                                         src="{{theme_asset('assets/img/icons/'.$orderDetails['order_status'].'.png')}}"
                                                                         class="dark-support" alt="">
                                                                </div>
                                                                <div class="badge active">
                                                                    <span>{{translate('2')}}</span>
                                                                    <i class="bi bi-check"></i>
                                                                </div>
                                                                <div class="state-text">
                                                                    {{ translate('order') }} {{ translate($orderDetails['order_status']) }}
                                                                </div>

                                                                @if(\App\Utils\order_status_history($orderDetails['id'], $orderDetails['order_status']))
                                                                    <div class="mt-2 fs-12">
                                                                        {{ date('h:i A, d M Y', strtotime(\App\Utils\order_status_history($orderDetails['id'], $orderDetails['order_status']))) }}
                                                                    </div>
                                                                @endif
                                                            </li>
                                                        @else
                                                            <li>
                                                                <div class="state-img">
                                                                    <img width="30"
                                                                         src="{{theme_asset('assets/img/icons/'.$orderDetails['order_status'].'.png')}}"
                                                                         class="dark-support" alt="">
                                                                </div>
                                                                <div class="badge active">
                                                                    <span>{{translate('2')}}</span>
                                                                    <i class="bi bi-check"></i>
                                                                </div>
                                                                <div class="state-text">
                                                                    {{ translate('order') }} {{ translate($orderDetails['order_status']) }}
                                                                </div>

                                                                @if(\App\Utils\order_status_history($orderDetails['id'], $orderDetails['order_status']))
                                                                    <div class="mt-2 fs-12">
                                                                        {{ date('h:i A, d M Y', strtotime(\App\Utils\order_status_history($orderDetails['id'], $orderDetails['order_status']))) }}
                                                                    </div>
                                                                @endif
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed' && $orderDetails['order_status']!='canceled')
                                        <div class="mt-5">
                                            <div class="row">

                                                <div class="col-lg-6">
                                                    <address class="media gap-2">
                                                        <img width="20"
                                                             src="{{theme_asset('assets/img/icons/location.png')}}"
                                                             class="dark-support" alt="">
                                                        <div class="media-body">
                                                            <div
                                                                class="mb-2 fw-bold fs-16 text-capitalize">{{translate('shipping_address')}}</div>
                                                            @if($orderDetails->shippingAddress)
                                                                @php($shipping=$orderDetails->shippingAddress)
                                                            @else
                                                                @php($shipping=$orderDetails['shipping_address_data'])
                                                            @endif
                                                            <p> @if($shipping)
                                                                    {{$shipping->address}},<br>
                                                                    {{$shipping->city}}
                                                                    , {{$shipping->zip}}

                                                                @endif
                                                            </p>
                                                        </div>
                                                    </address>
                                                </div>
                                                <div class="col-lg-6">
                                                    <address class="media gap-2">
                                                        <img width="20"
                                                             src="{{theme_asset('assets/img/icons/location.png')}}"
                                                             class="dark-support" alt="">
                                                        <div class="media-body">
                                                            <div
                                                                class="mb-2  fw-bold fs-16 text-capitalize">{{translate('billing_address')}}</div>
                                                            @if($orderDetails->billingAddress)
                                                                @php($billing=$orderDetails->billingAddress)
                                                            @else
                                                                @php($billing=$orderDetails['billing_address_data'])
                                                            @endif
                                                            <p>
                                                                @if($billing)
                                                                    {{ $billing->address ?? '' }}, <br>
                                                                    {{ $billing->city ?? '' }}
                                                                    , {{ $billing->zip ?? '' }}
                                                                @else
                                                                    {{ $shipping->address ?? '' }},<br>
                                                                    {{ $shipping->city ?? '' }}
                                                                    , {{ $shipping->zip ?? '' }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

