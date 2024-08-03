@php
    use App\Utils\Helpers;
@endphp
@extends('theme-views.layouts.app')
@section('title', translate('my_Profile').' | '.$web_config['name']->value.' '.translate('ecommerce'))
@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex gap-3 flex-wrap flex-grow-1">
                                <div class="card border flex-grow-1">
                                    <div class="card-body grid-center">
                                        <div class="text-center">
                                            <h3 class="mb-2">{{ $total_order }}</h3>
                                            <div class="d-flex align-items-center gap-2">
                                                <img width="16"
                                                     src="{{ theme_asset('assets/img/icons/profile-icon2.png') }}"
                                                     class="dark-support" alt="">
                                                <span>{{translate('Orders')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border flex-grow-1">
                                    <div class="card-body grid-center">
                                        <div class="text-center">
                                            <h3 class="mb-2">{{ $wishlists }}</h3>
                                            <div class="d-flex align-items-center gap-2">
                                                <img width="16"
                                                     src="{{ theme_asset('assets/img/icons/profile-icon3.png') }}"
                                                     class="dark-support" alt="">
                                                <span>{{translate('wishlist')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border flex-grow-1">
                                    <div class="card-body grid-center">
                                        <div class="text-center">
                                            <h3 class="mb-2">{{ Helpers::currency_converter($total_wallet_balance) }}</h3>
                                            <div class="d-flex align-items-center gap-2">
                                                <img width="16"
                                                     src="{{theme_asset('assets/img/icons/profile-icon5.png')}}"
                                                     class="dark-support" alt="">
                                                <span>{{translate('wallet')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border flex-grow-1">
                                    <div class="card-body grid-center">
                                        <div class="text-center">
                                            <h3 class="mb-2">{{$total_loyalty_point}}</h3>
                                            <div class="d-flex align-items-center gap-2">
                                                <img width="16"
                                                     src="{{theme_asset('assets/img/icons/profile-icon6.png')}}"
                                                     class="dark-support" alt="">
                                                <span class="text-capitalize">{{translate('loyalty_point')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-30 bg-light rounded p-3">
                                <div class="d-flex align-items-center flex-wrap justify-content-between gap-3">
                                    <h5>{{translate('Personal_Details')}}</h5>
                                    <a href="{{route('user-account')}}"
                                       class="btn btn-outline-secondary rounded-pill px-3 px-sm-4"><span
                                            class="d-none d-sm-inline-block text-capitalize">{{ translate('edit_profile') }}</span> <i
                                            class="bi bi-pencil-square"></i></a>
                                </div>

                                <div class="mt-4">
                                    <div class="row text-dark">
                                        <div class="col-md-6 col-xl-6 col-lg-6">
                                            <dl class="mb-0 flexible-grid width--7rem">
                                                <dt class="pe-1 text-capitalize">{{translate('first_name')}}</dt>
                                                <dd>{{$customer_detail['f_name']}}</dd>

                                                <dt class="pe-1 text-capitalize">{{translate('last_name')}}</dt>
                                                <dd>{{$customer_detail['l_name']}}</dd>
                                            </dl>
                                        </div>
                                        <div class="col-md-6 col-xl-6 col-lg-6">
                                            <dl class="mb-0 flexible-grid width--7rem">
                                                <dt class="pe-1">{{translate('phone')}}</dt>
                                                <dd><a href="tel:{{$customer_detail['phone']}}"
                                                       class="text-dark">{{$customer_detail['phone']}}</a></dd>

                                                <dt class="pe-1">{{translate('email')}}</dt>
                                                <dd><a href="mailto:{{$customer_detail['email']}}"
                                                       class="text-dark">{{$customer_detail['email']}}</a></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5 class="text-capitalize">{{translate('my_addresses')}}</h5>
                                <a href="{{route('account-address-add')}}"
                                   class="btn btn-outline-secondary rounded-pill px-3 px-sm-4">
                                    <span class="d-none d-sm-inline-block text-capitalize">{{translate('add_address')}}</span>
                                        <i class="bi bi-geo-alt-fill"></i>
                                </a>
                            </div>
                            <div class="mt-3">
                                <div class="row gy-3 text-dark">
                                    @foreach($addresses as $address)
                                        <div class="col-md-6">
                                            <div class="card border-0">
                                                <div
                                                    class="card-header gap-2 align-items-center d-flex justify-content-between">
                                                    <h6>{{translate($address['address_type'])}}
                                                        ({{ $address['is_billing'] == 1 ? translate('billing_address'):translate('shipping_address')}})</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <a href="{{route('address-edit',$address->id)}}" class="p-0 bg-transparent border-0">
                                                            <img src="{{theme_asset('assets/img/svg/location-edit.svg')}}"
                                                                alt="" class="svg">
                                                        </a>

                                                        <a href="javascript:"
                                                           data-action="{{route('address-delete',['id'=>$address->id])}}"
                                                           data-message="{{translate('want_to_delete_this_address').'?'}}"
                                                           class="p-0 bg-transparent border-0 delete-action">
                                                            <img src="{{theme_asset('assets/img/svg/delete.svg')}}"
                                                                 alt="" class="svg">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <address>
                                                        <dl class="mb-0 flexible-grid width--5rem">
                                                            <dt>{{translate('name')}}</dt>
                                                            <dd>{{$address['contact_person_name']}}</dd>

                                                            <dt>{{translate('phone')}}</dt>
                                                            <dd><a href="javascript:" class="text-dark">{{$address['phone']}}</a>
                                                            </dd>
                                                            <dt>{{translate('address')}}</dt>
                                                            <dd>{{$address['address']}}</dd>
                                                        </dl>
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if(count($addresses) == 0)
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 w-100">
                                            <img width="60" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-address.svg') }}" alt="">
                                            <h5 class="text-center text-muted">
                                                {{ translate('No_address_found') }}!
                                            </h5>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
