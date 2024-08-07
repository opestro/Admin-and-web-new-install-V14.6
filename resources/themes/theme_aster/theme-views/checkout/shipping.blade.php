@extends('theme-views.layouts.app')

@section('title', translate('shopping_Details').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <h4 class="text-center mb-3 text-capitalize">{{ translate('shipping_details') }}</h4>
            <div class="row">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-body  px-sm-4">
                            <div class="d-flex justify-content-center mb-30">
                                <ul class="cart-step-list">
                                    <li class="done cursor-pointer get-view-by-onclick"
                                        data-link="{{route('shop-cart')}}"><span><i
                                                class="bi bi-check2"></i></span> {{ translate('cart') }}</li>
                                    <li class="current cursor-pointer get-view-by-onclick text-capitalize"
                                        data-link="{{ route('checkout-details') }}"><span><i
                                                class="bi bi-check2"></i></span> {{ translate('shipping_details') }}
                                    </li>
                                    <li><span><i class="bi bi-check2"></i></span> {{ translate('payment') }}</li>
                                </ul>
                            </div>
                            <input type="hidden" id="physical-product" name="physical_product"
                                   value="{{ $physical_product_view ? 'yes':'no'}}">
                            <input type="hidden" id="billing-input-enable" name="billing_input_enable"
                                   value="{{ $billing_input_by_customer }}">
                            @if($physical_product_view)
                                <form method="post" id="address-form">
                                    <h5 class="mb-3 text-capitalize">{{ translate('delivery_information_details') }}</h5>
                                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                                        <div class="d-flex flex-wrap gap-3 align-items-center">
                                            <h6 class="text-capitalize">{{ translate('Shipping_Address') }}</h6>
                                        </div>
                                        <div class="d-flex flex-wrap gap-3 align-items-center">
                                            @if(getWebConfig('map_api_status') == 1)
                                                <a href="javascript:" type="button" data-bs-toggle="modal"
                                                   data-bs-target="#shippingMapModal"
                                                   class="btn-link text-primary text-capitalize">{{ translate('set_form_map') }}
                                                    <i class="bi bi-geo-alt-fill"></i>
                                                </a>
                                                <div class="modal fade" id="shippingMapModal" tabindex="-1"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <div class="product-quickview">
                                                                    <button type="button" class="btn-close outside"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    <input id="pac-input"
                                                                           class="controls rounded __inline-46"
                                                                           title="{{translate('search_your_location_here')}}"
                                                                           type="text"
                                                                           placeholder="{{translate('search_here')}}"/>
                                                                    <div class="dark-support rounded w-100 __h-14rem"
                                                                         id="location_map_canvas"></div>
                                                                    <input type="hidden" id="latitude"
                                                                           name="latitude" class="form-control d-inline"
                                                                           placeholder="{{ translate('ex') }} : {{ translate('-94.22213') }}"
                                                                           value="{{$default_location?$default_location['lat']:0}}"
                                                                           required readonly>
                                                                    <input type="hidden"
                                                                           name="longitude" class="form-control"
                                                                           placeholder="{{ translate('ex') }} : {{ translate('103.344322') }}"
                                                                           id="longitude"
                                                                           value="{{$default_location?$default_location['lng']:0}}"
                                                                           required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if(auth('customer')->check())
                                                <a href="javascript:" type="button" data-bs-toggle="modal"
                                                   data-bs-target="#shippingSavedAddressModal"
                                                   class="btn-link text-primary text-capitalize">{{ translate('select_from_saved') }}</a>
                                            @endif

                                        </div>
                                    </div>

                                    @if(auth('customer')->check())
                                        <div class="modal fade" id="shippingSavedAddressModal" data-bs-backdrop="static"
                                             data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered justify-content-center">
                                                <div class="modal-content border-0">
                                                    <div class="modal-header">
                                                        <h5 class="text-capitalize"
                                                            id="contact_sellerModalLabel">{{translate('saved_addresses')}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body custom-scrollbar">
                                                        <div class="product-quickview">
                                                            <div
                                                                class="shipping-saved-addresses {{ $shipping_addresses->count()<1 ? 'd--none':'' }}">
                                                                <div class="row gy-3 text-dark py-4">
                                                                    @foreach($shipping_addresses as $key=>$address)
                                                                        <div class="col-md-12">
                                                                            <div class="card border-0">
                                                                                <div
                                                                                    class="card-header bg-transparent gap-2 align-items-center d-flex flex-wrap justify-content-between">
                                                                                    <label
                                                                                        class="d-flex align-items-center gap-3 cursor-pointer mb-0">
                                                                                        <input type="radio"
                                                                                               name="shipping_method_id"
                                                                                               value="{{$address['id']}}" {{$key==0?'checked':''}}>
                                                                                        <h6>{{$address['address_type']}}</h6>
                                                                                    </label>
                                                                                    <div
                                                                                        class="d-flex align-items-center gap-3">
                                                                                        <button type="button"
                                                                                                onclick="location.href='{{ route('address-edit', ['id' => $address->id]) }}'"
                                                                                                class="p-0 bg-transparent border-0">
                                                                                            <img
                                                                                                src="{{ theme_asset('assets/img/svg/location-edit.svg') }}"
                                                                                                alt="" class="svg">
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <address>
                                                                                        <dl class="mb-0 flexible-grid sm-down-1 width--5rem">
                                                                                            <dt>{{ translate('name') }}</dt>
                                                                                            <dd class="shipping-contact-person">{{$address['contact_person_name']}}</dd>

                                                                                            <dt>{{ translate('phone') }}</dt>
                                                                                            <dd class="">
                                                                                                <a href="tel:{{$address['phone']}}"
                                                                                                   class="text-dark shipping-contact-phone">{{$address['phone']}}</a>
                                                                                            </dd>

                                                                                            <dt>{{ translate('address') }}</dt>
                                                                                            <dd>{{$address['address']}}
                                                                                                , {{$address['city']}}
                                                                                                , {{$address['zip']}}</dd>
                                                                                            <span
                                                                                                class="shipping-contact-address d-none">{{ $address['address'] }}</span>
                                                                                            <span
                                                                                                class="shipping-contact-city d-none">{{ $address['city'] }}</span>
                                                                                            <span
                                                                                                class="shipping-contact-zip d-none">{{ $address['zip'] }}</span>
                                                                                            <span
                                                                                                class="shipping-contact-country d-none">{{ $address['country'] }}</span>
                                                                                            <span
                                                                                                class="shipping-contact-address-type d-none">{{ $address['address_type'] }}</span>
                                                                                        </dl>
                                                                                    </address>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="text-center {{ $shipping_addresses->count()>0 ? 'd--none':'' }}">
                                                                <img src="{{theme_asset('assets/img/svg/address.svg')}}"
                                                                     alt="address" class="w-25">
                                                                <h5 class="my-3 pt-1 text-muted">
                                                                    {{translate('no_address_is_saved')}}!
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">{{ translate('close') }}</button>
                                                        <button type="button" class="btn btn-primary"
                                                                data-bs-dismiss="modal">{{ translate('save') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="card">
                                        <div class="card-body" id="collapseThree">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row mb-30">
                                                        <div
                                                            class="col-sm-@if(auth('customer')->check()) '6' @else '12' @endif">
                                                            <div class="form-group mb-3">
                                                                <label for="name"
                                                                       class="text-capitalize">{{ translate('contact_person_name')}}</label>
                                                                <input type="text" name="contact_person_name" id="name"
                                                                       class="form-control"
                                                                       placeholder="{{ translate('ex') }}: {{translate('Jhon_Doe')}}" {{$shipping_addresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="phone">{{ translate('phone') }}</label>
                                                                <input type="tel" id="phoneNumber"
                                                                       class="form-control phone-input-with-country-picker-shipping"
                                                                       placeholder="{{ translate('ex') }}: {{translate('+8801000000000')}}" {{$shipping_addresses->count()==0?'required':''}}>
                                                                <input type="hidden" class="country-picker-phone-number-shipping w-50" name="phone" readonly>
                                                            </div>
                                                        </div>
                                                        @if(!auth('customer')->check())
                                                            <div class="col-sm-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="email">{{ translate('email') }}</label>
                                                                    <input type="email" name="email" id="email"
                                                                           class="form-control"
                                                                           placeholder="{{ translate('ex') }}: {{translate('email@domain.com')}}"
                                                                           required>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="address-type"
                                                                       class="text-capitalize">{{ translate('address_type')}}</label>
                                                                <select name="address_type" id="address-type"
                                                                        class="form-select">
                                                                    <option
                                                                        value="permanent">{{ translate('permanent')}}</option>
                                                                    <option value="home">{{ translate('home')}}</option>
                                                                    <option
                                                                        value="others">{{ translate('others')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="country">{{ translate('country') }}</label>
                                                                <select name="country" id="country"
                                                                        class="form-control select_picker select2">
                                                                    @forelse($countries as $country)
                                                                        <option
                                                                            value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                                                    @empty
                                                                        <option
                                                                            value="">{{ translate('no_country_to_deliver') }}</option>
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="city">{{ translate('city') }}</label>
                                                                <input type="text" name="city" id="city"
                                                                       placeholder="{{ translate('ex') }}: {{translate('dhaka')}}"
                                                                       class="form-control" {{$shipping_addresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group mb-3">
                                                                <label for="city"
                                                                       class="text-capitalize">{{ translate('zip_code') }}</label>
                                                                @if($zip_restrict_status == 1)
                                                                    <select name="zip" id="zip"
                                                                            class="form-control select2 select_picker"
                                                                            data-live-search="true" required>
                                                                        @forelse($zip_codes as $code)
                                                                            <option
                                                                                value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                                        @empty
                                                                            <option
                                                                                value="">{{ translate('no_zip_to_deliver') }}</option>
                                                                        @endforelse
                                                                    </select>
                                                                @else
                                                                    <input type="text" class="form-control" id="zip"
                                                                           name="zip"
                                                                           placeholder="{{ translate('ex') }}: {{translate('1216')}}" {{$shipping_addresses->count()==0?'required':''}}>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group mb-3">
                                                                <label for="address">{{ translate('address') }}</label>
                                                                <div
                                                                    class="form-control focus-border rounded d-flex align-items-center">
                                                                    <input type="text" name="address" id="address"
                                                                           class="flex-grow-1 text-dark bg-transparent border-0 focus-input"
                                                                           placeholder="{{ translate('your_address') }}" {{$shipping_addresses->count()==0?'required':''}}>

                                                                    @if(getWebConfig('map_api_status') == 1)
                                                                    <div class="border-start ps-3 pe-1"
                                                                         data-bs-toggle="modal"
                                                                         data-bs-target="#shippingMapModal">
                                                                        <i class="bi bi-compass-fill"></i>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12">
                                                            <label class="custom-checkbox align-items-center"
                                                                   id="save-address-label">
                                                                <input type="hidden" name="shipping_method_id"
                                                                       id="shipping-method-id" value="0">
                                                                @if(auth('customer')->check())
                                                                    <input type="checkbox" name="save_address"
                                                                           id="saveAddress">
                                                                    {{ translate('save_this_address') }}
                                                                @endif
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>

                                @if(!Auth::guard('customer')->check() && $web_config['guest_checkout_status'])
                                    <div class="card __card mt-3">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center flex-wrap justify-content-between gap-3">
                                                <div class="min-h-45 d-flex gap-2 align-items-center cursor-pointer user-select-none">
                                                    <input type="checkbox" id="is_check_create_account" name="is_check_create_account">
                                                    <label class="fw-bold fs-13 mb-0" for="is_check_create_account">
                                                        {{translate('Create_an_account_with_the_above_info')}}
                                                    </label>
                                                </div>

                                                <div class="is_check_create_account_password_group d--none">
                                                    <div class="d-flex gap-3 flex-wrap flex-sm-nowrap">
                                                        <div class="">
                                                            <div class="input-inner-end-ele">
                                                                <input name="customer_password" type="password" id="customer_password" class="form-control" placeholder="{{ translate('new_Password') }}" required="">
                                                                <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <div class="input-inner-end-ele">
                                                                <input name="customer_confirm_password" type="password" id="customer_confirm_password" class="form-control" placeholder="{{ translate('confirm_Password') }}" required="">
                                                                <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @endif

                            @if($billing_input_by_customer)
                                <div class="mt-5 {{ $billing_input_by_customer ? '':'d-none' }}">
                                    <div class="bg-light rounded p-3 mt-20">
                                        <div class="d-flex flex-wrap justify-content-between gap-3">
                                            <div class="d-flex gap-3 align-items-center">
                                                <h6 class="text-capitalize">{{ translate('billing_address') }}</h6>
                                            </div>

                                            @if($physical_product_view)
                                                <label class="custom-checkbox" class="text-capitalize">
                                                    {{ translate('same_as_delivery_address') }}
                                                    <input type="checkbox" id="same-as-shipping-address"
                                                           name="same_as_shipping_address"
                                                           class="billing-address-checkbox" {{$billing_input_by_customer==1?'':'checked'}}>
                                                </label>
                                            @endif
                                        </div>
                                    </div>

                                    @if(!$physical_product_view)
                                        <div class="my-3 alert--info">
                                            <div class="d-flex align-items-center gap-2">
                                                <img class="mb-1" src="{{ theme_asset('assets/img/icons/info-light.svg') }}" alt="Info">
                                                <span>{{ translate('When_you_input_all_the_required_information_for_this_billing_address_it_will_be_stored_for_future_purchases') }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <form method="post" id="billing-address-form">
                                        <div class="toggle-billing-address mt-3" id="hide-billing-address">
                                            <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                                </div>
                                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                                    @if(getWebConfig('map_api_status') == 1)
                                                        <a href="javascript:" data-bs-toggle="modal"
                                                           data-bs-target="#billingMapModal"
                                                           class="btn-link text-primary text-capitalize">
                                                            {{ translate('set_form_map') }}
                                                            <i class="bi bi-geo-alt-fill"></i>
                                                        </a>
                                                        <div class="modal fade" id="billingMapModal" tabindex="-1"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <div class="product-quickview">
                                                                        <button type="button" class="btn-close outside"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        <input id="pac-input-billing"
                                                                               class="controls rounded __inline-46"
                                                                               title="{{translate('search_your_location_here')}}"
                                                                               type="text"
                                                                               placeholder="{{translate('search_here')}}"/>
                                                                        <div
                                                                            class="dark-support rounded w-100 __h-14rem"
                                                                            id="billing-location-map-canvas"></div>
                                                                        <input type="hidden" id="billing-latitude"
                                                                               name="billing_latitude"
                                                                               class="form-control d-inline"
                                                                               placeholder="{{translate('ex')}} : {{translate('-94.22213')}}"
                                                                               value="{{$default_location?$default_location['lat']:0}}"
                                                                               required readonly>
                                                                        <input type="hidden"
                                                                               name="billing_longitude"
                                                                               class="form-control"
                                                                               placeholder="{{ translate('ex') }} : {{translate('103.344322')}}"
                                                                               id="billing-longitude"
                                                                               value="{{$default_location?$default_location['lng']:0}}"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if(auth('customer')->check())
                                                        <a href="javascript:" type="button" data-bs-toggle="modal"
                                                           data-bs-target="#billingSavedAddressModal"
                                                           class="btn-link text-primary text-capitalize">{{ translate('select_from_saved') }}</a>
                                                    @endif
                                                </div>
                                            </div>

                                            @if(auth('customer')->check())
                                                <div class="modal fade" id="billingSavedAddressModal"
                                                     data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                     aria-hidden="true">
                                                    <div
                                                        class="modal-dialog modal-lg modal-dialog-centered justify-content-center">
                                                        <div class="modal-content border-0 max-width-500">
                                                            <div class="modal-header">
                                                                <h5 class="text-capitalize"
                                                                    id="contact_sellerModalLabel">{{translate('saved_addresses')}}</h5>
                                                                <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body custom-scrollbar">
                                                                <div class="product-quickview">
                                                                    <div
                                                                        class="billing-saved-addresses {{ $billing_addresses->count()<1 ? 'd--none':'' }}">
                                                                        <div class="row gy-3 text-dark py-4">
                                                                            @foreach($billing_addresses as $key=>$address)
                                                                                <div class="col-md-12">
                                                                                    <div class="card border-0 ">
                                                                                        <div
                                                                                            class="card-header bg-transparent gap-2 align-items-center d-flex flex-wrap justify-content-between">
                                                                                            <label
                                                                                                class="d-flex align-items-center gap-3 cursor-pointer mb-0">
                                                                                                <input type="radio"
                                                                                                       value="{{$address['id']}}"
                                                                                                       name="billing_method_id" {{$key==0?'checked':''}}>
                                                                                                <h6>{{$address['address_type']}}</h6>
                                                                                            </label>
                                                                                            <div
                                                                                                class="d-flex align-items-center gap-3">
                                                                                                <button type="button"
                                                                                                        onclick="location.href='{{ route('address-edit', ['id' => $address->id]) }}'"
                                                                                                        class="p-0 bg-transparent border-0">
                                                                                                    <img
                                                                                                        src="{{ theme_asset('assets/img/svg/location-edit.svg') }}"
                                                                                                        alt=""
                                                                                                        class="svg">
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="card-body pb-0">
                                                                                            <address>
                                                                                                <dl class="mb-0 flexible-grid sm-down-1 width--5rem">
                                                                                                    <dt>{{ translate('name') }}</dt>
                                                                                                    <dd class="billing-contact-name">{{$address['contact_person_name']}}</dd>

                                                                                                    <dt>{{ translate('phone') }}</dt>
                                                                                                    <dd class="">
                                                                                                        <a href="tel:{{$address['phone']}}"
                                                                                                           class="text-dark billing-contact-phone">{{$address['phone']}}</a>
                                                                                                    </dd>

                                                                                                    <dt>{{ translate('address') }}</dt>
                                                                                                    <dd>{{$address['address']}}
                                                                                                        , {{$address['city']}}
                                                                                                        , {{$address['zip']}}</dd>
                                                                                                    <span
                                                                                                        class="billing-contact-address d-none">{{ $address['address'] }}</span>
                                                                                                    <span
                                                                                                        class="billing-contact-city d-none">{{ $address['city'] }}</span>
                                                                                                    <span
                                                                                                        class="billing-contact-zip d-none">{{ $address['zip'] }}</span>
                                                                                                    <span
                                                                                                        class="billing-contact-country d-none">{{ $address['country'] }}</span>
                                                                                                    <span
                                                                                                        class="billing-contact-address-type d-none">{{ $address['address_type'] }}</span>
                                                                                                </dl>
                                                                                            </address>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="text-center {{ $billing_addresses->count()>0 ? 'd--none':'' }}">
                                                                        <img
                                                                            src="{{theme_asset('assets/img/svg/address.svg')}}"
                                                                            alt="address" class="w-25">
                                                                        <h5 class="my-3 pt-1 text-muted">
                                                                            {{translate('no_address_is_saved')}}!
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">{{ translate('close') }}</button>
                                                                <button type="button" class="btn btn-primary"
                                                                        data-bs-dismiss="modal">{{ translate('save') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="row mb-30">
                                                                <div
                                                                    class="col-sm-@if(auth('customer')->check()) '6' @else '12' @endif">
                                                                    <div class="form-group mb-3">
                                                                        <label for="billing-contact-person-name"
                                                                               class="text-capitalize">{{ translate('contact_person_name')}}</label>
                                                                        <input type="text"
                                                                               name="billing_contact_person_name"
                                                                               id="billing-contact-person-name"
                                                                               class="form-control"
                                                                               placeholder="{{ translate('ex') }}: {{translate('Jhon_Doe')}}" {{$billing_addresses->count()==0?'required':''}}>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group mb-3">
                                                                        <label
                                                                            for="billing_phone">{{ translate('phone') }}</label>
                                                                        <input type="tel"
                                                                               id="billing-phone" class="form-control phone-input-with-country-picker-billing"
                                                                               placeholder="{{ translate('ex') }}: {{translate('+88 01000000000')}}" {{$billing_addresses->count()==0?'required':''}}>
                                                                        <input type="hidden" class="country-picker-phone-number-billing w-50" name="billing_phone" readonly>

                                                                    </div>
                                                                </div>
                                                                @if(!auth('customer')->check())
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group mb-3">
                                                                            <label
                                                                                for="billing_contact_email">{{ translate('email') }}</label>
                                                                            <input type="email"
                                                                                   name="billing_contact_email"
                                                                                   id="billing-contact-email"
                                                                                   class="form-control"
                                                                                   placeholder="{{ translate('ex') }}: {{translate('email@domain.com')}}"
                                                                                   required>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <div class="col-sm-6">
                                                                    <div class="form-group mb-3">
                                                                        <label for="billing_address_type"
                                                                               class="text-capitalize">{{ translate('address_type')}}</label>
                                                                        <select name="billing_address_type"
                                                                                id="billing-address-type"
                                                                                class="form-select">
                                                                            <option
                                                                                value="permanent">{{ translate('permanent')}}</option>
                                                                            <option
                                                                                value="home">{{ translate('home')}}</option>
                                                                            <option
                                                                                value="others">{{ translate('others')}}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group mb-3">
                                                                        <label
                                                                            for="billing-country">{{ translate('country') }}</label>
                                                                        <select name="billing_country"
                                                                                id="billing-country"
                                                                                class="form-control select_picker select2">
                                                                            @forelse($countries as $country)
                                                                                <option
                                                                                    value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                                                            @empty
                                                                                <option
                                                                                    value="">{{ translate('no_country_to_deliver') }}</option>
                                                                            @endforelse
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group mb-3">
                                                                        <label
                                                                            for="billing-city">{{ translate('city') }}</label>
                                                                        <input type="text" name="billing_city"
                                                                               id="billing-city"
                                                                               placeholder="{{ translate('ex') }}: {{translate('Dhaka')}}"
                                                                               class="form-control" {{$billing_addresses->count()==0?'required':''}}>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group mb-3">
                                                                        <label
                                                                            for="billing-zip">{{ translate('Zip_Code') }}</label>
                                                                        @if($zip_restrict_status == 1)
                                                                            <select name="billing_zip" id="billing-zip"
                                                                                    class="form-control select2 select_picker"
                                                                                    data-live-search="true" required>
                                                                                @forelse($zip_codes as $code)
                                                                                    <option
                                                                                        value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                                                @empty
                                                                                    <option
                                                                                        value="">{{ translate('no_zip_to_deliver') }}</option>
                                                                                @endforelse
                                                                            </select>
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                   id="billing-zip" name="billing_zip"
                                                                                   placeholder="{{ translate('ex') }}: {{translate('1216')}}" {{$billing_addresses->count()==0?'required':''}}>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="form-group mb-3">
                                                                        <label
                                                                            for="billing_address">{{ translate('address') }}</label>
                                                                        <div
                                                                            class="form-control focus-border rounded d-flex align-items-center">
                                                                            <input type="text" name="billing_address"
                                                                                   id="billing_address"
                                                                                   class="flex-grow-1 text-dark bg-transparent border-0 focus-input"
                                                                                   placeholder="{{ translate('your_address') }}" {{$shipping_addresses->count()==0?'required':''}}>

                                                                            @if(getWebConfig('map_api_status') == 1)
                                                                                <div class="border-start ps-3 pe-1"
                                                                                     data-bs-toggle="modal"
                                                                                     data-bs-target="#billingMapModal">
                                                                                    <i class="bi bi-compass-fill"></i>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="billing_method_id"
                                                                       id="billing-method-id" value="0">
                                                                @if(auth('customer')->check())
                                                                    <div class="col-sm-12">
                                                                        <label
                                                                            class="custom-checkbox save-billing-address"
                                                                            id="save-billing-address-label">
                                                                            <input type="checkbox"
                                                                                   name="save_address_billing"
                                                                                   id="save_address_billing">
                                                                            {{ translate('save_this_address') }}
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                @if(!Auth::guard('customer')->check() && $web_config['guest_checkout_status'] && !$physical_product_view)
                                    <div class="card __card mt-3">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center flex-wrap justify-content-between gap-3">
                                                <div class="min-h-45 d-flex gap-2 align-items-center cursor-pointer user-select-none">
                                                    <input type="checkbox" id="is_check_create_account" name="is_check_create_account">
                                                    <label class="fw-bold fs-13 mb-0" for="is_check_create_account">
                                                        {{ translate('Create_account_with_above_info') }}
                                                    </label>
                                                </div>

                                                <div class="is_check_create_account_password_group d--none">
                                                    <div class="d-flex gap-3 flex-wrap flex-sm-nowrap">
                                                        <div class="">
                                                            <div class="input-inner-end-ele">
                                                                <input name="customer_password" type="password" id="customer_password" class="form-control" placeholder="{{ translate('new_Password') }}" required="">
                                                                <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <div class="input-inner-end-ele">
                                                                <input name="customer_confirm_password" type="password" id="customer_confirm_password" class="form-control" placeholder="{{ translate('confirm_Password') }}" required="">
                                                                <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @include('theme-views.partials._order-summery')
            </div>
        </div>
    </main>

    <span id="shipping-address-location"
          data-latitude="{{ $default_location ? $default_location['lat'] : '' }}"
          data-longitude="{{ $default_location ? $default_location['lng'] : '' }}">
</span>
@endsection
@push('script')
    <script src="{{ theme_asset('assets/js/shipping-page.js') }}"></script>

    @if(getWebConfig('map_api_status') ==1 )
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{getWebConfig('map_api_key')}}&callback=mapsLoading&loading=async&libraries=places&v=3.56"
            defer>
        </script>
    @endif
@endpush
