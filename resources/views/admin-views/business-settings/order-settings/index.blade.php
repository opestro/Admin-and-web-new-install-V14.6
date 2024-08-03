@extends('layouts.back-end.app')
@section('title', translate('order_settings'))
@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/business-setup.png')}}" alt="">
                {{translate('business_setup')}}
            </h2>
            <div class="btn-group">
                <div class="ripple-animation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"
                         class="svg replaced-svg">
                        <path
                            d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
                <div
                    class="dropdown-menu dropdown-menu-right bg-aliceblue border border-color-primary-light p-4 dropdown-w-lg">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/note.png')}}" alt="">
                        <h5 class="text-primary mb-0">{{translate('note')}}</h5>
                    </div>
                    <p class="title-color font-weight-medium mb-0">{{ translate('please_click_the_Save_button_below_to_save_all_the_changes') }}</p>
                </div>
            </div>
        </div>
        @include('admin-views.business-settings.business-setup-inline-menu')
        <div class="card">
            <div class="border-bottom px-4 py-3">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/header-logo.png')}}" alt="">
                    {{translate('order_settings')}}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{route('admin.business-settings.order-settings.update-order-settings')}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        @php($orderVerification=getWebConfig('order_verification'))
                        <div class="col-xl-4 col-md-6">
                            <div
                                class="form-group d-flex justify-content-between align-items-center gap-10 form-control">
                                <span class="title-color text-capitalize">
                                    {{translate('order_delivery_verification')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top"
                                          title="{{translate('customers_receive_a_verification_code_after_placing_an_order').'.'.translate('when_a_deliveryman_arrives_for_delivery_they_must_provide_the_code_to_the_deliveryman_to_verify_the_order_delivery')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>
                                <label class="switcher" for="order-verification">
                                    <input type="checkbox" value="1" class="switcher_input toggle-switch-message" name="order_verification"
                                           id="order-verification" {{ $orderVerification == 1 ? 'checked':'' }}
                                           data-modal-id = "toggle-modal"
                                           data-toggle-id = "order-verification"
                                           data-on-image = "order-verifications-on.png"
                                           data-off-image = "order-verifications-off.png"
                                           data-on-title = "{{translate('want_to_Turn_ON_Order_Delivery_Verification')}}"
                                           data-off-title = "{{translate('want_to_Turn_OFF_Order_Delivery_Verification')}}"
                                           data-on-message = "<p>{{translate('if_enabled_deliverymen_must_verify_the_order_deliveries_by_collecting_the_OTP_from_customers')}}</p>"
                                           data-off-message = "<p>{{translate('if_disabled_deliverymen_do_not_need_to_verify_the_order_deliveries')}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>

                        @php($minimumOrderAmountStatus=getWebConfig('minimum_order_amount_status'))
                        <div class="col-xl-4 col-md-6">
                            <div
                                class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color">
                                    {{translate('minimum_order_amount')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top"
                                          title="{{translate('if_enabled_customers_must_place_at_least_or_more_than_the_order_amount_that_admin_or_vendors_set')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>
                                <label class="switcher" for="minimum-order-amount-status">
                                    <input type="checkbox" value="1" class="switcher_input toggle-switch-message"
                                           name="minimum_order_amount_status" id="minimum-order-amount-status"
                                           {{ $minimumOrderAmountStatus == 1 ? 'checked':'' }}
                                           data-modal-id = "toggle-modal"
                                           data-toggle-id = "minimum-order-amount-status"
                                           data-on-image = "minimum-order-amount-on.png"
                                           data-off-image = "minimum-order-amount-off.png"
                                           data-on-title = "{{translate('want_to_Turn_ON_Minimum_Order_Amount')}}"
                                           data-off-title = "{{translate('want_to_Turn_OFF_Minimum_Order_Amount')}}"
                                           data-on-message = "<p>{{translate('if_enabled_customers_must_order_over_the_minimum_amount_of_orders_that_admin_or_vendors_set')}}</p>"
                                           data-off-message = "<p>{{translate('if_disabled_there_will_be_no_minimum_order_restrictions_and_customers_can_place_any_order_amount')}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            @php($billingInputByCustomer=getWebConfig('billing_input_by_customer'))
                            <div
                                class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color d-flex align-items-center gap-1 text-capitalize">
                                    {{translate('show_billing_address_in_checkout')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="top" title="{{translate('if_enabled_the_billing_address_will_be_shown_on_the_checkout_page')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>

                                <label class="switcher" for="billing-input-by-customer">
                                    <input type="checkbox" value="1" class="switcher_input toggle-switch-message"
                                           name="billing_input_by_customer" id="billing-input-by-customer"
                                           {{$billingInputByCustomer == 1?'checked':''}}
                                           data-modal-id = "toggle-modal"
                                           data-toggle-id = "billing-input-by-customer"
                                           data-on-image = "billing-address-on.png"
                                           data-off-image = "billing-address-off.png"
                                           data-on-title = "{{translate('want_to_Turn_ON_Billing_Address_in_Checkout')}}"
                                           data-off-title = "{{translate('want_to_Turn_OFF_Billing_Address_in_Checkout')}}"
                                           data-on-message = "<p>{{translate('if_enabled_the_billing_address_will_be_shown_on_the_checkout_page')}}</p>"
                                           data-off-message = "<p>{{translate('if_disabled_the_billing_address_will_be_hidden_from_the_checkout_page')}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                        </div>

                        @php($freeDelivery=getWebConfig('free_delivery_status'))
                        <div class="col-xl-4 col-md-6">
                            <div
                                class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color d-flex align-items-center gap-1 text-capitalize">
                                    {{translate('free_delivery')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="top"
                                          title="{{translate('if_enabled_free_delivery_will_be_available_when_customers_order_over_a_certain_amount')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>

                                <label class="switcher" for="free-delivery-status">
                                    <input type="checkbox" value="1" class="switcher_input toggle-switch-message" name="free_delivery_status"
                                           id="free-delivery-status" {{$freeDelivery == 1?'checked':''}}
                                           data-modal-id = "toggle-modal"
                                           data-toggle-id = "free-delivery-status"
                                           data-on-image = "free-delivery-on.png"
                                           data-off-image = "free-delivery-off.png"
                                           data-on-title = "{{translate('want_to_Turn_ON_Free_Delivery')}}"
                                           data-off-title = "{{translate('want_to_Turn_OFF_Free_Delivery')}}"
                                           data-on-message = "<p>{{translate('if_enabled_the_free_delivery_feature_will_be_shown_from_the_system')}}</p>"
                                           data-off-message = "<p>{{translate('if_disabled_the_free_delivery_feature_will_be_hidden_from_the_system')}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>

                        @php($freeDeliveryResponsibility=getWebConfig('free_delivery_responsibility'))
                        <div class="col-xl-4 col-md-6">
                            <div class="form-group">
                                <label class="title-color d-flex text-capitalize"
                                       for="free_delivery_responsibility">{{translate('free_delivery_responsibility')}} </label>
                                <select name="free_delivery_responsibility" id="free-delivery-responsibility"
                                        class="form-control  js-select2-custom">
                                    <option
                                        value="admin" {{ $freeDeliveryResponsibility == 'admin' ? 'selected':'' }}>{{ translate('admin') }}</option>
                                    <option
                                        value="seller" {{ $freeDeliveryResponsibility == 'seller' ? 'selected':'' }}>{{ translate('vendor') }}</option>
                                </select>
                            </div>
                        </div>

                        @php($freeDeliveryOverAmountSeller=getWebConfig('free_delivery_over_amount_seller'))
                        <div class="col-xl-4 col-md-6"
                             style="{{ $freeDeliveryResponsibility == 'seller' ? 'display:none':''}}"
                             id="free-delivery-over-amount-admin-area">
                            <div class="form-group">
                                <label class="title-color d-flex align-items-center gap-2 text-capitalize"
                                       for="free_delivery_over_amount_vendor">
                                    {{translate('free_delivery_over')}}({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }})
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="top"
                                          title="{{translate('free_delivery_over_amount_for_every_vendor_if_they_do_not_set_any_range_yet')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </label>
                                <input type="number" class="form-control" min="0"
                                       name="free_delivery_over_amount_seller" id="free_delivery_over_amount_vendor"
                                       placeholder="{{translate('ex').':'.'10'}}"
                                       value="{{ usdToDefaultCurrency($freeDeliveryOverAmountSeller) ?? 0 }}">
                            </div>
                        </div>

                        @php($refundDayLimit=getWebConfig('refund_day_limit'))
                        <div class="col-xl-4 col-md-6">
                            <div class="form-group">
                                <label class="title-color text-capitalize" for="refund_day_limit">{{translate('refund_order_validity')}}
                                    ({{translate('days')}})</label>
                                <input type="text" class="form-control" name="refund_day_limit" id="refund_day_limit"
                                       placeholder="{{translate('ex').':'.'10'}}" value="{{ $refundDayLimit ?? 0 }}">
                            </div>
                        </div>
                        @php($guestCheckout=getWebConfig('guest_checkout'))
                        <div class="col-xl-4 col-md-6">
                            <div
                                class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color d-flex align-items-center gap-1">
                                    {{translate('guest_checkout')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top"
                                          title="{{translate('if_enabled_users_can_complete_the_checkout_process_without_logging_in_to_the_system')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>
                                <label class="switcher" for="guest-checkout">
                                    <input type="checkbox" value="1" class="switcher_input toggle-switch-message" name="guest_checkout"
                                           id="guest-checkout" {{$guestCheckout == 1?'checked':''}}
                                           data-modal-id = "toggle-modal"
                                           data-toggle-id = "guest-checkout"
                                           data-on-image = "guest-checkout-on.png"
                                           data-off-image = "guest-checkout-off.png"
                                           data-on-title = "{{translate('by_Turning_ON_Guest_Checkout_Mode')}}"
                                           data-off-title = "{{translate('by_Turning_Off_Guest_Checkout_Mode')}}"
                                           data-on-message = "<p>{{translate('user_can_place_order_without_login')}}</p>"
                                           data-off-message = "<p>{{translate('user_cannot_place_order_without_login')}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" id="submit" class="btn btn--primary px-4">{{translate('save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/business-setting.js')}}"></script>
@endpush
