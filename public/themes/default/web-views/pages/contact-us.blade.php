@extends('layouts.front-end.app')

@section('title',translate('contact_us'))

@push('css_or_js')
    <meta property="og:image" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Contact {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <meta property="twitter:card" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Contact {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/css/intlTelInput.css') }}">
@endpush

@section('content')
<div class="__inline-58">
    <div class="container rtl">
        <div class="row">
            <div class="col-md-12 contact-us-page sidebar_heading text-center mb-2">
                <h1 class="h3 mb-0 headerTitle">{{ translate('contact_us') }}</h1>
            </div>
        </div>
    </div>

    <div class="container rtl text-align-direction">
        <div class="row no-gutters py-5">
            <div class="col-lg-6 iframe-full-height-wrap ">
                <img class="for-contact-image" src="{{theme_asset(path: "public/assets/front-end/png/contact.png")}}" alt="">
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body for-send-message">
                        <h2 class="h4 mb-4 text-center font-semibold text-black">{{translate('send_us_a_message')}}</h2>
                        <form action="{{route('contact.store')}}" method="POST" id="getResponse">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label >{{translate('your_name')}}</label>
                                        <input class="form-control name" name="name" type="text"
                                               value="{{ old('name') }}" placeholder="{{ translate('John_Doe') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="cf-email">{{translate('email_address')}}</label>
                                        <input class="form-control email" name="email" type="email"
                                               value="{{ old('email') }}"
                                               placeholder="{{ translate('enter_email_address') }}" required >

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="cf-phone">{{translate('your_phone')}}</label>
                                        <input class="form-control mobile_number phone-input-with-country-picker" type="number"
                                               value="{{ old('mobile_number') }}" placeholder="{{translate('contact_number')}}" required>

                                        <div class="">
                                            <input type="hidden" class="country-picker-country-code w-50" name="country_code" readonly>
                                            <input type="hidden" class="country-picker-phone-number w-50" name="mobile_number" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="cf-subject">{{translate('subject')}}:</label>
                                        <input class="form-control subject" type="text" name="subject"
                                               value="{{ old('subject') }}" placeholder="{{translate('short_title')}}" required>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cf-message">{{translate('message')}}</label>
                                        <textarea class="form-control message" name="message" rows="6" required>{{ old('subject') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            @php($recaptcha = getWebConfig(name: 'recaptcha'))
                            @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                <div id="recaptcha_element" class="w-100" data-type="image"></div>
                                <br/>
                            @else
                                <div class="row mb-3 mt-1">
                                    <div class="col-6 pr-0">
                                        <input type="text" class="form-control" name="default_captcha_value" value=""
                                               placeholder="{{translate('enter_captcha_value')}}" autocomplete="off">
                                    </div>
                                    <div class="col-6 input-icons rounded">
                                        <a href="javascript:" class="get-contact-recaptcha-verify" data-link="{{ URL('/contact/code/captcha') }}">
                                            <img src="{{ URL('/contact/code/captcha/1') }}" class="input-field __h-44 rounded" id="default_recaptcha_id" alt="">
                                            <i class="tio-refresh icon"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class=" ">
                                <button class="btn btn--primary" type="submit" >{{translate('send')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')

@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        "use strict";
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async
            defer></script>
    <script>
        "use strict";
        $("#getResponse").on('submit', function (e) {
            var response = grecaptcha.getResponse();
            if (response.length === 0) {
                e.preventDefault();
                toastr.error($('#message-please-check-recaptcha').data('text'));
            }
        });
    </script>
@endif

<script src="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/js/intlTelInput.js') }}"></script>
<script src="{{ theme_asset(path: 'public/assets/front-end/js/country-picker-init.js') }}"></script>
@endpush

