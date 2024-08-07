@extends('layouts.front-end.app')

@section('title',translate('shipping_Address'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/css/intlTelInput.css') }}">
@endpush

@section('content')
@php($billingInputByCustomer=getWebConfig(name: 'billing_input_by_customer'))
    <div class="container py-4 rtl __inline-56 px-0 px-md-3 text-align-direction">
        <div class="row mx-max-md-0">
            <div class="col-md-12 mb-3">
                <h3 class="font-weight-bold text-center text-lg-left">{{translate('checkout')}}</h3>
            </div>
            <section class="col-lg-8 px-max-md-0">
                <div class="checkout_details">
                <div class="px-3 px-md-3">
                    @include('web-views.partials._checkout-steps',['step'=>2])
                </div>
                    @php($defaultLocation = getWebConfig(name: 'default_location'))

                    @if($physical_product_view)
                        <input type="hidden" id="physical_product" name="physical_product" value="{{ $physical_product_view ? 'yes':'no'}}">
                        <div class="px-3 px-md-0">
                            <h4 class="pb-2 mt-4 fs-18 text-capitalize">{{ translate('shipping_address')}}</h4>
                        </div>

                        @php($shippingAddresses= \App\Models\ShippingAddress::where(['customer_id'=>auth('customer')->id(), 'is_guest'=>0])->get())
                        <form method="post" class="card __card" id="address-form">
                            <div class="card-body p-0">
                                <ul class="list-group">
                                    <li class="list-group-item add-another-address">
                                        @if ($shippingAddresses->count() >0)
                                            <div class="d-flex align-items-center justify-content-end gap-3">
                                                <div class="dropdown">
                                                    <button class="form-control dropdown-toggle text-capitalize" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{translate('saved_address')}}
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right saved-address-dropdown scroll-bar-saved-address" aria-labelledby="dropdownMenuButton">
                                                        @foreach($shippingAddresses as $key => $address)
                                                        <div class="dropdown-item select_shipping_address {{$key == 0 ? 'active' : ''}}" id="shippingAddress{{$key}}">
                                                            <input type="hidden" class="selected_shippingAddress{{$key}}" value="{{$address}}">
                                                            <input type="hidden" name="shipping_method_id" value="{{$address['id']}}">
                                                            <div class="media gap-2">
                                                                <div class="">
                                                                    <i class="tio-briefcase"></i>
                                                                </div>
                                                                <div class="media-body">
                                                                    <div class="mb-1 text-capitalize">{{$address->address_type}}</div>
                                                                    <div class="text-muted fs-12 text-capitalize text-wrap">{{$address->address}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div id="accordion">
                                            <div class="">
                                                <div class="mt-3">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('contact_person_name')}}
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="text" class="form-control" name="contact_person_name" {{$shippingAddresses->count()==0?'required':''}} id="name">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('phone')}}
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="tel" class="form-control phone-input-with-country-picker-3" id="phone" {{$shippingAddresses->count()==0?'required':''}}>
                                                                <input type="hidden" id="shipping_phone_view" class="country-picker-phone-number-3 w-50" name="phone" readonly>
                                                            </div>
                                                        </div>
                                                        @if(!auth('customer')->check())
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="exampleInputEmail1">
                                                                        {{ translate('email')}}
                                                                        <span class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="email" class="form-control"  name="email" id="email" {{$shippingAddresses->count()==0?'required':''}}>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ translate('address_type')}}</label>
                                                                <select class="form-control" name="address_type" id="address_type">
                                                                    <option value="permanent">{{ translate('permanent')}}</option>
                                                                    <option value="home">{{ translate('home')}}</option>
                                                                    <option value="others">{{ translate('others')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ translate('country')}}
                                                                    <span class="text-danger">*</span></label>
                                                                <select name="country" id="country" class="form-control selectpicker" data-live-search="true" required>
                                                                    @forelse($countries as $country)
                                                                        <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                                                    @empty
                                                                        <option value="">{{ translate('no_country_to_deliver') }}</option>
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('city')}}<span  class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="city" id="city" {{$shippingAddresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('zip_code')}}
                                                                    <span class="text-danger">*</span></label>
                                                                @if($zip_restrict_status == 1)
                                                                    <select name="zip" class="form-control selectpicker" data-live-search="true" id="select2-zip-container" required>
                                                                        @forelse($zip_codes as $code)
                                                                        <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                                        @empty
                                                                            <option value="">{{ translate('no_zip_to_deliver') }}</option>
                                                                        @endforelse
                                                                    </select>
                                                                @else
                                                                <input type="text" class="form-control"
                                                                       name="zip" id="zip" {{$shippingAddresses->count()==0?'required':''}}>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group mb-1">
                                                                <label>{{ translate('address')}}<span class="text-danger">*</span></label>
                                                                <textarea class="form-control" id="address" type="text" name="address" {{$shippingAddresses->count()==0?'required':''}}></textarea>
                                                                <span class="fs-14 text-danger font-semi-bold opacity-0 map-address-alert">
                                                                    {{ translate('note') }}: {{ translate('you_need_to_select_address_from_your_selected_country') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(getWebConfig('map_api_status') ==1 )
                                                        <div class="form-group location-map-canvas-area map-area-alert-border">
                                                            <input id="pac-input" class="controls rounded __inline-46 location-search-input-field" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}"/>
                                                            <div class="__h-200px" id="location_map_canvas"></div>
                                                        </div>
                                                    @endif

                                                    <div class="d-flex gap-3 align-items-center">
                                                        <label class="form-check-label d-flex gap-2 align-items-center" id="save_address_label">
                                                            <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="0">
                                                            @if(auth('customer')->check())
                                                                <input type="checkbox" name="save_address" id="save_address">
                                                                {{ translate('save_this_Address') }}
                                                            @endif
                                                        </label>
                                                    </div>

                                                    <input type="hidden" id="latitude"
                                                           name="latitude" class="form-control d-inline"
                                                           placeholder="{{ translate('ex')}} : -94.22213"
                                                           value="{{$defaultLocation?$defaultLocation['lat']:0}}" required
                                                           readonly>
                                                    <input type="hidden"
                                                           name="longitude" class="form-control"
                                                           placeholder="{{ translate('ex')}} : 103.344322" id="longitude"
                                                           value="{{$defaultLocation?$defaultLocation['lng']:0}}" required
                                                           readonly>

                                                    <button type="submit" class="btn btn--primary d--none" id="address_submit"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </form>

                        @if(!Auth::guard('customer')->check() && $web_config['guest_checkout_status'])
                        <div class="card __card mt-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center flex-wrap justify-content-between gap-3">
                                    <div class="min-h-45 form-check d-flex gap-3 align-items-center cursor-pointer user-select-none">
                                        <input type="checkbox" id="is_check_create_account" name="is_check_create_account" class="form-check-input mt-0" value="1">
                                        <label class="form-check-label font-weight-bold fs-13" for="is_check_create_account">
                                            {{translate('Create_an_account_with_the_above_info')}}
                                        </label>
                                    </div>

                                    <div class="is_check_create_account_password_group d--none">
                                        <div class="d-flex gap-3 flex-wrap flex-sm-nowrap">
                                            <div class="w-100">
                                                {{-- <label class="form-label font-semibold">{{ translate('password') }}</label> --}}
                                                <div class="password-toggle rtl">
                                                    <input class="form-control text-align-direction" name="customer_password" type="password" id="customer_password" placeholder="{{ translate('new_Password') }}" required>
                                                    <label class="password-toggle-btn">
                                                        <input class="custom-control-input" type="checkbox">
                                                        <i class="tio-hidden password-toggle-indicator"></i>
                                                        <span class="sr-only">{{ translate('show_password') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="w-100">
                                                {{-- <label class="form-label font-semibold">{{ translate('confirm_Password') }}</label> --}}
                                                <div class="password-toggle rtl">
                                                    <input class="form-control text-align-direction w-100" name="customer_confirm_password" type="password" id="customer_confirm_password" placeholder="{{ translate('confirm_Password') }}" required>
                                                    <label class="password-toggle-btn">
                                                        <input class="custom-control-input" type="checkbox">
                                                        <i class="tio-hidden password-toggle-indicator"></i>
                                                        <span class="sr-only">{{ translate('show_password') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif

                    @if($billingInputByCustomer)
                    <div>
                        <div class="billing-methods_label d-flex flex-wrap justify-content-between gap-2 mt-4 pb-3 px-3 px-md-0">
                            <h4 class="mb-0 fs-18 text-capitalize">{{ translate('billing_address')}}</h4>

                            @php($billingAddresses=\App\Models\ShippingAddress::where(['customer_id'=>auth('customer')->id(), 'is_guest'=>'0'])->get())
                            @if($physical_product_view)
                                <div class="form-check d-flex gap-3 align-items-center">
                                    <input type="checkbox" id="same_as_shipping_address" name="same_as_shipping_address"
                                        class="form-check-input action-hide-billing-address mt-0" {{$billingInputByCustomer==1?'':'checked'}}>
                                    <label class="form-check-label user-select-none" for="same_as_shipping_address">
                                        {{ translate('same_as_shipping_address')}}
                                    </label>
                                </div>
                            @endif
                        </div>

                        @if(!$physical_product_view)
                            <div class="mb-3 alert--info">
                                <div class="d-flex align-items-center gap-2">
                                    <img class="mb-1" src="{{ theme_asset('public/assets/front-end/img/icons/info-light.svg') }}" alt="Info">
                                    <span>{{ translate('When_you_input_all_the_required_information_for_this_billing_address_it_will_be_stored_for_future_purchases') }}</span>
                                </div>
                            </div>
                        @endif

                        <form method="post" class="card __card" id="billing-address-form">
                            <div id="hide_billing_address" class="">
                                <ul class="list-group">

                                    <li class="list-group-item action-billing-address-hide">
                                        @if ($billingAddresses->count() >0)
                                            <div class="d-flex align-items-center justify-content-end gap-3">

                                                <div class="dropdown">
                                                    <button class="form-control dropdown-toggle text-capitalize" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{translate('saved_address')}}
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right saved-address-dropdown scroll-bar-saved-address" aria-labelledby="dropdownMenuButton">
                                                        @foreach($billingAddresses as $key=>$address)
                                                            <div class="dropdown-item select_billing_address {{$key == 0 ? 'active' : ''}}" id="billingAddress{{$key}}">
                                                                <input type="hidden" class="selected_billingAddress{{$key}}" value="{{$address}}">
                                                                <input type="hidden" name="billing_method_id" value="{{$address['id']}}">
                                                                <div class="media gap-2">
                                                                    <div class="">
                                                                        <i class="tio-briefcase"></i>
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <div class="mb-1 text-capitalize">{{$address->address_type}}</div>
                                                                        <div class="text-muted fs-12 text-capitalize text-wrap">{{$address->address}}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div id="accordion">
                                            <div class="">
                                                <div class="">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('contact_person_name')}}<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    name="billing_contact_person_name" id="billing_contact_person_name"  {{$billingAddresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('phone')}}
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="text" class="form-control phone-input-with-country-picker-2"
                                                                    id="billing_phone" {{ $billingAddresses->count()==0 ? 'required' : '' }}>
                                                                <input type="hidden" class="country-picker-phone-number-2 w-50" name="billing_phone" readonly>
                                                            </div>
                                                        </div>
                                                        @if(!auth('customer')->check())
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleInputEmail1">{{ translate('email')}}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        name="billing_contact_email" id="billing_contact_email" id {{$billingAddresses->count()==0?'required':''}}>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ translate('address_type')}}</label>
                                                                <select class="form-control" name="billing_address_type" id="billing_address_type">
                                                                    <option value="permanent">{{ translate('permanent')}}</option>
                                                                    <option value="home">{{ translate('home')}}</option>
                                                                    <option value="others">{{ translate('others')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ translate('country')}}<span class="text-danger">*</span></label>
                                                                <select name="billing_country" id="" class="form-control selectpicker" data-live-search="true" id="billing_country">
                                                                    @foreach($countries as $country)
                                                                        <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1">{{ translate('city')}}<span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="billing_city"
                                                                    name="billing_city" {{$billingAddresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('zip_code')}}
                                                                    <span class="text-danger">*</span></label>
                                                                @if($zip_restrict_status)
                                                                    <select name="billing_zip" id="" class="form-control selectpicker" data-live-search="true" id="select_billing_zip">
                                                                        @foreach($zip_codes as $code)
                                                                            <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @else
                                                                    <input type="text" class="form-control" id="billing_zip"
                                                                           name="billing_zip" {{$billingAddresses->count()==0?'required':''}}>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-1">
                                                        <label>{{ translate('address')}}<span class="text-danger">*</span></label>
                                                        <textarea class="form-control" id="billing_address" type="billing_text" name="billing_address" id="billing_address" {{$billingAddresses->count()==0?'required':''}}></textarea>

                                                        <span class="fs-14 text-danger font-semi-bold opacity-0 map-address-alert">
                                                            {{ translate('note') }}: {{ translate('you_need_to_select_address_from_your_selected_country') }}
                                                        </span>
                                                    </div>
                                                    @if(getWebConfig('map_api_status') ==1 )
                                                    <div class="form-group map-area-alert-border location-map-billing-canvas-area">
                                                        <input id="pac-input-billing" class="controls rounded __inline-46 location-search-input-field"
                                                            title="{{translate('search_your_location_here')}}"
                                                            type="text"
                                                            placeholder="{{translate('search_here')}}"/>
                                                        <div class="__h-200px" id="location_map_canvas_billing"></div>
                                                    </div>
                                                    @endif

                                                    <input type="hidden" name="billing_method_id" id="billing_method_id" value="0">
                                                    @if(auth('customer')->check())
                                                    <div class=" d-flex gap-3 align-items-center">
                                                        <label class="form-check-label d-flex gap-2 align-items-center" id="save-billing-address-label">
                                                            <input type="checkbox" name="save_address_billing" id="save_address_billing">
                                                            {{ translate('save_this_Address') }}
                                                        </label>
                                                    </div>
                                                    @endif

                                                    <input type="hidden" id="billing_latitude"
                                                        name="billing_latitude" class="form-control d-inline"
                                                        placeholder="{{ translate('ex')}} : -94.22213"
                                                        value="{{$defaultLocation?$defaultLocation['lat']:0}}" required
                                                        readonly>
                                                    <input type="hidden"
                                                        name="billing_longitude" class="form-control"
                                                        placeholder="{{ translate('ex')}} : 103.344322" id="billing_longitude"
                                                        value="{{$defaultLocation?$defaultLocation['lng']:0}}" required
                                                        readonly>

                                                    <button type="submit" class="btn btn--primary d--none" id="address_submit"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>

                        @if(!Auth::guard('customer')->check() && $web_config['guest_checkout_status'] && !$physical_product_view)
                            <div class="card __card mt-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center flex-wrap justify-content-between gap-3">
                                        <div class="min-h-45 form-check d-flex gap-3 align-items-center cursor-pointer user-select-none">
                                            <input type="checkbox" id="is_check_create_account" name="is_check_create_account" class="form-check-input mt-0" value="1">
                                            <label class="form-check-label font-weight-bold fs-13" for="is_check_create_account">
                                                {{translate('Create_an_account_with_the_above_info')}}
                                            </label>
                                        </div>

                                        <div class="is_check_create_account_password_group d--none">
                                            <div class="d-flex gap-3 flex-wrap flex-sm-nowrap">
                                                <div class="w-100">
                                                    <div class="password-toggle rtl">
                                                        <input class="form-control text-align-direction" name="customer_password" type="password" id="customer_password" placeholder="{{ translate('new_Password')}}" required>
                                                        <label class="password-toggle-btn">
                                                            <input class="custom-control-input" type="checkbox">
                                                            <i class="tio-hidden password-toggle-indicator"></i>
                                                            <span class="sr-only">{{ translate('show_password') }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="w-100">
                                                    <div class="password-toggle rtl">
                                                        <input class="form-control text-align-direction" name="customer_confirm_password" type="password" id="customer_confirm_password" placeholder="{{ translate('confirm_Password')}}" required>
                                                        <label class="password-toggle-btn">
                                                            <input class="custom-control-input" type="checkbox">
                                                            <i class="tio-hidden password-toggle-indicator"></i>
                                                            <span class="sr-only">{{ translate('show_password') }}</span>
                                                        </label>
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
            </section>
            @include('web-views.partials._order-summary')
        </div>
    </div>

    <span id="message-update-this-address" data-text="{{ translate('Update_this_Address') }}"></span>
    <span id="route-customer-choose-shipping-address-other" data-url="{{ route('customer.choose-shipping-address-other') }}"></span>
    <span id="default-latitude-address" data-value="{{ $defaultLocation ? $defaultLocation['lat']:'-33.8688' }}"></span>
    <span id="default-longitude-address" data-value="{{ $defaultLocation ? $defaultLocation['lng']:'151.2195' }}"></span>
    <span id="route-action-checkout-function" data-route="checkout-details"></span>
    <span id="system-country-restrict-status" data-value="{{ $country_restrict_status }}"></span>
@endsection

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/country-picker-init.js') }}"></script>
    <script>
        "use strict";
        const deliveryRestrictedCountries = @json($countriesName);
        function deliveryRestrictedCountriesCheck(countryOrCode, elementSelector, inputElement) {
            const foundIndex = deliveryRestrictedCountries.findIndex(country => country.toLowerCase() === countryOrCode.toLowerCase());
            if (foundIndex !== -1) {
                $(elementSelector).removeClass('map-area-alert-danger');
                $(inputElement).parent().find('.map-address-alert').removeClass('opacity-100').addClass('opacity-0')
            } else {
                $(elementSelector).addClass('map-area-alert-danger');
                $(inputElement).val('')
                $(inputElement).parent().find('.map-address-alert').removeClass('opacity-0').addClass('opacity-100')
            }
        }

        $('#is_check_create_account').on('change', function() {
            if($(this).is(':checked')) {
                $('.is_check_create_account_password_group').fadeIn();
            } else {
                $('.is_check_create_account_password_group').fadeOut();
            }
        });
    </script>

    <script src="{{ theme_asset(path: 'public/assets/front-end/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/shipping.js') }}"></script>



    @if(getWebConfig('map_api_status') ==1 )
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{getWebConfig('map_api_key')}}&callback=mapsShopping&loading=async&libraries=places&v=3.56"
            defer>
        </script>
    @endif
@endpush
