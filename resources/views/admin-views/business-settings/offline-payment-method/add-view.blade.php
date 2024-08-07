@extends('layouts.back-end.app')

@section('title', translate('add_Offline_Payment_Method'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.css')}}"/>
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('3rd_party')}}
            </h2>
            <div class="btn-group">
                <div class="ripple-animation" data-toggle="modal" data-target="#getInformationModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z" fill="currentColor"></path>
                    </svg>
                </div>
            </div>
        </div>
        @include('admin-views.business-settings.third-party-payment-method-menu')
        <form action="{{ route('admin.business-settings.offline-payment-method.add') }}" method="POST" id="payment-method-offline">
            @csrf
            <div class="card mt-3">
                <div class="card-header gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/payment-card.png')}}" alt="">
                        <span class="title-color text-capitalize font-weight-bold">
                            {{translate('payment_information')}}
                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('choose_your_preferred_payment_method_such_as_bank,_mobile_wallet,_digital_cards,_etc').' . '.translate('that_customers_will_choose_from_and_add_relevant_input_fields_for_the_payment_method').'.'}}">
                                <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                            </span>
                        </span>
                    </div>
                    <a href="javascript:" id="add-input-fields-group" class="btn btn--primary text-capitalize"><i class="tio-add"></i> {{ translate('add_new_field') }} </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-sm-6">
                            <div class="form-group">
                                <label for="method_name" class="title_color text-capitalize">{{ translate('payment_method_name') }}</label>
                                <input type="text" class="form-control" placeholder="{{ translate('ex').':'.translate('bkash') }}" name="method_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="input-fields-section" id="input-fields-section">
                        @php($inputFieldsRandomNumber = rand())
                        <div class="row align-items-end" id="{{ $inputFieldsRandomNumber }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input_name" class="title_color">{{ translate('input_field_Name') }}</label>
                                    <input type="text" name="input_name[]" class="form-control" placeholder="{{ translate('ex').':'.translate('bank_Name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input_data" class="title_color text-capitalize">{{ translate('input_data') }}</label>
                                    <input type="text" name="input_data[]" class="form-control" placeholder="{{ translate('ex').':'.translate('AVC_bank') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="d-flex justify-content-end">
                                        <a href="javascript:" class="btn btn-outline-danger btn-sm delete square-btn remove-input-fields-group" title="{{translate('delete')}}"  data-id="{{ $inputFieldsRandomNumber }}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/payment-card-fill.png')}}" alt="">
                        <span class="title-color text-capitalize font-weight-bold">
                            {{translate('required_information_from_Customer')}}
                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('add_relevant_input_fields_for_customers_to_fill-up_after_completing_the_offline_payment').' . '. translate('you_can_add_multiple_input_fields_&_place_holders_and_define_them_as_‘Is_Required’,_so_customers_cannot_complete_offline_payment_without_adding_that_information').'.'}}">
                                <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                            </span>
                        </span>
                    </div>
                    <a href="javascript:" id="add-customer-input-fields-group" class="btn btn--primary"><i class="tio-add"></i> {{ translate('Add_New_Field') }} </a>
                </div>
                <div class="card-body">
                    @php($customerInputFieldsRandomNumber = rand())
                    <div class="customer-input-fields-section" id="customer-input-fields-section">
                        <div class="row align-items-end" id="{{ $customerInputFieldsRandomNumber }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title_color">{{ translate('input_field_Name') }}</label>
                                    <input type="text" name="customer_input[]" class="form-control" placeholder="{{ translate('ex').':'.translate('payment_By') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_placeholder" class="title_color">{{ translate('placeholder') }}</label>
                                    <input type="text" name="customer_placeholder[]" class="form-control" placeholder="{{ translate('ex').':'.translate('enter_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="d-flex justify-content-between gap-2">
                                        <div class="form-check text-start mb-3">

                                            <label class="form-check-label text-dark" for="{{ $customerInputFieldsRandomNumber+1 }}">
                                                <input type="checkbox" class="form-check-input" id="{{ $customerInputFieldsRandomNumber+1 }}" name="is_required[0]"> {{ translate('is_required').'?' }}
                                            </label>
                                        </div>

                                        <a class="btn btn-outline-danger btn-sm delete square-btn remove-input-fields-group" title="{{translate('delete')}}"  data-id="{{ $customerInputFieldsRandomNumber }}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-3 mt-3">
                <button type="reset" class="btn btn-secondary px-5">{{ translate('reset') }}</button>
                <button type="submit" class="btn btn--primary px-5">{{ translate('submit') }}</button>
            </div>
        </form>
    </div>
    <div class="modal fade" id="getInformationModal" tabindex="-1" aria-labelledby="getInformationModal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                            class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <div class="swiper mySwiper pb-3">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/delivery2.png')}}" loading="lazy"
                                         alt="">
                                    <h4 class="lh-md mb-3 text-capitalize">{{translate('create_your_custom_offline_payment_method')}}</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>
                                            {{translate('for_a_personalised_payment_experience').'!'}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <h4 class="lh-md mb-3 text-capitalize">{{translate('how_does_offline_payment_method_work').'?'}}</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>
                                            {{translate('step').' '.'1'.' :'.translate('add').' ‘'.translate('Payment_Information').'’'}}
                                        </li>
                                        <li>{{translate('step').' '.'2'.' :'.translate('click').' ‘ +'.translate('Add_New_Field').'’'.translate('for_more_information').'['.translate('according_to_your_payment_method').']'}}</li>
                                        <li>{{translate('step').' '.'3'.' :'.translate('add').' ‘'.translate('Required_Information_from_Customer').'’ '.'['.translate('that_you_need_to_verify_according_to_your_payment_method').']'}}</li>
                                        <li>{{translate('step').' '.'4'.' :'.translate('click').' ‘ +'.translate('Add_New_Field').'’'.translate('for_more_information').'['.translate('according_to_your_payment_method').']'}}</li>
                                        <li>{{translate('step').' '.'5'.' :'.translate('mark_the_check_box_if_the_field_is_required')}}</li>
                                        <li>{{translate('step').' '.'6'.' :'.translate('click').' ‘'.translate('submit').'’ '.translate('to_save_the_changes')}}</li>

                                    </ul>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <h4 class="lh-md mb-3 text-capitalize">{{translate('important_note')}}<i></i></h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>{{translate('you_can_add_one_or_more_offline_payment_methods_for_your_customers')}}</li>
                                        <li>{{translate('when_a_customer_chooses_the_‘Offline Payment’_during_checkout_and_chooses_their_favorite_payment_method,_they_must_fill-up_all_the_required_information_to_confirm_payment').'.'}} </li>
                                        <li>{{translate('later_admin_will_review_the_offline_payment_manually_to_confirm_order_by_changing_the_Order_&_Payment_Status_from_order_details_page').'.'}}
                                        <li>{{translate('to_review_offline_payment:_Go_to_Order_Details_page_>_view_Payment_Information_>_Match_the_payment_information').'.'}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                    <img width="80" class="mb-3"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/confirmed.png')}}" loading="lazy"
                                         alt="">
                                    <h4 class="lh-md mb-3 text-capitalize">{{translate('the_two-in-one_benefits_of_‘Offline_Payment_Method’_Feature').':'}}</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>{{translate('get_paid_from_customers')}}</li>
                                        <li>{{translate('introduce_more_convenient_payment_methods_for_customersEnjoy').'!'}}</li>
                                    </ul>
                                    <button class="btn btn-primary px-10 mt-3 text-capitalize" data-dismiss="modal">{{ translate('got_it') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination mb-2"></div>
                </div>
            </div>
        </div>
    </div>
    <span id="get-add-input-field-text"
          data-input-field-name = "{{translate('input_field_Name')}}"
          data-input-field-name-placeholder = "{{translate('ex').':'.translate('bank_Name')}}"
          data-input-data = "{{translate('input_data')}}"
          data-input-data-placeholder = "{{translate('ex').':'.translate('AVC_bank')}}"
          data-delete-text = "{{translate('delete')}}"
    ></span>
    <span id="get-add-customer-input-field-text"
          data-input-field-name = "{{translate('input_field_Name')}}"
          data-input-field-name-placeholder = "{{translate('ex').':'.translate('payment_By')}}"
          data-input-placeholder = "{{translate('placeholder')}}"
          data-input-placeholder-placeholder = "{{translate('ex').':'.translate('enter_name')}}"
          data-delete-text = "{{translate('delete')}}"
          data-require-text = "{{translate('is_required').'?'}}"
    ></span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/offline-payment.js')}}"></script>
@endpush

