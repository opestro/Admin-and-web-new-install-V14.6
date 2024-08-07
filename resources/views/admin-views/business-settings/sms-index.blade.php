@extends('layouts.back-end.app')

@section('title', translate('SMS_Module_Setup'))

@push('css_or_js')

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
        <div class="row gy-3" id="sms-gateway-cards">
            <div class="col-12">
                <div class="mt-2 valley-alert">
                    <img width="16" class="mt-1" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                         alt="">
                    <p class="mb-0">
                        <strong>{{translate('NB').':'}}</strong>
                        {{ translate('Please_re-check_if_you’ve_put_all_the_data_correctly_or_contact_your_SMS_gateway_provider_for_assistance').'.'}}
                    </p>
                </div>
            </div>
            @if($paymentGatewayPublishedStatus)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body d-flex justify-content-around align-items-center">
                            <h4 class="text-danger bg-transparent m-0">
                                <i class="tio-info-outined"></i>
                                {{ translate('your_current_SMS_settings_are_disabled_because_you_have_enabled_sms_gateway_addon_To_visit_your_currently_active_sms_gateway_settings_please_follow_the_link') }}
                            </h4>
                            <span>
                            <a href="{{!empty($paymentUrl) ? $paymentUrl : ''}}" class="btn btn-outline-primary"><i class="tio-settings mr-1"></i>{{translate('settings')}}</a>
                        </span>
                        </div>
                    </div>
                </div>
            @endif
            @foreach($smsGateways as $key => $smsConfig)
                <div class="col-md-6">
                    <div class="card h-100">
                        <form action="{{route('admin.business-settings.addon-sms-set')}}" method="POST"
                              id="{{$smsConfig['key_name']}}-form" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="card-header d-flex flex-wrap align-content-around">
                                <h5>
                                    <span class="text-uppercase">{{ str_replace('_', ' ', $smsConfig['key_name'])}}</span>
                                </h5>

                                <?php
                                    $imgPath = 'sms/'.$smsConfig['key_name'].'.png';
                                ?>
                                <label class="switcher show-status-text">
                                    <input class="switcher_input toggle-switch-message" type="checkbox" name="status" value="1"
                                           id="{{$smsConfig['key_name']}}" {{$smsConfig['is_active']==1?'checked':''}}
                                           data-modal-id = "toggle-status-modal"
                                           data-toggle-id = "{{$smsConfig['key_name']}}"
                                           data-on-image = "{{ $imgPath }}"
                                           data-off-image = "{{ $imgPath }}"
                                           data-on-title = "{{translate('want_to_Turn_ON_').' '.ucwords(str_replace('_',' ',$smsConfig['key_name'])).' '.translate('_as_the_SMS_Gateway').'?'}}"
                                           data-off-title = "{{translate('want_to_Turn_OFF_').' '.ucwords(str_replace('_',' ',$smsConfig['key_name'])).' '.translate('_as_the_SMS_Gateway').'?'}}"
                                           data-on-message = "<p>{{translate('if_enabled_system_can_use_this_SMS_Gateway')}}</p>"
                                           data-off-message = "<p>{{translate('if_disabled_system_cannot_use_this_SMS_Gateway')}}</p>">
                                    <span class="switcher_control" data-ontitle="{{ translate('on') }}" data-offtitle="{{ translate('off') }}"></span>
                                </label>
                            </div>
                            <div class="card-body">
                                <input name="gateway" value="{{$smsConfig['key_name']}}" class="d-none">
                                <input name="mode" value="live" class="d-none">
                                @php($skip=['gateway','mode','status'])
                                @foreach($smsConfig['live_values'] as $keyName => $value)
                                    @if(!in_array($keyName, $skip))
                                        <div class="form-group mb-10px mt-20px">
                                            <label for="exampleFormControlInput1"
                                                   class="form-label">{{ucwords(str_replace('_',' ',$keyName))}}
                                                   <span class="text-danger">*</span>
                                                </label>
                                            <input type="text" class="form-control"
                                                   name="{{$keyName}}"
                                                   placeholder="{{ucwords(str_replace('_',' ',$keyName))}}"
                                                   value="{{env('APP_ENV')=='demo'?'':$value}}">
                                        </div>
                                    @endif
                                @endforeach
                                <div class="text-right mt-20px">
                                    <button type="submit" class="btn btn-primary px-5">{{translate('save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection

@push('script')
    <script>
        'use strict';
        @if($paymentGatewayPublishedStatus == 1)
            let smsGatewayCards = $('#sms-gateway-cards');
            smsGatewayCards.find('input').each(function () {
                $(this).attr('disabled', true);
            });
            smsGatewayCards.find('select').each(function () {
                $(this).attr('disabled', true);
            });
            smsGatewayCards.find('.switcher_input').each(function () {
                $(this).removeAttr('checked', true);
            });
            smsGatewayCards.find('button').each(function () {
                $(this).attr('disabled', true);
            });
        @endif
    </script>
@endpush
