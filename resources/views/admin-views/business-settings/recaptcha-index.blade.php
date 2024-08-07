@extends('layouts.back-end.app')

@section('title', translate('reCaptcha_Setup'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('3rd_party')}}
            </h2>
        </div>
        @include('admin-views.business-settings.third-party-inline-menu')
        <div class="row">
            <div class="col-12">
                <div class="card overflow-hidden">
                    <form action="{{ env('APP_MODE') != 'demo' ? route('admin.business-settings.captcha') : 'javascript:' }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="d-flex justify-content-between gap-2 align-items-center mb-3">
                                <div>{{translate('status')}}</div>

                                <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder mb-2 text-capitalize">
                                    {{translate('how_it_works')}}
                                    <div class="ripple-animation" data-toggle="modal" data-target="#getInformationModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" class="svg replaced-svg">
                                            <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z" fill="currentColor"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-bottom overflow-hidden mb-4">
                                <div class="border rounded border-color-c1 px-4 py-3 d-flex justify-content-between">
                                    <h5 class="mb-0 d-flex gap-1 c1">
                                        @if(isset($config))
                                            @php($config = (array)json_decode($config['value']))
                                        @endif
                                        {{translate('turn')}} {{translate(isset($config) && $config['status']==1?'OFF':'ON')}}
                                    </h5>
                                    <div class="position-relative">
                                        <label class="switcher">
                                            <input class="switcher_input toggle-switch-message" type="checkbox" name="status"
                                                   id="recaptcha-id" {{$config['status']==1?'checked':''}} value="1"
                                                   data-modal-id = "toggle-modal"
                                                   data-toggle-id = "recaptcha-id"
                                                   data-on-image = "recaptcha-off.png"
                                                   data-off-image = "recaptcha-off.png"
                                                   data-on-title = "{{translate('important').'!'}}"
                                                   data-off-title = "{{translate('warning').'!'}}"
                                                   data-on-message = "<p>{{translate('reCAPTCHA_is_now_enabled_for_added_security').'.'.translate('users_may_be_prompted_to_complete_a_reCAPTCHA_challenge_to_verify_their_human_identity_and protect_against_spam_and_malicious_activity')}}</p>"
                                                   data-off-message = "<p>{{translate('disabling_reCAPTCHA_may_leave_your_website_vulnerable_to_spam_and_malicious_activity_and_suspects_that_a_user_may_be_a_bot').' '.translate('it_is_highly_recommended_to_keep_reCAPTCHA_enabled_to_ensure_the_security_and_integrity_of_your_website')}}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="title-color font-weight-bold d-flex">{{translate('site_Key')}}</label>
                                        <input type="text" class="form-control" name="site_key"
                                                value="{{env('APP_MODE')!='demo'?$config['site_key']??"":''}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="title-color font-weight-bold d-flex">{{translate('secret_Key')}}</label>
                                        <input type="text" class="form-control" name="secret_key"
                                                value="{{env('APP_MODE')!='demo'?$config['secret_key']??"":''}}">
                                    </div>
                                </div>
                            </div>
                            <h5 class="mt-4 mb-3 d-flex">{{translate('instructions')}}</h5>
                            <ol class="pl-4 instructions-list">
                                <li>
                                    {{translate('to_get_site_key_and_secret_key_Go_to_the_Credentials_page')}}
                                    (<a href="https://www.google.com/recaptcha/admin/create"
                                        target="_blank">{{translate('click_here')}}</a>)
                                </li>
                                <li>{{translate('add_a_label_(Ex:_abc_company)')}}</li>
                                <li>{{translate('select_reCAPTCHA_v2_as_ReCAPTCHA_Type')}}</li>
                                <li>{{translate('select_sub_type').':'.translate('im_not_a_robot_checkbox')}}</li>
                                <li>{{translate('add_Domain_(For_ex:_demo.6amtech.com)')}}</li>
                                <li>{{translate('check_in_Accept_the_reCAPTCHA_Terms_of_Service')}}</li>
                                <li>{{translate('press_Submit')}}</li>
                                <li>{{translate('copy_Site_Key_and_Secret_Key,_Paste_in_the_input_filed_below_and_Save').'.'}}</li>
                            </ol>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-5 {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="getInformationModal" tabindex="-1" aria-labelledby="getInformationModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <div class="swiper mySwiper pb-3">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/smtp-server.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3 text-capitalize">{{translate('find_SMTP_server_details')}}</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                    <li>{{translate('contact_your_email_service_provider_or_IT_administrator_to_obtain_the_SMTP_server_details_such_as_hostname_port_username_and_password').'.'}}</li>
                                        <li>{{translate('note').':'.translate('if_you`re_not_sure_where_to_find_these_details,_check_the_email_provider`s_documentation_or_support_resources_for_guidance').'.'}}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
