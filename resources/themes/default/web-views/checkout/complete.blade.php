@extends('layouts.front-end.app')

@section('title', translate('order_Complete'))

@section('content')
    <div class="container mt-5 mb-5 rtl __inline-53 text-align-direction">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 col-lg-10">
                <div class="card">
                    @if(auth('customer')->check() || session('guest_id'))
                        <div class="card-body">
                            <div class="mb-3 text-center">
                                <i class="fa fa-check-circle __text-60px __color-0f9d58"></i>
                            </div>

                            <h6 class="font-black fw-bold text-center">
                                @if(isset($isNewCustomerInSession) && $isNewCustomerInSession)
                                    {{ translate('Order_Placed_&_Account_Created_Successfully') }}!
                                @else
                                    {{ translate('Order_Placed_Successfully') }}!
                                @endif
                            </h6>

                            @if (isset($order_ids) && count($order_ids) > 0)
                                <p class="text-center fs-12">
                                    {{ translate('your_payment_has_been_successfully_processed_and_your_order') }} -
                                    <span class="fw-bold text-primary">
                                        @foreach ($order_ids as $key => $order)
                                            {{ $order }}
                                        @endforeach
                                    </span>
                                    {{ translate('has_been_placed.') }}
                                </p>
                            @else
                                <p class="text-center fs-12">
                                    {{ translate('your_order_is_being_processed_and_will_be_completed.') }}
                                    {{ translate('You_will_receive_an_email_confirmation_when_your_order_is_placed.') }}
                                </p>
                            @endif

                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <a href="{{ route('track-order.index') }}"
                                       class="btn btn--primary mb-3 text-center">
                                        {{ translate('track_Order')}}
                                    </a>
                                </div>
                                <div class="col-12 text-center">
                                    <a href="{{route('home')}}" class="text-center">
                                        {{ translate('Continue_Shopping') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
