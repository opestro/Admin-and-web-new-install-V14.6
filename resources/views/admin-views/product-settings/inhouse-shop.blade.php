@extends('layouts.back-end.app')

@section('title', translate('inhouse_shop'))

@section('content')
    <div class="content container-fluid">

        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/business-setup.png') }}" alt="">
                {{ translate('business_Setup') }}
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
                        <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/note.png') }}" alt="">
                        <h5 class="text-primary mb-0">{{ translate('note') }}</h5>
                    </div>
                    <p class="title-color font-weight-medium mb-0">{{ translate('please_click_the_Save_button_below_to_save_all_the_changes') }}</p>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('admin.product-settings.inhouse-shop-temporary-close') }}" method="post"
                      id="temporary-close-form">
                    @csrf
                    <div class="border rounded border-color-c1 px-4 py-3 d-flex justify-content-between mb-1">
                        <h5 class="mb-0 d-flex gap-1 c1">
                            {{ translate('temporary_close') }}
                        </h5>
                        <div class="position-relative">
                            <label class="switcher">
                                <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                       id="temporary-close" value="1" {{ $temporaryClose['status'] == 1 ? 'checked' : '' }}
                                       data-modal-id="toggle-status-modal"
                                       data-toggle-id="temporary-close"
                                       data-on-image="store-temporary-close-on.png"
                                       data-off-image="store-temporary-close-off.png"
                                       data-on-title="{{ translate('want_to_Turn_ON_the_Temporary_Close_option') }}"
                                       data-off-title="{{ translate('want_to_Turn_OFF_the_Temporary_Close_option') }}"
                                       data-on-message="<p>{{ translate('if_enabled_admin_can_temporarily_pause_his_shop_activities') }}</p>"
                                       data-off-message="<p>{{ translate('if_disabled_this_feature_will_be_hidden_from_the_system') }}</p>">
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                    </div>
                    <p>*{{ translate('by_turning_on_the') }} "{{ translate('temporary_Close') }}”
                        {{ translate('button_admin_can_pause_his_shop_activities_and_his_shop_will_be_shown_as') }}
                        "{{ translate('temporary_Close') }}” {{ translate('in_the_system') }}
                        . {{ translate('Customers_will_not_be_able_to_order_or_purchase_from_his_shop') }}</p>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 justify-content-between mb-4">
                    <div class="d-flex flex-column gap-1">
                        <h3 class="mb-0 d-flex gap-2 flex-wrap text-capitalize">{{ translate('shop_details') }}</h3>
                        <p class="mb-0">{{ translate('created_at') }} {{ date('d M, Y', strtotime($admin['updated_at']) ) }}</p>
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <button class="btn btn-outline--primary" data-toggle="modal" data-target="#vacation_mode_modal">
                            {{ translate('go_to_Vacation_Mode') }}
                        </button>
                        <a href="{{ route('admin.product-settings.inhouse-shop').'?action=edit' }}"
                           class="btn btn--primary d-flex gap-2 align-items-center"><i
                                    class="tio-edit"></i>{{ translate('edit_shop') }}</a>
                    </div>
                </div>

                <div class="pt-10 rounded bg-position-center bg-soft-secondary"
                     data-bg-img="{{ getValidImage(path: 'storage/app/public/shop/'. getWebConfig(name: 'shop_banner'), type:'backend-banner') }}">
                    <div class="media flex-wrap align-items-end gap-3 p-2">
                        <div class="bg-white rounded py-4 px-5 shadow-lg">
                            <img width="80"
                                 src="{{ getValidImage(path: 'storage/app/public/company/'.getWebConfig(name: 'company_fav_icon'), type: 'backend-logo') }}"
                                 alt="">
                        </div>
                        <div class="media-body">
                            <div class="d-flex flex-column align-items-start gap-1 mb-3">
                                <h3 class="text-white fz-24">{{ getWebConfig(name: 'company_name' ) }} {{ translate('shop') }}</h3>
                                <a href="{{ route('shopView',['id'=>0]) }}" target="_blank"
                                   class="btn btn--primary d-flex gap-2 align-items-center text-nowrap"><i
                                            class="tio-globe"></i>{{ translate('visit_website') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($minimumOrderAmountStatus || $free_delivery_status)
                    <div class="mt-5">
                        <form action="{{ route('admin.product-settings.inhouse-shop') }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="bg-white rounded border">
                                <div class="border-bottom p-3">
                                    <h5 class="mb-0 text-capitalize d-flex gap-2">
                                        <i class="tio-photo-square-outlined"></i>
                                        {{ translate('shop_settings') }}
                                    </h5>
                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        @if ($minimumOrderAmountStatus)
                                            <div class="col-lg-4 col-mg-6">
                                                <div class="form-group">
                                                    <label class="title-color d-flex" for="minimum_order_amount">
                                                        {{ translate('minimum_order_amount') }} {{ getCurrencySymbol(currencyCode: getCurrencyCode()) }}
                                                        <span class="input-label-secondary cursor-pointer"
                                                              data-toggle="tooltip" data-placement="right"
                                                              title="{{ translate('set_the_minimum_order_amount_a_customer_must_order_from_the_inhouse_shop') }}">
                                                    <img width="16"
                                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}"
                                                         alt="">
                                                </span>
                                                    </label>
                                                    <input type="number" min="0" class="form-control"
                                                           name="minimum_order_amount" id="minimum_order_amount"
                                                           placeholder="{{ translate('ex') }}: 10"
                                                           value="{{ usdToDefaultCurrency(amount: $minimumOrderAmount['value']) }}">
                                                </div>
                                            </div>
                                        @endif

                                        @if($freeDeliveryStatus)
                                            <div class="col-lg-4 col-mg-6">
                                                <div class="form-group">
                                                    <label class="title-color d-flex"
                                                           for="free_delivery_over_amount">{{ translate('free_Delivery_Over_Amount') }}
                                                        ({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }})</label>
                                                    <input type="number" min="0" class="form-control"
                                                           name="free_delivery_over_amount"
                                                           id="free_delivery_over_amount"
                                                           placeholder="{{ translate('ex') }}: 10"
                                                           value="{{ usdToDefaultCurrency(amount: $freeDeliveryOverAmount['value'] ) }}">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-30">
                                <button type="submit" class="btn btn--primary px-4">
                                    {{ translate('save_information') }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="modal fade" id="vacation_mode_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content"
                     style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <form action="{{ route('admin.product-settings.vacation-add') }}" method="post">
                        @csrf
                        <div class="modal-header border-bottom pb-2">
                            <div>
                                <h5 class="modal-title" id="exampleModalLabel">{{ translate('vacation_Mode') }}</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="switcher">
                                        <input type="checkbox" name="status" class="switcher_input"
                                               id="vacation_close" {{$vacation['status'] == 1?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="close pt-0" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="mb-5">
                                * {{ translate('set_vacation_mode_for_shop_means_you_will_be_not_available_receive_order_and_provider_products_for_placed_order_at_that_time') }}</div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{ translate('vacation_Start') }}</label>
                                    <input type="date" name="vacation_start_date"
                                           value="{{ $vacation['vacation_start_date'] }}" id="inhouse-vacation-start-date"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>{{ translate('vacation_End') }}</label>
                                    <input type="date" name="vacation_end_date"
                                           value="{{ $vacation['vacation_end_date'] }}" id="inhouse-vacation-end-date"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-12 mt-2 ">
                                    <label>{{ translate('vacation_Note') }}</label>
                                    <textarea class="form-control" name="vacation_note" id="vacation_note">{{ $vacation['vacation_note'] }}</textarea>
                                </div>
                            </div>

                            <div class="text-end gap-5 mt-2">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    {{ translate('close') }}
                                </button>
                                <button type="submit" class="btn btn--primary">
                                    {{ translate('update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
