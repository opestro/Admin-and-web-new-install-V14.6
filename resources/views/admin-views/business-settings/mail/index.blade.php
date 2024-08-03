@php use App\Utils\Helpers; @endphp
@extends('layouts.back-end.app')
@section('title', translate('mail_Config'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.css')}}"/>
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('3rd_party')}}
            </h2>
        </div>
        @include('admin-views.business-settings.third-party-inline-menu')
        <div class="bg-white rounded-top">
            <div class="card-body pb-0">
                <div class="d-flex flex-wrap justify-content-between gap-3 border-bottom">
                    <nav>
                        <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
                            <a class="nav-link d-flex align-items-center gap-2 active" id="nav-home-tab"
                               data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home"
                               aria-selected="true">
                                <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/mail-config.png')}}" alt="">
                                {{translate('mail_configuration')}}
                            </a>
                            <a class="nav-link d-flex align-items-center gap-2" id="nav-profile-tab" data-toggle="tab"
                               href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                                <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/send-test-mail.png')}}"
                                     alt="">
                                {{translate('send_test_mail')}}
                            </a>
                        </div>
                    </nav>
                    <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder mb-2 text-capitalize">
                        {{translate('how_it_works')}}
                        <div class="ripple-animation" data-toggle="modal" data-target="#getInformationModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                 fill="none" class="svg replaced-svg">
                                <path
                                    d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mt-3">
                            @php($data_smtp=Helpers::get_business_settings('mail_config'))
                            <form action="{{route('admin.business-settings.mail.update')}}" method="post">
                                @csrf
                                @if(isset($data_smtp))
                                    <div class="card-header">
                                        <h5 class="mb-0 d-flex align-items-center gap-2 text-capitalize">
                                            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/smtp.png')}}"
                                                 alt="">
                                            {{translate('smtp_mail_config')}}
                                        </h5>

                                        <label class="switcher">
                                            <input type="checkbox" name="status" value="1"
                                                   id="mail_config" {{$data_smtp['status']==1?'checked':''}} class="switcher_input toggle-switch-message"
                                                   data-modal-id = "toggle-modal"
                                                   data-toggle-id = "mail_config"
                                                   data-on-image = "maintenance_mode-on.png"
                                                   data-off-image = "maintenance_mode-off.png"
                                                   data-on-title = "{{translate('want_to_Turn_ON_the_smtp_mail_config_option').'?'}}"
                                                   data-off-title = "{{translate('want_to_Turn_OFF_the_smtp_mail_config_option').'?'}}"
                                                   data-on-message = "<p>{{translate('enabling_mail_configuration_services_will_allow_the_system_to_send_emails').'.'.translate('please_ensure_that_you_have_correctly_configured_the_SMTP_settings_to_avoid_potential_issues_with_email_delivery')}}</p>"
                                                   data-off-message = "<p>{{translate('disabling_SMTP_mail_configuration_services_stops_email_sending')}}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label
                                                            class="title-color mb-0">{{translate('mailer_name')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_mailer_name')}}"></i>
                                                    </div>
                                                    <input type="text"
                                                           placeholder="{{translate('ex')}}:{{translate('alex')}}"
                                                           class="form-control" name="name"
                                                           value="{{env('APP_MODE')=='demo' ? '' :$data_smtp['name']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label class="title-color mb-0">{{translate('host')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_the_name_of_the_host_of_your_mailing_service')}}"></i>
                                                    </div>
                                                    <input type="text" class="form-control" name="host"
                                                           placeholder="{{translate('ex').':'}}{{translate('smtp.mailtrap.io')}}"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['host']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label class="title-color mb-0">{{translate('driver')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_the_driver_for_your_mailing_service')}}"></i>
                                                    </div>
                                                    <input type="text" class="form-control" name="driver"
                                                           placeholder="{{translate('ex')}}:{{translate('smtp')}}"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['driver']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label class="title-color mb-0">{{translate('port')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_the_port_number_for_your_mailing_service')}}"></i>
                                                    </div>
                                                    <input type="text" class="form-control" name="port"
                                                           placeholder="{{translate('ex')}}:587"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['port']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label
                                                            class="title-color mb-0">{{translate('username')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_the_username_of_your_account')}}"></i>
                                                    </div>
                                                    <input type="text" placeholder="{{translate('ex : yahoo')}}"
                                                           class="form-control"
                                                           name="username"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['username']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label
                                                            class="title-color mb-0">{{translate('email_ID')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_your_email_ID')}}"></i>
                                                    </div>
                                                    <input type="text"
                                                           placeholder="{{translate('ex')}}:{{translate('example@example.com')}}"
                                                           class="form-control" name="email"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['email_id']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label
                                                            class="title-color mb-0">{{translate('encryption')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_the_encryption_type')}}"></i>
                                                    </div>
                                                    <input type="text"
                                                           placeholder="{{translate('ex :')}}:{{translate('tls')}}"
                                                           class="form-control" name="encryption"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['encryption']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="js-form-message form-group">
                                                    <label class="input-label" for="smtpPassword" tabindex="0">
                                                    <span class="d-flex align-items-center gap-2">
                                                      {{translate('password')}}
                                                      <i class="tio-info-outined" data-toggle="tooltip"
                                                         title="{{translate('enter_your_password')}}"></i>
                                                    </span>
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <input type="password" class="js-toggle-password form-control"
                                                               value="{{env('APP_MODE')=='demo'?'':$data_smtp['password']}}"
                                                               name="password" id="smtpPassword"
                                                               placeholder="{{translate('ex')}}:123456"
                                                               data-hs-toggle-password-options='{
                                                                     "target": "#changePassTarget2",
                                                            "defaultClass": "tio-hidden-outlined",
                                                            "showClass": "tio-visible-outlined",
                                                            "classChangeTarget": "#changePassIcon2"
                                                            }'>
                                                        <div id="changePassTarget2" class="input-group-append">
                                                            <a class="input-group-text" href="javascript:">
                                                                <i id="changePassIcon2"
                                                                   class="tio-visible-outlined"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap justify-content-end gap-10">
                                            <button type="reset"
                                                    class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                                    class="btn btn--primary px-5 {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('save')}}</button>
                                            @else
                                                <button type="submit"
                                                        class="btn btn--primary px-5">{{translate('configure')}}</button>
                                            @endif
                                        </div>

                                    </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card mt-3">
                            <form action="{{route('admin.business-settings.mail.update-sendgrid')}}" method="post">
                                @csrf
                                @php($data_sendgrid=Helpers::get_business_settings('mail_config_sendgrid'))
                                @if(isset($data_sendgrid))
                                    <div class="card-header">
                                        <h5 class="mb-0 d-flex align-items-center gap-2 text-capitalize">
                                            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/smtp.png')}}" alt="">
                                            {{translate('sendgrid_mail_config')}}
                                        </h5>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                   id="mail-config-sendgrid"
                                                   value="1" {{$data_sendgrid['status'] == 1 ? 'checked':''}}
                                                   data-modal-id = "toggle-modal"
                                                   data-toggle-id = "mail-config-sendgrid"
                                                   data-on-image = "maintenance_mode-on.png"
                                                   data-off-image = "maintenance_mode-off.png"
                                                   data-on-title = "{{translate('want_to_Turn_ON_the_sendgrid_mail_config_option').'?'}}"
                                                   data-off-title = "{{translate('want_to_Turn_OFF_the_sendgrid_mail_config_option').'?'}}"
                                                   data-on-message = "<p>{{translate('enabling_mail_configuration_services_will_allow_the_system_to_send_emails').'.'.translate('please_ensure_that_you_have_correctly_configured_the_sendgrid_settings_to_avoid_potential_issues_with_email_delivery')}}</p>"
                                                   data-off-message = "<p>{{translate('disabling_sendgrid_mail_configuration_services_stops_email_sending')}}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label
                                                            class="title-color mb-0">{{translate('mailer_name')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_mailer_name')}}"></i>
                                                    </div>
                                                    <input type="text"
                                                           placeholder="{{translate('ex').':'}}{{translate('alex')}}"
                                                           class="form-control" name="name"
                                                           value="{{env('APP_MODE')=='demo' ? '' :$data_sendgrid['name']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label class="title-color mb-0">{{translate('host')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_the_name_of_the_host_of_your_mailing_service')}}"></i>
                                                    </div>
                                                    <input type="text" class="form-control" name="host"
                                                           placeholder="{{translate('ex')}}:{{translate('smtp.mailtrap.io')}}"
                                                           value="{{env('APP_MODE')=='demo' ? '' : $data_sendgrid['host']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label class="title-color mb-0">{{translate('driver')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_the_driver_for_your_mailing_service')}}"></i>
                                                    </div>
                                                    <input type="text" class="form-control" name="driver"
                                                           placeholder="{{translate('ex')}}:{{translate('smtp')}}"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['driver']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label class="title-color mb-0">{{translate('port')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_the_port_number_for_your_mailing_service')}}"></i>
                                                    </div>
                                                    <input type="text" class="form-control" name="port"
                                                           placeholder="{{translate('ex').':'.'587'}}"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['port']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label
                                                            class="title-color mb-0">{{translate('username')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_the_username_of_your_account')}}"></i>
                                                    </div>
                                                    <input type="text" placeholder="{{translate('ex').':'.'yahoo'}}"
                                                           class="form-control" name="username"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['username']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label
                                                            class="title-color mb-0">{{translate('email_ID')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_your_email_ID')}}"></i>
                                                    </div>
                                                    <input type="text"
                                                           placeholder="{{translate('ex').':'}}{{translate('example@example.com')}}"
                                                           class="form-control" name="email"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['email_id']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <label
                                                            class="title-color mb-0">{{translate('encryption')}}</label>
                                                        <i class="tio-info-outined" data-toggle="tooltip"
                                                           title="{{translate('enter_the_encryption_type')}}"></i>
                                                    </div>
                                                    <input type="text"
                                                           placeholder="{{translate('ex').':'}}{{translate('tls')}}"
                                                           class="form-control" name="encryption"
                                                           value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['encryption']}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="js-form-message form-group">
                                                    <label class="input-label" for="sendGridPassword" tabindex="0">
                                                    <span class="d-flex gap-2 align-items-center">
                                                      {{translate('password')}}
                                                      <i class="tio-info-outined" data-toggle="tooltip"
                                                         title="{{translate('enter_your_password')}}"></i>
                                                    </span>
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <input type="password" class="js-toggle-password form-control"
                                                               name="password" id="sendGridPassword"
                                                               placeholder="{{translate('ex')}}:123456"
                                                               value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['password']}}"
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

                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap justify-content-end gap-10">
                                            <button type="reset"
                                                    class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                                    class="btn btn--primary px-5 {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('save')}}</button>
                                            @else
                                                <button type="submit"
                                                        class="btn btn--primary px-5">{{translate('configure')}}</button>
                                            @endif
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="bg-white rounded-bottom overflow-hidden">
                    <div class="bg-white card-body">
                        <form class="" action="javascript:">
                            <div class="row">
                                <div class="col-xl-8 col-lg-10">
                                    <div class="d-flex align-items-end gap-2 gap-sm-3">
                                        <div class="flex-grow-1">
                                            <label class="title-color">{{translate('email')}}</label>
                                            <input type="email" id="test-email" class="form-control"
                                                   placeholder="{{translate('ex').':'.'jhon@email.com'}}">
                                        </div>
                                        <button type="button" class="btn btn--primary px-sm-5" data-toggle="modal"
                                                data-target="#send-mail-confirmation-modal">
                                            <i class="tio-telegram"></i>
                                            {{translate('send_mail')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="getInformationModal" tabindex="-1" aria-labelledby="getInformationModal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                            class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <div class="swiper mySwiper pb-3">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/smtp-server.png')}}" loading="lazy"
                                         alt="">
                                    <h4 class="lh-md mb-3 text-capitalize">{{translate('find_SMTP_server_details')}}</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>
                                            {{translate('contact_your_email_service_provider_or_IT_administrator_to_obtain_the_SMTP_server_details_such_as_hostname_port_username_and_password').'.'}}
                                        </li>
                                        <li>{{translate('note').':'}}
                                             {{translate('if_you`re_not_sure_where_to_find_these_details,_check_the_email_provider`s_documentation_or_support_resources_for_guidance').'.'}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/config-smtp.png')}}" loading="lazy"
                                         alt="">
                                    <h4 class="lh-md mb-3 text-capitalize">{{translate('configure_SMTP_settings')}}</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>{{translate('go_to_the_SMTP_mail_setup_page_in_the_admin_panel').'.'}}</li>
                                        <li>{{translate('enter_the_obtained_SMTP_server_details,_including_the_hostname,_port,_username,_and password').'.'}}</li>
                                        <li>{{translate('choose_the_appropriate_encryption_method').' '.'(e.g., SSL,TLS)'.' '.translate('if_required').'.'}}</li>
                                        <li>{{translate('save_the_settings').'.'}}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/test-smtp.png')}}" loading="lazy"
                                         alt="">
                                    <h4 class="lh-md mb-3 text-capitalize">{{translate('test_SMTP_connection')}}</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>{{translate('click_on_the').'"'.translate('send_test_mail').'"'.translate('button_to_verify_the_SMTP_connection')}}
                                        </li>
                                        <li>{{translate('if_successful,_you_will_see_a_confirmation_message_indicating_that_the_connection_is_working_fine').'.'}} </li>
                                        <li>{{translate('if_not,_double-check_your_SMTP_settings_and_try_again').'.'}}</li>
                                        <li>{{translate('note').':'.translate('if_you`re_unsure_about_the_SMTP_settings,_contact_your_email_service_provider_or_IT_administrator_for_assistance').'.'}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                    <img width="80" class="mb-3"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/enable-mail-config.png')}}"
                                         loading="lazy" alt="">
                                    <h4 class="lh-md mb-3 text-capitalize">{{translate('enable_mail_configuration')}}</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>{{translate('if_the_SMTP_connection_test_is_successful,_you_can_now_enable_the_mail_configuration_services_by_toggling_the_switch_to_"ON"')}}</li>
                                        <li>{{translate('this_will_allow_the_system_to_send_emails_using_the_configured_SMTP_settings').'.'}}</li>
                                    </ul>
                                    <button class="btn btn-primary px-10 mt-3 text-capitalize"
                                            data-dismiss="modal">{{ translate('got_it') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination mb-2"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="send-mail-confirmation-modal" tabindex="-1"
         aria-labelledby="send-mail-confirmation-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close">
                        <i class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0 text-center">
                    <div class="d-flex flex-column align-items-center gap-2">
                        <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/send-mail.png')}}"
                             loading="lazy" alt="">
                        <h4 class="lh-md">{{translate('send_a_test_mail_to_your_email').'?'}}  </h4>
                        <p class="text-muted">{{translate('a_test_mail_will_be_send_to_your_email_to')}}
                            <br> {{translate('confirm_it_works_perfectly').'.'}}</p>
                        <button type="button" id="text-mail-send"
                                class="btn btn--primary px-5 px-sm-10 text-capitalize">{{translate('send_mail')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="get-send-mail-route-text" data-action="{{route('admin.business-settings.mail.send')}}"
          data-error-text="{{translate("email_configuration_error").'!!'}}"
          data-success-text="{{translate("email_configured_perfectly")}}"
          data-info-text="{{translate("email_status_is_not_active").'!'}}"
          data-invalid-text="{{translate("invalid_email_address").'!'}}">
    </span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/mail.js')}}"></script>
@endpush
