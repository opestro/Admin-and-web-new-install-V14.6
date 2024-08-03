<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{translate('forgot_password')}}</title>

    <link rel="shortcut icon" href="{{ dynamicStorage(path: 'storage/app/public/company/'.getWebConfig(name: 'company_fav_icon')) }}">

    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/google-fonts.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/icon-set/style.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/theme.minc619.css?v=1.0') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/style.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/toastr.css') }}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/custom.css')}}">
</head>
<body>
<main id="content" role="main" class="main">
    <div class="row">
        <div class="col-12 position-fixed z-9999 mt-10rem">
            <div id="loading" class="d--none">
                <div id="loader"></div>
            </div>
        </div>
    </div>
    <div class="position-fixed top-0 right-0 left-0 bg-img-hero __h-32rem">
        <figure class="position-absolute right-0 bottom-0 left-0">
            <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1921 273">
                <polygon fill="#fff" points="0,273 1921,273 1921,0 "/>
            </svg>
        </figure>
    </div>
    <div class="container py-5 py-sm-7">
        @php($ecommerceLogo=getWebConfig('company_web_logo'))
        <a class="d-flex justify-content-center mb-5" href="javascript:">
            <img class="z-index-2 __w-8rem" src="{{ getValidImage(path:'storage/app/public/company/'.$ecommerceLogo,type: 'backend-logo') }}" alt="{{translate('logo')}}">
        </a>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <h2 class="h3 mb-4">{{translate('forgot_password').'?'}}</h2>
                <p class="font-size-md">{{translate('follow_steps')}}</p>
                <ol class="list-unstyled font-size-md">
                    <li><span class="text-primary mr-2">1.</span>{{translate('fill_in_your_email_address_below').'.'}}</li>
                    <li>
                        <span class="text-primary mr-2">2.</span>{{translate('we_will_send_email you a temporary code').'.'}}
                    </li>
                    <li>
                        <span class="text-primary mr-2">3.</span>{{translate('use_the_code_to_change_your_password_on_our_secure_website').'.'}}
                    </li>
                </ol>
                @php($verificationBy = getWebConfig('forgot_password_verification'))
                @if ($verificationBy=='email')
                    <div class="card py-2 mt-4">
                        <form class="card-body needs-validation" action="{{route('vendor.auth.forgot-password.index')}}"
                              method="post">
                            @csrf
                            <div class="form-group">
                                <label for="recover-email">{{translate('enter_your_email_address')}}</label>
                                <input class="form-control" type="email" name="identity" id="recover-email" required>
                                <div class="invalid-feedback">{{translate('please_provide_valid_email_address.')}}</div>
                            </div>
                            <button class="btn btn-primary forget-password-form" type="button">{{translate('get_new_password')}}</button>
                        </form>
                    </div>
                @else
                    <div class="card py-2 mt-4">
                        <form class="card-body needs-validation" action="{{route('vendor.auth.forgot-password.index')}}"
                              method="post">
                            @csrf
                            <div class="form-group">
                                <label for="phoneLabel"
                                       class="col-form-label input-label">{{translate('phone')}} </label>
                                <div class=" mb-3">
                                    <input class="form-control form-control-user" name="identity"
                                           type="number" id="exampleInputPhone" value="{{old('phone')}}"
                                           placeholder="{{ translate('enter_phone_number') }}" required>
                                </div>
                            </div>
                            <button class="btn btn--primary forget-password-form" type="button">{{translate('get_new password')}}</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade password-reset-successfully-modal" tabindex="-1" aria-labelledby="toggle-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <div class="d-flex flex-column align-items-center text-center gap-2 mb-2">
                        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/password-reset.png')}}" width="70" class="mb-3 mb-20" alt="">
                        <h5 class="modal-title">{{translate('password_reset_successfully')}}</h5>
                        <div class="text-center">{{translate('a_password_reset_mail_has_sent_to_your_email').'. '.translate('please_check_your_email').'.'}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<span class="system-default-country-code" data-value="{{ getWebConfig(name: 'country_code') ?? 'us' }}"></span>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/theme.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/toastr.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/app-script.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/forgot-password.js')}}"></script>

{!! Toastr::message() !!}
@if ($errors->any())
    <script>
        "use strict";
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
</body>
</html>

