@extends('layouts.back-end.app-seller')

@section('title', translate('shop_view'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/shop-info.png')}}" alt="">
                {{translate('shop_info')}}
            </h2>
        </div>
        @include('vendor-views.shop.inline-menu')
        <div class="row my-3 gy-3">
            @if ($minimumOrderAmountStatus && $minimumOrderAmountByVendor)
                <div class="col-md-6">
                    <form action="{{route('vendor.shop.update-order-settings',[$vendor['id']])}}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="text-capitalize mb-0">
                                    <i class="tio-dollar-outlined"></i>
                                    {{translate('minimum_order_amount')}}
                                </h5>
                            </div>
                            <div class="card-body text-start">
                                <div class="mb-3">
                                    <label class="title-color" for="minimum_order_amount">
                                        {{translate('amount')}} ({{ getCurrencySymbol() }})
                                    </label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="top"
                                              title="{{translate('set_the_minimum_order_amount_a_customer_must_order_from_this_vendor_shop')}}">
                                            <img width="16" src="{{dynamicAsset(path: '/public/assets/back-end/img/info-circle.svg')}}"
                                                 alt="">
                                        </span>
                                    <input type="number" step="0.01" class="form-control w-100"
                                           id="minimum_order_amount"
                                           name="minimum_order_amount" min="1"
                                           value="{{ usdToDefaultCurrency(amount: $vendor->minimum_order_amount) ?? 0 }}"
                                           placeholder="{{translate('0.00')}}">
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" id="submit"
                                            class="btn btn--primary px-4">{{translate('submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            @if ($freeDeliveryStatus && $freeDeliveryResponsibility == 'seller')
                <div
                    class="col-sm-12 col-md-6">
                    <form action="{{route('vendor.shop.update-order-settings',[$vendor['id']])}}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="text-capitalize mb-0">
                                    <i class="tio-dollar-outlined"></i>
                                    {{translate('free_delivery_over_amount')}}
                                </h5>
                            </div>
                            <div class="card-body text-start">
                                <div class="row align-items-end">
                                    <div class="col-xl-6 col-md-6">
                                        <div
                                            class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                    <span class="title-color d-flex align-items-center gap-1">
                                        {{translate('free_Delivery')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="top"
                                              title="{{translate('if_enabled_free_delivery_will_be_available_when_customers_order_over_a_certain_amount')}}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                            <label class="switcher" for="free-delivery-status">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                       name="free_delivery_status"
                                                       id="free-delivery-status"
                                                       {{$vendor['free_delivery_status'] == 1?'checked':''}}
                                                       data-modal-id = "toggle-modal"
                                                       data-toggle-id = "free-delivery-status"
                                                       data-on-image = "free-delivery-on.png"
                                                       data-off-image = "free-delivery-on.png"
                                                       data-on-title = "{{translate('want_to_Turn_ON_Free_Delivery')}}"
                                                       data-off-title = "{{translate('want_to_Turn_OFF_Free_Delivery')}}"
                                                       data-on-message = "<p>{{translate('if_enabled_the_free_delivery_feature_will_be_shown_from_the_system')}}</p>"
                                                       data-off-message = "<p>{{translate('if_disabled_the_free_delivery_feature_will_be_hidden_from_the_system')}}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-md-6">
                                        <div class="form-group">
                                            <label class="title-color d-flex align-items-center gap-2"
                                                   for="free-delivery-over-amount">
                                                {{translate('free_Delivery_Over')}}
                                                ({{ getCurrencySymbol() }})
                                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                                      data-placement="top"
                                                      title="{{ translate('customers_will_get_free_delivery_if_the_order_amount_exceeds_the_given_amount_and_the_given_amount_will_be_added_as_vendor_expenses')}}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                            </label>
                                            <input type="number" class="form-control" name="free_delivery_over_amount" id="free-delivery-over-amount" min="0"
                                                   placeholder="{{translate('ex').':'.translate('10')}}"
                                                   value="{{ usdToDefaultCurrency($vendor['free_delivery_over_amount']) ?? 0 }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" id="submit"
                                            class="btn btn--primary px-4">{{translate('submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
