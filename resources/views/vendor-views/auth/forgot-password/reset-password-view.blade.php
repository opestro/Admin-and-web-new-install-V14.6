<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{translate('vendor')}} | {{translate('reset_Password')}}</title>
    <link rel="shortcut icon" href="{{ dynamicStorage(path: 'storage/app/public/company/'.getWebConfig(name: 'company_fav_icon')) }}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/google-fonts.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/vendor.min.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/vendor/icon-set/style.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/theme.minc619.css?v=1.0')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/toastr.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/style.css')}}">
</head>

<body>
<main id="content" role="main" class="main">
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
            <img class="z-index-2 __w-8rem" src="{{getValidImage(path:'storage/app/public/company/'.$ecommerceLogo, type: 'backend-logo')}}" alt="{{translate('logo')}}">
        </a>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <h2 class="h3 mb-4">{{translate('forget_your_password').'?'}}</h2>
                <p class="font-size-md">{{translate('follow_steps_secure').'.'}}</p>
                <ol class="list-unstyled font-size-md">
                    <li><span class="text-primary mr-2">1.</span>{{translate('new_password').'.'}}</li>
                    <li><span class="text-primary mr-2">2.</span>{{translate('confirm_password').'.'}}</li>
                </ol>
                <div class="card py-2 mt-4">
                    <form class="card-body needs-validation" novalidate method="POST"
                          action="{{ route('vendor.auth.forgot-password.reset-password') }}">
                        @csrf
                        <div class="form-group d-none">
                            <input type="text" name="reset_token" value="{{$token}}" required>
                        </div>
                        <div class="form-group">
                            <label for="si-password" class="d-flex align-items-center">
                                {{translate('new_password')}}
                                <small class="text-danger mx-1 password-error"></small>
                            </label>

                            <div class="input-group input-group-merge">
                                <input type="password" class="js-toggle-password form-control password-check"
                                       name="password" required id="user_password"
                                       placeholder="{{ translate('password_minimum_8_characters') }}"
                                       data-hs-toggle-password-options='{
                                                         "target": "#changePassTarget",
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": "#changePassIcon"
                                                }'>
                                <div id="changePassTarget" class="input-group-append">
                                    <a class="input-group-text" href="javascript:">
                                        <i id="changePassIcon" class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="si-password">{{translate('confirm_password')}}</label>

                            <div class="input-group input-group-merge">
                                <input type="password" class="js-toggle-password form-control"
                                       name="confirm_password" required id="confirm_password"
                                       placeholder="{{ translate('confirm_password') }}"
                                       data-hs-toggle-password-options='{
                                                         "target": "#changeConfirmPassTarget",
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": "#changeConfirmPassIcon"
                                                }'>
                                <div id="changeConfirmPassTarget" class="input-group-append">
                                    <a class="input-group-text" href="javascript:">
                                        <i id="changeConfirmPassIcon" class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn--primary forget-password-form" type="button">{{translate('reset_password')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <span id="password-error-message" data-max-character="{{translate('at_least_8_characters').'.'}}" data-uppercase-character="{{translate('at_least_one_uppercase_letter_').'(A...Z)'.'.'}}" data-lowercase-character="{{translate('at_least_one_uppercase_letter_').'(a...z)'.'.'}}"
          data-number="{{translate('at_least_one_number').'(0...9)'.'.'}}" data-symbol="{{translate('at_least_one_symbol').'(!...%)'.'.'}}"></span>

</main>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/theme.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/toastr.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/forgot-password.js')}}"></script>
<span id="message-copied_success" data-text="{{ translate('copied_successfully') }}"></span>
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

