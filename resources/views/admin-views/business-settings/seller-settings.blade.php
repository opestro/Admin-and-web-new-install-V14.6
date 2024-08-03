@extends('layouts.back-end.app')

@section('title', translate('vendor_Information'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/business-setup.png')}}" alt="">
                {{translate('business_setup')}}
            </h2>
            <div class="btn-group">
                <div class="ripple-animation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"
                         class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z"
                              fill="currentColor"></path>
                    </svg>
                </div>
                <div class="dropdown-menu dropdown-menu-right bg-aliceblue border border-color-primary-light p-4 dropdown-w-lg">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/note.png')}}" alt="">
                        <h5 class="text-primary mb-0">{{translate('note')}}</h5>
                    </div>
                    <p class="title-color font-weight-medium mb-0">{{ translate('please_click_the_Save_button_below_to_save_all_the_changes') }}</p>
                </div>
            </div>
        </div>
        @include('admin-views.business-settings.business-setup-inline-menu')

        <form action="{{route('admin.business-settings.vendor-settings.update-vendor-settings')}}" method="post">
            @csrf
            <div class="card">
                <div class="border-bottom px-4 py-3">
                    <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                        <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/product_setup.png')}}" alt="">
                        {{translate('vendor_setup')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-xl-4 col-md-6">
                            <div class="form-group">
                                <label class="title-color d-flex align-items-center gap-2">
                                    {{translate('default_commission')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="right"
                                          title="{{translate('set_the_default_commission_amount_that_will_be_received_from_vendors_on_each_order')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                             alt="">
                                    </span>
                                </label>
                                @php($commission=getWebConfig('sales_commission'))
                                <input type="number" class="form-control" name="commission"
                                       value="{{$commission ?? 0}}"
                                       placeholder="{{translate('ex').':'.'70'}}" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color">
                                    {{translate('enable_POS_in_Vendor_Panel')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="right"
                                          title="{{translate('if_enabled_POS_will_be_available_on_the_Vendor_Panel')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                             alt="">
                                    </span>
                                </span>

                                @php($sellerPos=getWebConfig('seller_pos'))

                                <label class="switcher" for="vendor-pos">
                                    <input type="checkbox" class="switcher_input toggle-switch-message" name="seller_pos" id="vendor-pos"
                                           value="1" {{$sellerPos==1?'checked':''}}
                                           data-modal-id = "toggle-modal"
                                           data-toggle-id = "vendor-pos"
                                           data-on-image = "pos-seller-on.png"
                                           data-off-image = "pos-seller-off.png"
                                           data-on-title = "{{translate('want_to_Turn_ON_POS_for_Vendor')}}"
                                           data-off-title = "{{translate('want_to_Turn_OFF_POS_for_Vendor')}}"
                                           data-on-message = "<p>{{translate('if_enabled_POS_option_will_be_available_in_the_Vendor_Panel')}}</p>"
                                           data-off-message = "<p>{{translate('if_disabled_POS_option_will_be_hidden_from_the_Vendor_Panel')}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color">
                                    {{translate('vendor_registration')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="right"
                                          title="{{translate('if_enabled_vendors_can_send_registration_requests_to_admin')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                             alt="">
                                    </span>
                                </span>

                                @php($vendorRegistration=getWebConfig('seller_registration'))

                                <label class="switcher" for="vendor-registration">
                                    <input type="checkbox" class="switcher_input toggle-switch-message" name="seller_registration"
                                           id="vendor-registration" {{$vendorRegistration==1?'checked':''}} value="1"
                                           data-modal-id = "toggle-modal"
                                           data-toggle-id = "vendor-registration"
                                           data-on-image = "self-registrations-on.png"
                                           data-off-image = "self-registrations-off.png"
                                           data-on-title = "{{translate('want_to_Turn_ON_Self_Registration').'?'}}"
                                           data-off-title = "{{translate('want_to_Turn_OFF_Self_Registration').'?'}}"
                                           data-on-message = "<p>{{translate('if_enabled_Vendors_can_register_by_themselves_from_website_or_app').'.'.translate('admin_can_review_the_registration_request_and_approve_or_deny_the_request')}}</p>"
                                           data-off-message = "<p>{{translate('if_disabled_Vendors_can_not_register_themselves_from_website_or_App').'.'.translate('only_Admin_can_create_an_account_for_any_Vendor')}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color">
                                    {{translate('set_minimum_order_amount')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="right"
                                          title="{{translate('if_enabled_Vendors_can_set_minimum_order_amount_for_their_stores_by_themselves')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                             alt="">
                                    </span>
                                </span>

                                @php($minimumOrderAmountBySeller=getWebConfig('minimum_order_amount_by_seller'))

                                <label class="switcher" for="minimum-order-amount-by-vendor">
                                    <input type="checkbox" value="1" class="switcher_input toggle-switch-message"
                                           name="minimum_order_amount_by_seller" id="minimum-order-amount-by-vendor"
                                           {{ $minimumOrderAmountBySeller == 1 ? 'checked' : '' }}
                                           data-modal-id="toggle-modal"
                                           data-toggle-id="minimum-order-amount-by-vendor"
                                           data-on-image="minimum-order-amount-feature-on.png"
                                           data-off-image="minimum-order-amount-feature-off.png"
                                           data-on-title="{{translate('want_to_Turn_ON_the_Set_Minimum_Order_Amount_option').'?'}}"
                                           data-off-title="{{translate('want_to_Turn_OFF_the_Set_Minimum_Order_Amount_option').'?'}}"
                                           data-on-message="<p>{{translate('if_enabled_Vendors_can_set_minimum_order_amount_for_their_stores_by_themselves').'.'}}</p>"
                                           data-off-message="<p>{{translate('if_disabled_Vendors_cannot_set_the_minimum_order_amount_for_their_store_and_the_admin_will_set_it').'.'}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="border-bottom px-4 py-3">
                    <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/box.png')}}" alt="">
                        {{translate('need_product_approval')}}
                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right"
                              title="{{translate('set_whether_Vendors_need_admin_approval_before_adding_new_products_to_their_shops')}}">
                            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    @php($newProductApproval = getWebConfig('new_product_approval'))
                    @php($productWiseShippingCostApproval = getWebConfig('product_wise_shipping_cost_approval'))
                    <div class="d-flex align-items-center flex-wrap gap-4">
                        <div class="d-flex align-items-center gap-2">
                            <input name="new_product_approval" type="checkbox" value="1"
                                   id="new_product_approval" {{$newProductApproval==1?'checked':''}}>
                            <label class="title-color mb-0" for="new_product_approval">
                                {{translate('new_product')}}
                            </label>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input name="product_wise_shipping_cost_approval" type="checkbox" value="1"
                                   id="product_wise_shipping_cost_approval" {{$productWiseShippingCostApproval==1?'checked':''}}>
                            <label class="title-color mb-0 {{ Session::get('direction') === 'rtl' ? 'text-right' : 'text-left' }}"
                                   for="product_wise_shipping_cost_approval">
                                {{translate('product_wise_shipping_cost')}}
                                <span class="text-info">( {{translate('this_feature_will_activate_whenever_a_Vendor_add_a_product_or_modifies_the_shipping_cost_of_any_product')}} )</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn--primary px-4">{{translate('save')}}</button>
            </div>
        </form>
    </div>
@endsection
