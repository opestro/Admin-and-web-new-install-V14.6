@extends('theme-views.layouts.app')
@section('title', translate('refer_&_Earn').' | '.$web_config['name']->value.' '.translate('ecommerce'))
@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5 class="text-capitalize">{{translate('refer_&_earn')}}</h5>
                            </div>
                            <div class="mt-4">
                                <div class="refer_and_earn_section">

                                    <div class="d-flex justify-content-center align-items-center py-2 mb-3">
                                        <div class="banner-img">
                                            <img class="img-fluid" alt="" width="300"
                                                 src="{{ theme_asset('assets/img/icons/refer-and-earn.png') }}">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="primary-heading mb-2">{{ translate('invite_your_friends_&_businesses') }}</h5>
                                        <p class="secondary-heading">{{ translate('copy_your_code_and_share_your_friends') }}</p>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="col-md-10">
                                            <div class="d-m-flex align-items-center gap-3">
                                                <div class="refer_code_box flex-grow-1">
                                                    <div class="refer_code click-to-copy-code"
                                                         data-copy-code="{{$customer_detail->referral_code}}">{{ $customer_detail->referral_code }}</div>
                                                    <span class="click-to-copy-code" data-copy-code="{{$customer_detail->referral_code}}">
                                                        <img class="w-100" alt=""
                                                             src="{{ theme_asset('assets/img/icons/solar_copy-bold-duotone.png') }}">
                                                    </span>
                                                </div>

                                                <h4 class="share-icons-heading mt-3 text-capitalize">{{ translate('share_via') }}</h4>
                                                <div class="d-flex justify-content-center align-items-center share-on-social">
                                                    @php
                                                        $text = "Greetings,".getWebConfig('company_name').' '."is the best e-commerce platform in the country.If you are new to this website dont forget to use " . $customer_detail->referral_code . " " ."as the referral code while sign up into ".' '.getWebConfig('company_name').'.';
                                                        $link = url('/');
                                                    @endphp
                                                    <a href="https://api.whatsapp.com/send?text={{$text}}.{{$link}}" target="_blank">
                                                        <img src="{{ theme_asset('assets/img/icons/whatsapp.png') }}" alt="">
                                                    </a>
                                                    <a href="mailto:recipient@example.com?subject=Referral%20Code%20Text&body={{$text}}%20Link:%20{{$link}}" target="_blank">
                                                        <img src="{{ theme_asset('assets/img/icons/gmail.png') }}" alt="">
                                                    </a>
                                                    <a href="javascript:"
                                                       class="click-to-copy-code" data-copy-code="{{route('home')}}?referral_code={{ $customer_detail->referral_code }}">
                                                        <img src="{{ theme_asset('assets/img/icons/share.png') }}" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="information-section col-md-10">
                                            <h4 class="text-bold d-flex align-items-center gap-1"> <span class="custom-info-icon">i</span> {{ translate('how_you_it_works').'?'}}</h4>
                                            <ul>
                                                <li>
                                                    <span class="item-custom-index">{{ translate('1') }}</span>
                                                    <span class="item-custom-text">{{ translate('invite_your_friends_&_businesses') }}</span>
                                                </li>
                                                <li>
                                                    <span class="item-custom-index">{{ translate('2') }}</span>
                                                    <span class="item-custom-text">{{ translate('they_register') }} {{ $web_config['name']->value }} {{ translate('with_special_offer') }}</span>
                                                </li>
                                                <li>
                                                    <span class="item-custom-index">{{ translate('3') }}</span>
                                                    <span class="item-custom-text">{{ translate('you_made_your_earning') }}</span>
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
        </div>
    </main>
@endsection
