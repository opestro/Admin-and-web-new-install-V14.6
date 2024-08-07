@extends('layouts.back-end.app')

@section('title', translate('product_settings'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/business-setup.png')}}" alt="">
                {{translate('business_Setup')}}
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
                    <p class="title-color font-weight-medium mb-0">{{ translate('please_click_save_information_button_below_to_save_all_the_changes') }}</p>
                </div>
            </div>
        </div>
        @include('admin-views.business-settings.business-setup-inline-menu')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-capitalize d-flex gap-2 text-capitalize">
                    <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/product_setup.png')}}" alt="">
                    {{translate('product_setup')}}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.product-settings.index') }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color d-flex text-capitalize"
                                           for="stock_limit">{{translate('re-order_level')}}</label>
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="right" title="{{translate('set_the_stock_limit_for_the_Reorder_level')}}
                                    {{translate ('vendors_can_see_all_products_that_need_to_be_re_stocked_in_a_section_when_they_reach_this_ReOrder_Level')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                             alt="">
                                    </span>
                                </div>
                                <input class="form-control" type="text" name="stock_limit" id="stock_limit"
                                       value="{{ $stockLimit['value'] }}"
                                       placeholder="{{translate('ex').':'.'10'}}">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                    <span class="title-color text-capitalize">
                                        {{translate('sell_digital_product')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('if_enabled_vendors_can_sell_digital_products') }} ({{ translate('software') }}, {{ translate('ebooks') }}, {{ translate('activation_keys') }}, JPG, PNG, etc.) {{ translate('in_their_shops') }}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>
                                    <label class="switcher" for="digital-product">
                                        <input type="checkbox" class="switcher_input toggle-switch-message" value="1" name="digital_product"
                                               id="digital-product" {{ $digitalProduct && $digitalProduct['value'] ? 'checked':'' }}
                                               data-modal-id = "toggle-modal"
                                               data-toggle-id = "digital-product"
                                               data-on-image = "digital-product-on.png"
                                               data-off-image = "digital-product-off.png"
                                               data-on-title = "{{translate('want_to_Turn_ON_Digital_Product').'?'}}"
                                               data-off-title = "{{translate('want_to_Turn_OFF_Digital_Product').'?'}}"
                                               data-on-message = "<p>{{translate('if_enabled_vendors_can_sell_digital_products_in_their_shops')}}</p>"
                                               data-off-message = "<p>{{translate('if_disabled_vendors_can_not_sell_digital_products_in_their_shops')}}</p>">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                    <span class="title-color">
                                        {{translate('show_brand')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('if_enabled_customers_can_see_brands_on_the_app_and_website').'.'.translate('they_can_browse_and_search_for_products_from_each_brand_inside_any_shop')}}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="product-brand">
                                        <input type="checkbox" class="switcher_input toggle-switch-message" value="1" name="product_brand"
                                               id="product-brand" {{ $brand && $brand['value'] ? 'checked':'' }}
                                               data-modal-id = "toggle-modal"
                                               data-toggle-id = "product-brand"
                                               data-on-image = "product-brand-on.png"
                                               data-off-image = "product-brand-off.png"
                                               data-on-title = "{{translate('want_to_Turn_ON_Product_Brand').'?'}}"
                                               data-off-title = "{{translate('want_to_Turn_OFF_Product_Brand').'?'}}"
                                               data-on-message = "<p>{{translate('if_enabled_customers_can_see_brands_on_the_app_and_website')}}</p>"
                                               data-off-message = "<p>{{translate('if_disabled_brand_section_will_be_hidden_from_the_customer_app_and_website')}}</p>">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn--primary px-4">{{translate('save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
