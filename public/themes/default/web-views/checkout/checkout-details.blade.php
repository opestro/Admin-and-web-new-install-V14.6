@extends('layouts.front-end.app')

@section('title', translate('Checkout'))

@section('content')

    <div class="container pb-5 mb-2 mb-md-4 rtl __inline-54 text-align-direction checkout-details-page">
        <div class="row">
            <div class="col-md-12 mb-5 pt-5">
                <div class="feature_header __feature_header">
                    <span>{{ translate('sign_in')}}</span>
                </div>
            </div>
            <section class="col-lg-8">
                <div class="checkout_details">
                    @include('web-views.partials._checkout-steps',['step'=>1])
                    <h2 class="h4 pb-3 mb-2 mt-5">{{translate('authentication')}}</h2>
                    @if(auth('customer')->check())
                        <div class="card">
                            <div class="card-body">
                                <h4>{{auth('customer')->user()->f_name}}, {{translate('Hi')}}!</h4>
                                <small>{{translate('you_are_already_Sign_in_proceed')}}.</small>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-tabs mt-2 d-flex justify-content-between" role="tablist">
                                    <li class="nav-item d-inline-block">
                                        <a class="nav-link active" href="#signin" data-toggle="tab" role="tab">
                                            {{translate('sign_in')}}
                                        </a>
                                    </li>
                                    <li class="nav-item d-inline-block">
                                        <a class="nav-link" href="#signup" data-toggle="tab" role="tab">
                                            {{ translate('sign_up')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="signin" role="tabpanel">
                                        <form class="needs-validation" autocomplete="off" id="login-form"
                                              action="{{route('customer.auth.login')}}" method="post" novalidate>
                                            @csrf
                                            <div class="form-row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="si-email">
                                                            {{ translate('email_address')}}
                                                        </label>
                                                        <input class="form-control" type="email" name="email"
                                                               id="si-email" value="{{old('email')}}"
                                                               placeholder="{{ translate('enter_your_email') }}" required>
                                                        <div class="invalid-feedback">
                                                            {{ translate('please_provide_a_valid_email_address')}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="si-password">{{ translate('password')}}</label>
                                                        <div class="password-toggle rtl">
                                                            <input class="form-control" name="password" type="password"
                                                                   id="si-password" required>
                                                            <label class="password-toggle-btn">
                                                                <input class="custom-control-input" type="checkbox"><i
                                                                    class="czi-eye password-toggle-indicator"></i><span
                                                                    class="sr-only">{{ translate('show_password')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-sm-6">
                                                    <div class="form-group d-flex flex-wrap justify-content-between">
                                                        <div class="mb-2">
                                                            <input type="checkbox" name="remember"
                                                                   {{ old('remember') ? 'checked' : '' }}
                                                                   id="remember_me">
                                                            <label for="remember_me" class="cursor-pointer">
                                                                {{ translate('remember_me')}}
                                                            </label>

                                                            <a class="font-size-sm {{Session::get('direction') === "rtl" ? 'mr-5' : 'ml-5'}}"
                                                               href="{{route('customer.auth.recover-password')}}">
                                                                {{ translate('forgot_password')}}?
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <button class="btn btn--primary btn-block" type="submit">
                                                        {{ translate('sing_in')}}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="signup" role="tabpanel">
                                        <form class="needs-validation_" autocomplete="off" novalidate id="sign-up-form"
                                              action="{{route('customer.auth.register')}}" method="post">
                                            @csrf
                                            <div class="form-row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="su-name">{{ translate('first_name')}}</label>
                                                        <input class="form-control" type="text" name="f_name"
                                                               placeholder="{{ translate('John') }}" required>
                                                        <div class="invalid-feedback">
                                                            {{ translate('please_fill_in_your_first_name.')}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="su-name">{{ translate('last_name')}} </label>
                                                        <input class="form-control" type="text" name="l_name"
                                                               placeholder="{{ translate('Doe') }}" required>
                                                        <div class="invalid-feedback">
                                                            {{ translate('please_fill_in_your_last_name.')}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="su-email">
                                                            {{ translate('email_address')}}
                                                        </label>
                                                        <input class="form-control" name="email" type="email"
                                                               id="su-email" placeholder="{{ translate('enter_your_email') }}"
                                                               required>
                                                        <div class="invalid-feedback">
                                                            {{ translate('please_provide_a_valid_email_address.')}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="su-email">{{ translate('phone')}}</label>
                                                        <input class="form-control" name="phone" type="number"
                                                               id="su-phone" placeholder="{{ translate('01700000000')}}"
                                                               required>
                                                        <div class="invalid-feedback">
                                                            {{ translate('please_provide_a_valid_phone_number.')}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="su-password">{{ translate('password')}}</label>
                                                        <div class="password-toggle">
                                                            <input class="form-control" name="password" type="password"
                                                                   id="su-password" required>
                                                            <label class="password-toggle-btn">
                                                                <input class="custom-control-input" type="checkbox"><i
                                                                    class="czi-eye password-toggle-indicator"></i><span
                                                                    class="sr-only">{{ translate('show_password')}}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="su-password-confirm">
                                                            {{ translate('confirm_password')}}
                                                        </label>
                                                        <div class="password-toggle rtl">
                                                            <input class="form-control" name="con_password"
                                                                   type="password" id="su-password-confirm"
                                                                   required>
                                                            <label class="password-toggle-btn">
                                                                <input class="custom-control-input" type="checkbox">
                                                                <i class="czi-eye password-toggle-indicator"></i>
                                                                <span class="sr-only">
                                                                    {{ translate('show_password')}}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <button class="btn btn--primary btn-block" type="submit">
                                                        {{ translate('sign_up')}}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <br>
                <div class="row">
                    <div class="col-6">
                        <a class="btn btn-secondary btn-block" href="{{route('shop-cart')}}">
                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} mt-sm-0 mx-1"></i>
                            <span
                                class="d-none d-sm-inline">{{ translate('back_to_cart')}} </span>
                            <span class="d-inline d-sm-none">{{ translate('back')}}</span>
                        </a>
                    </div>
                    <div class="col-6">
                        @if(auth('customer')->check())
                            <a class="btn btn--primary btn-block" href="{{route('shop-cart')}}">
                                <span class="d-none d-sm-inline">{{ translate('shop_cart')}}</span>
                                <span class="d-inline d-sm-none">{{ translate('next')}}</span>
                                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}} mt-sm-0 mx-1"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </section>

            @include('web-views.partials._order-summary')
        </div>
    </div>

    <span id="route-action-checkout-function" data-route="checkout-details"></span>
@endsection

@push('script')

    <script>
        $('#login-form').submit(function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('customer.auth.login')}}',
                dataType: 'json',
                data: $('#login-form').serialize(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    toastr.success(data.message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    location.reload();
                },
                complete: function () {
                    $('#loading').hide();
                },
                error: function () {
                    toastr.error('{{ translate("credential_not_matched")}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        $('#sign-up-form').submit(function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('customer.auth.register')}}',
                dataType: 'json',
                data: $('#sign-up-form').serialize(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setInterval(function () {
                            location.href = data.url;
                        }, 2000);
                    }
                },
                complete: function () {
                    $('#loading').hide();
                },
                error: function () {
                    toastr.error('{{ translate("something_went_wrong")}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>

@endpush
