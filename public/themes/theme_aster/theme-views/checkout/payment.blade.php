@php use App\Utils\Helpers; @endphp
@extends('theme-views.layouts.app')

@section('title', translate('Payment_Details').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <h4 class="text-center mb-3 text-capitalize">{{ translate('payment_details') }}</h4>
            <div class="row">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-body  px-sm-4">
                            <div class="d-flex justify-content-center mb-30">
                                <ul class="cart-step-list">
                                    <li class="done cursor-pointer get-view-by-onclick"
                                        data-link="{{route('shop-cart')}}">
                                        <span><i class="bi bi-check2"></i></span> {{ translate('cart') }}
                                    </li>
                                    <li class="done cursor-pointer get-view-by-onclick text-capitalize"
                                        data-link="{{route('checkout-details')}}">
                                        <span><i class="bi bi-check2"></i></span> {{ translate('shipping_details') }}
                                    </li>
                                    <li class="current"><span><i
                                                class="bi bi-check2"></i></span> {{ translate('payment') }}</li>
                                </ul>
                            </div>
                            <h5 class="mb-4 text-capitalize">{{ translate('payment_information') }}</h5>
                            <div class="mb-30">
                                <ul class="option-select-btn flex-wrap gap-3">
                                    @if($cashOnDeliveryBtnShow && $cash_on_delivery['status'])
                                        <li>
                                            <form action="{{route('checkout-complete')}}" method="get">
                                                <label>
                                                    <input type="hidden" name="payment_method" value="cash_on_delivery">
                                                    <button type="submit"
                                                            class="payment-method d-flex border-0 align-items-center gap-3 overflow-hidden">
                                                        <img width="32" class="dark-support" alt=""
                                                             src="{{ theme_asset('assets/img/icons/cash-on.png') }}">
                                                        <span class="text-capitalize">{{ translate('cash_on_delivery') }}</span>
                                                    </button>
                                                </label>
                                            </form>
                                        </li>
                                    @endif
                                    @if(auth('customer')->check() && $wallet_status == 1)
                                        <li>
                                            <label class="">
                                                <button
                                                    class="payment-method d-flex align-iems-center border-0 gap-3 overflow-hidden"
                                                    type="submit" data-bs-toggle="modal"
                                                    data-bs-target="#wallet_submit_button">
                                                    <img width="30"
                                                         src="{{ theme_asset('assets/img/icons/wallet.png') }}"
                                                         class="dark-support" alt="">
                                                    <span>{{ translate('wallet') }}</span>
                                                </button>
                                            </label>
                                        </li>
                                    @endif
                                    @if ($digital_payment['status']==1)
                                        <li class="{{ ((($payment_gateway_published_status == 1 && count($payment_gateways_list) == 0) && (!isset($offline_payment) && !$offline_payment['status']))? 'd-none':'') }}">
                                            <label id="digital-payment-btn">
                                                <input type="hidden">
                                                <span class="payment-method d-flex align-iems-center gap-3">
                                                <img width="30"
                                                     src="{{ theme_asset('assets/img/icons/degital-payment.png') }}"
                                                     class="dark-support" alt="">
                                                <span>{{ translate('Digital_Payment') }}</span>
                                            </span>
                                            </label>
                                        </li>
                                        @foreach ($payment_gateways_list as $payment_gateway)
                                            <li>
                                                <form method="post" class="digital-payment d--none"
                                                      action="{{ route('customer.web-payment-request') }}">
                                                    @csrf
                                                    <input type="hidden" name="user_id"
                                                           value="{{ auth('customer')->check() ? auth('customer')->id() : session('guest_id') }}">
                                                    <input type="hidden" name="customer_id"
                                                           value="{{ auth('customer')->check() ? auth('customer')->id() : session('guest_id') }}">
                                                    <input type="hidden" name="payment_method"
                                                           value="{{ $payment_gateway->key_name }}">
                                                    <input type="hidden" name="payment_platform" value="web">
                                                    @if ($payment_gateway->mode == 'live' && isset($payment_gateway->live_values['callback_url']))
                                                        <input type="hidden" name="callback"
                                                               value="{{ $payment_gateway->live_values['callback_url'] }}">
                                                    @elseif ($payment_gateway->mode == 'test' && isset($payment_gateway->test_values['callback_url']))
                                                        <input type="hidden" name="callback"
                                                               value="{{ $payment_gateway->test_values['callback_url'] }}">
                                                    @else
                                                        <input type="hidden" name="callback" value="">
                                                    @endif
                                                    <input type="hidden" name="external_redirect_link"
                                                           value="{{ url('/').'/web-payment' }}">
                                                    <label>
                                                        @php($additional_data = $payment_gateway['additional_data'] != null ? json_decode($payment_gateway['additional_data']) : [])
                                                        <button
                                                            class="payment-method border-0 d-flex align-iems-center gap-3 digital-payment-card overflow-hidden"
                                                            type="submit">
                                                            <img width="100" class="dark-support" alt=""
                                                                 src="{{ getValidImage(path: 'storage/app/public/payment_modules/gateway_image/'.($additional_data != null ? $additional_data->gateway_image : ''), type:'banner') }}">
                                                        </button>
                                                    </label>
                                                </form>
                                            </li>
                                        @endforeach
                                        @if(isset($offline_payment) && $offline_payment['status'])
                                            <li>
                                                <form action="{{route('offline-payment-checkout-complete')}}"
                                                      method="get" class="digital-payment d--none">
                                                    <label>
                                                        <input type="hidden" name="weight">
                                                        <span
                                                            class="payment-method d-flex align-iems-center gap-3 overflow-hidden"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#offline_payment_submit_button">
                                                            <img width="100"
                                                                 src="{{ theme_asset('assets/img/payment/pay-offline.png') }}"
                                                                 class="dark-support" alt="">
                                                        </span>
                                                    </label>
                                                </form>
                                            </li>
                                        @endif
                                    @endif
                                </ul>

                                @if(auth('customer')->check() && $wallet_status==1)
                                    <div class="modal fade" id="wallet_submit_button">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLongTitle">{{ translate('wallet_payment') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                @php($customer_balance = auth('customer')->user()->wallet_balance)
                                                @php($remain_balance = $customer_balance - $amount)
                                                <form action="{{route('checkout-complete-wallet')}}" method="get"
                                                      class="needs-validation">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-row mb-3">
                                                            <div class="form-group col-12">
                                                                <label
                                                                    for="">{{ translate('your_current_balance') }}</label>
                                                                <input class="form-control" type="text"
                                                                       value="{{Helpers::currency_converter($customer_balance)}}"
                                                                       readonly>
                                                            </div>
                                                        </div>

                                                        <div class="form-row mb-3">
                                                            <div class="form-group col-12">
                                                                <label
                                                                    for="">{{ translate('order_amount') }}</label>
                                                                <input class="form-control" type="text"
                                                                       value="{{Helpers::currency_converter($amount)}}"
                                                                       readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mb-2">
                                                            <div class="form-group col-12">
                                                                <label for="">
                                                                    {{ translate('remaining_balance') }}
                                                                </label>
                                                                <input class="form-control" type="text"
                                                                       value="{{Helpers::currency_converter($remain_balance)}}"
                                                                       readonly>
                                                                @if ($remain_balance<0)
                                                                    <label
                                                                        class="__color-crimson mt-2">{{ translate('you_do_not_have_sufficient_balance_for_pay_this_order') }}
                                                                        !!</label>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" data-bs-dismiss="modal"
                                                                class="update_cart_button fs-16 btn btn-secondary"
                                                                data-dismiss="modal">{{ translate('close') }}</button>
                                                        <button type="submit"
                                                                class="update_cart_button fs-16 btn btn-primary" {{$remain_balance>0? '':'disabled'}}>{{ translate('submit') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($digital_payment['status']==1)
                                    @if(isset($offline_payment) && $offline_payment['status'])
                                        <div class="modal fade" id="offline_payment_submit_button">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="exampleModalLongTitle">{{ translate('offline_Payment') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('offline-payment-checkout-complete')}}"
                                                          method="post" class="needs-validation">
                                                        @csrf
                                                        <div class="modal-body p-3 p-md-5">

                                                            <div class="text-center px-5">
                                                                <img
                                                                    src="{{ theme_asset('assets/img/offline-payments.png') }}"
                                                                    alt="">
                                                                <p class="py-2">
                                                                    {{ translate('pay_your_bill_using_any_of_the_payment_method_below_and_input_the_required_information_in_the_form') }}
                                                                </p>
                                                            </div>

                                                            <div class="">

                                                                <select class="form-select" id="pay-offline-method"
                                                                        name="payment_by" required>
                                                                    <option
                                                                        value="">{{ translate('select_Payment_Method') }}</option>
                                                                    @foreach ($offline_payment_methods as $method)
                                                                        <option value="{{ $method->id }}">{{ translate('payment_Method').':'}}{{ $method->method_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="" id="method-filed-div">
                                                                <div class="text-center py-5">
                                                                    <img class="pt-5"
                                                                         src="{{ theme_asset('assets/img/offline-payments-vectors.png') }}"
                                                                         alt="">
                                                                    <p class="py-2 pb-5 text-muted">{{ translate('select_a_payment_method first') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @include('theme-views.partials._order-summery')
            </div>
        </div>
    </main>
    <span class="get-payment-method-list" data-action="{{route('pay-offline-method-list')}}"></span>
@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/payment-page.js') }}"></script>
@endpush
