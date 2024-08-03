@extends('layouts.back-end.app')

@section('title',translate('customer_wallet_bonus_edit'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/admin-wallet.png')}}" alt="">
                {{translate('wallet_bonus_edit')}}
            </h2>
            <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder">
                {{translate('how_it_works')}}
                <div class="ripple-animation" data-toggle="modal" data-target="#howItWorksModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"
                         class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z"
                              fill="currentColor"></path>
                    </svg>
                </div>
            </div>
            <div class="modal fade" id="howItWorksModal" tabindex="-1" aria-labelledby="howItWorksModal"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                            <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                                        class="tio-clear"></i></button>
                        </div>
                        <div class="modal-body px-4 px-sm-5 pt-0 text-center">
                            <div class="d-flex flex-column align-items-center gap-2">
                                <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/para.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md">
                                    {{ translate('wallet_bonus_is_only_applicable_when_a_customer_add_fund_to_wallet_via_outside_payment_gateway').'!' }}
                                </h4>
                                <p>{{ translate('customer_will_get_extra_amount_to_his_or_her_wallet_additionally_with_the_amount_he_or_she_added_from_other_payment_gateways').' '.translate('the_bonus_amount_will_be_deduct_from_admin_wallet_&_will_consider_as_admin_expense') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.customer.wallet.bonus-setup-update') }}" id="form-submit" method="post">
                    @csrf
                    <div class="row gx-2">
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="bonus_title"
                                       class="title-color text-capitalize d-flex">{{translate('bonus_title')}}</label>
                                <input type="text" name="title" class="form-control" id="bonus_title"
                                       placeholder="{{translate('ex').':'.translate('EID_Dhamaka')}}" value="{{ $data->title }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="short_desc"
                                       class="title-color text-capitalize d-flex">{{translate('short_description')}}</label>
                                <input type="text" name="description" class="form-control" id="short_desc"
                                       placeholder="{{translate('ex').':'.translate('EID_Dhamaka')}}" value="{{ $data->description }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="bonus-type"
                                       class="title-color text-capitalize d-flex">{{translate('bonus_type')}}</label>
                                <select name="bonus_type" id="bonus-type" class="form-control">
                                    <option value="percentage" {{ $data->bonus_type == 'percentage' ? 'selected':'' }}>{{translate('percentage ').'(%)'}}</option>
                                    <option value="fixed" {{ $data->bonus_type == 'fixed' ? 'selected':'' }}>{{translate('fixed_amount')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="bonus_amount"
                                       class="title-color text-capitalize d-flex">
                                    {{translate('bonus_amount')}}(<span id="bonus-title-percent">{{translate('%')}}</span>)
                                </label>
                                <input type="number" name="bonus_amount" min="0" class="form-control" id="bonus-title-percent"
                                       placeholder="{{translate('ex').':'.'5'}}"
                                       value="{{ $data->bonus_type == 'fixed' ? (usdToDefaultCurrency(amount: $data->bonus_amount) ?? 0) : $data->bonus_amount }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="min_add_money_amount"
                                       class="title-color text-capitalize d-flex">{{translate('minimum_add_amount')}}
                                    ({{ getCurrencySymbol(currencyCode: getCurrencyCode(type: 'default')) }})</label>
                                <input type="number" name="min_add_money_amount" min="0" class="form-control"
                                       id="min_add_money_amount" placeholder="{{translate('ex').':'.'100'}}"
                                       value="{{ usdToDefaultCurrency(amount: $data->min_add_money_amount) ?? 0 }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4" id="max-bonus-amount-area">
                            <div class="form-group">
                                <label for="max_bonus_amount"
                                       class="title-color text-capitalize d-flex">{{translate('maximum_bonus')}}
                                    ({{getCurrencySymbol(currencyCode: getCurrencyCode(type: 'default'))}})</label>
                                <input type="number" min="0" name="max_bonus_amount" class="form-control"
                                       id="max_bonus_amount" placeholder="{{translate('ex').':'.'5000'}}"
                                       value="{{ usdToDefaultCurrency(amount: $data->max_bonus_amount) ?? 0 }}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="start-date-time"
                                       class="title-color text-capitalize d-flex">{{translate('start_date')}}</label>
                                <input type="date" name="start_date_time" id="start-date-time" class="form-control"
                                       value="{{ date('Y-m-d',strtotime($data['start_date_time'])) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="end-date-time"
                                       class="title-color text-capitalize d-flex">{{translate('end_date')}}</label>
                                <input type="date" name="end_date_time" id="end-date-time" class="form-control"
                                       value="{{ date('Y-m-d',strtotime($data['end_date_time'])) }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{route('admin.customer.wallet.bonus-setup')}}"
                                   class="btn btn-secondary px-5">{{translate('back')}}</a>
                                <button type="submit" class="btn btn--primary px-5">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/customer/wallet.js')}}"></script>
@endpush

