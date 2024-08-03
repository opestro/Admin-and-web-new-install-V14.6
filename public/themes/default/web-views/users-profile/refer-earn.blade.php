@extends('layouts.front-end.app')

@section('title', translate('Refer_&_Earn'))

@section('content')
    <div class="container py-2 py-md-4 p-0 p-md-2 user-profile-container px-5px">
        <div class="row">
            @include('web-views.partials._profile-aside')

            <div class="col-lg-9 __customer-profile px-0">

                <div class="card">
                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                            <h5 class="font-bold m-0 fs-16">{{ translate('Refer_&_Earn') }}</h5>

                            <button class="profile-aside-btn btn btn--primary px-2 rounded px-2 py-1 d-lg-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7 9.81219C7 9.41419 6.842 9.03269 6.5605 8.75169C6.2795 8.47019 5.898 8.31219 5.5 8.31219C4.507 8.31219 2.993 8.31219 2 8.31219C1.602 8.31219 1.2205 8.47019 0.939499 8.75169C0.657999 9.03269 0.5 9.41419 0.5 9.81219V13.3122C0.5 13.7102 0.657999 14.0917 0.939499 14.3727C1.2205 14.6542 1.602 14.8122 2 14.8122H5.5C5.898 14.8122 6.2795 14.6542 6.5605 14.3727C6.842 14.0917 7 13.7102 7 13.3122V9.81219ZM14.5 9.81219C14.5 9.41419 14.342 9.03269 14.0605 8.75169C13.7795 8.47019 13.398 8.31219 13 8.31219C12.007 8.31219 10.493 8.31219 9.5 8.31219C9.102 8.31219 8.7205 8.47019 8.4395 8.75169C8.158 9.03269 8 9.41419 8 9.81219V13.3122C8 13.7102 8.158 14.0917 8.4395 14.3727C8.7205 14.6542 9.102 14.8122 9.5 14.8122H13C13.398 14.8122 13.7795 14.6542 14.0605 14.3727C14.342 14.0917 14.5 13.7102 14.5 13.3122V9.81219ZM12.3105 7.20869L14.3965 5.12269C14.982 4.53719 14.982 3.58719 14.3965 3.00169L12.3105 0.915687C11.725 0.330188 10.775 0.330188 10.1895 0.915687L8.1035 3.00169C7.518 3.58719 7.518 4.53719 8.1035 5.12269L10.1895 7.20869C10.775 7.79419 11.725 7.79419 12.3105 7.20869ZM7 2.31219C7 1.91419 6.842 1.53269 6.5605 1.25169C6.2795 0.970186 5.898 0.812187 5.5 0.812187C4.507 0.812187 2.993 0.812187 2 0.812187C1.602 0.812187 1.2205 0.970186 0.939499 1.25169C0.657999 1.53269 0.5 1.91419 0.5 2.31219V5.81219C0.5 6.21019 0.657999 6.59169 0.939499 6.87269C1.2205 7.15419 1.602 7.31219 2 7.31219H5.5C5.898 7.31219 6.2795 7.15419 6.5605 6.87269C6.842 6.59169 7 6.21019 7 5.81219V2.31219Z" fill="white"/>
                                </svg>
                            </button>
                        </div>

                        <div class="refer_and_earn_section">
                            <div class="d-flex justify-content-center align-items-center py-2 mb-3">
                                <div class="banner-img">
                                    <img class="img-fluid" src="{{ theme_asset(path: 'public/assets/front-end/img/refer-and-earn.png') }}" alt="" width="300">
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="primary-heading mb-2">{{ translate('invite_Your_Friends_&_Businesses') }}</h5>
                                <p class="secondary-heading">{{ translate('copy_your_code_and_share_your_friends') }}</p>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <h6 class="text-secondary-color">{{ translate('your_personal_code') }}</h6>
                                    <div class="refer_code_box">
                                        <div class="refer_code click-to-copy-data-value" data-value="{{ $customer_detail->referral_code }}">{{ $customer_detail->referral_code }}</div>
                                        <span class="refer_code_copy click-to-copy-data-value" data-value="{{ $customer_detail->referral_code }}">
                                            <img class="w-100" src="{{ theme_asset(path: 'public/assets/front-end/img/icons/solar_copy-bold-duotone.png') }}" alt="">
                                        </span>
                                    </div>

                                    <h4 class="share-icons-heading">{{ translate('oR_SHARE') }}</h4>
                                    <div class="d-flex justify-content-center align-items-center share-on-social">
                                        @php
                                            $text = "Greetings,".getWebConfig('company_name').' '."is the best e-commerce platform in the country.If you are new to this website dont forget to use " . $customer_detail->referral_code . " " ."as the referral code while sign up into ".' '.getWebConfig('company_name').'.';
                                            $link = url('/');
                                        @endphp
                                        <a href="https://api.whatsapp.com/send?text={{$text}}.{{$link}}" target="_blank">
                                            <img alt=""
                                                 src="{{ getValidImage(path: 'public/assets/front-end/img/icons/whatsapp.png', type: 'product') }}">
                                        </a>
                                        <a href="mailto:recipient@example.com?subject=Referral%20Code%20Text&body={{$text}}%20Link:%20{{$link}}" target="_blank">
                                            <img alt=""
                                                 src="{{ getValidImage(path: 'public/assets/front-end/img/icons/gmail.png', type: 'product') }}">
                                        </a>

                                        <span data-target="#social-share-modal" data-toggle="modal">
                                            <img alt=""
                                                 src="{{ getValidImage(path: 'public/assets/front-end/img/icons/share.png', type: 'product') }}">
                                        </span>
                                    </div>
                                </div>

                                <div class="information-section col-md-10">
                                    <h4 class="text-bold d-flex align-items-center"> <span class="custom-info-icon me-2">i</span> {{ translate('how_you_it_works') }}?</h4>

                                    <ul>
                                        <li>
                                            <span class="item-custom-index">
                                                {{ translate('1') }}
                                            </span>
                                            <span class="item-custom-text">
                                                {{ translate('invite_your_friends_&_businesses') }}
                                            </span>
                                        </li>
                                        <li>
                                            <span class="item-custom-index">
                                                {{ translate('2') }}
                                            </span>
                                            <span class="item-custom-text">
                                                {{ translate('they_register') }} {{ $web_config['name']->value }} {{ translate('with_special_offer') }}
                                            </span>
                                        </li>
                                        <li>
                                            <span class="item-custom-index">
                                                {{ translate('3') }}
                                            </span>
                                            <span class="item-custom-text">
                                                {{ translate('you_made_your_earning') }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("layouts.front-end.partials.modal._social-share-modal", ['link'=> route('home').'?referral_code='. $customer_detail['referral_code']])

@endsection
