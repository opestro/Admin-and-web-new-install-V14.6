@extends('layouts.back-end.app')

@section('title', translate('social_Login'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('3rd_party')}}
            </h2>
        </div>
        @include('admin-views.business-settings.third-party-inline-menu')
        <?php
            $socialLoginServices = json_decode($data['value'], true);
            $appleLoginServices = $apple ? json_decode($apple['value'], true) : [];
        ?>
        <div class="row gy-3">
            @if (isset($socialLoginServices))
                @foreach ($socialLoginServices as $socialLoginService)
                    <div class="col-lg-6">
                        <div class="card overflow-hidden">
                            <form action="{{route('admin.social-login.update',[$socialLoginService['login_medium']])}}" method="post">
                                @csrf
                                <div class="card-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img')}}/{{$socialLoginService['login_medium']}}{{('-logo.png')}}" alt="">
                                        <h4 class="mb-0">{{translate($socialLoginService['login_medium'])}} {{translate('login')}}</h4>
                                    </div>

                                    <label class="switcher">
                                        <input class="switcher_input toggle-switch-message" type="checkbox" name="status"
                                               id="{{$socialLoginService['login_medium']}}-id" value="1" {{isset($socialLoginService['status']) && $socialLoginService['status'] == 1 ?'checked' : ''}}
                                               data-modal-id = "toggle-modal"
                                               data-toggle-id = "{{$socialLoginService['login_medium']}}-id"
                                               data-on-image = "social/{{$socialLoginService['login_medium']}}-on.png"
                                               data-off-image = "social/{{$socialLoginService['login_medium']}}-off.png"
                                               data-on-title = "{{translate('want_to_turn_ON_'.$socialLoginService['login_medium'].'_login').'?'}}"
                                               data-off-title = "{{translate('want_to_turn_OFF_'.$socialLoginService['login_medium'].'_login').'?'}}"
                                               data-on-message = "<p>{{translate('if_enabled_customers_can_log_in_to_their_account_using_their'.' '.$socialLoginService['login_medium'].' '.'credentials')}}</p>"
                                               data-off-message = "<p>{{translate('if_disabled_customers_cannot_log_in_to_their_account_using_their'.' '.$socialLoginService['login_medium'].' '.'credentials')}}</p>">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-end mb-2">
                                        <button class="btn-link text-capitalize d-flex align-items-center gap-2" type="button" data-toggle="modal" data-target="#{{$socialLoginService['login_medium']}}-modal">
                                            {{translate('credential_setup')}}
                                            <img width="16" class="svg" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" loading="lazy" alt="">
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex mb-2 gap-2 align-items-center">
                                            <label class="title-color font-weight-bold text-capitalize mb-0">{{translate('callback_URI')}}</label>
                                            <span data-toggle="tooltip" data-title="{{translate('add_the_OAuth_authorization_URL')}}">
                                                <img width="16" class="svg" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" loading="lazy" alt="">
                                            </span>
                                        </div>
                                        <div class="form-control d-flex align-items-center justify-content-between py-1 pl-3 pr-2">
                                            <span class="form-ellipsis d-flex" id="id_{{$socialLoginService['login_medium']}}">{{ url('/') }}/customer/auth/login/{{$socialLoginService['login_medium']}}/callback</span>
                                            <span class="btn-link copy-to-clipboard" data-id="#id_{{$socialLoginService['login_medium']}}">
                                                <i class="tio-copy"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex mb-2 gap-2 align-items-center">
                                            <label class="title-color font-weight-bold text-capitalize mb-0">{{translate('store_Client_ID')}}</label>
                                            <span data-toggle="tooltip" data-title="{{translate('add_the_unique_client_ID ')}}">
                                                <img width="16" class="svg" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" loading="lazy" alt="">
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-ellipsis" name="client_id" placeholder="{{translate('ex')}}:{{translate('client_ID')}}"
                                               value="{{env('APP_MODE')!='demo'? $socialLoginService['client_id']??"":''}}">
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex mb-2 gap-2 align-items-center">
                                            <label class="title-color font-weight-bold text-capitalize mb-0">{{translate('store_Client_Secret_Key')}}</label>
                                            <span data-toggle="tooltip" data-title="{{translate('store_Client_Secret_Key')}}">
                                                <img width="16" class="svg" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" loading="lazy" alt="">
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-ellipsis" name="client_secret" placeholder="{{translate('ex')}}:{{translate('client_secret_key')}}"
                                               value="{{env('APP_MODE')!='demo'?$socialLoginService['client_secret']??"":''}}">
                                    </div>
                                    <div class="d-flex justify-content-end flex-wrap gap-3">
                                        <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-5 {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('save')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif

            @if(isset($appleLoginServices))
                @foreach ($appleLoginServices as $appleLoginService)
                    <div class="col-md-6">
                        <div class="card overflow-hidden">
                            <form
                                action="{{route('admin.social-login.update-apple', [$appleLoginService['login_medium']])}}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/apple.png')}}" alt="">
                                        <h4 class="mb-0">{{translate($appleLoginService['login_medium'])}} {{translate('login')}}</h4>
                                    </div>

                                    <label class="switcher">
                                        <input class="switcher_input  toggle-switch-message" type="checkbox"
                                               id="apple-login-id" {{$appleLoginService['status']==1?'checked':''}} value="1" name="status"
                                               data-modal-id = "toggle-modal"
                                               data-toggle-id = "apple-login-id"
                                               data-on-image = "social/apple-on.png"
                                               data-off-image = "social/apple-off.png"
                                               data-on-title = "{{translate('want_to_turn_ON_apple_login').'?'}}"
                                               data-off-title = "{{translate('want_to_turn_OFF_apple_login').'?'}}"
                                               data-on-message = "<p>{{translate('if_enabled_customers_can_log_in_to_their_account_using_their_Apple_credentials')}}</p>"
                                               data-off-message = "<p>{{translate('if_disabled_customers_cannot_log_in_to_their_account_using_their_Apple_credentials')}}</p>">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-end mb-2">
                                        <button class="btn-link text-capitalize d-flex align-items-center gap-2" type="button" data-toggle="modal" data-target="#{{$appleLoginService['login_medium']}}-modal">
                                            {{translate('credential_setup')}}
                                            <img width="16" class="svg" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" loading="lazy" alt="">
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex mb-2 gap-2 align-items-center">
                                            <label class="form-label mb-0 title-color font-weight-bold ">{{translate('client_id')}}</label>
                                            <span data-toggle="tooltip" data-title="{{translate('add_the_unique_client_ID')}}">
                                                <img width="16" class="svg" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" loading="lazy" alt="">
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="client_id" placeholder="{{translate('ex')}}:{{translate('client_ID')}}"
                                               value="{{ $appleLoginService['client_id'] }}">
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex mb-2 gap-2 align-items-center">
                                            <label class="form-label mb-0 title-color font-weight-bold ">{{translate('team_id')}}</label>
                                            <span data-toggle="tooltip" data-title="{{translate('team_ID')}}">
                                                <img width="16" class="svg" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" loading="lazy" alt="">
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="team_id"  placeholder="{{translate('ex').':'.translate('team_id')}}"
                                               value="{{ $appleLoginService['team_id'] }}">
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex mb-2 gap-2 align-items-center">
                                            <label class="form-label mb-0 title-color font-weight-bold ">{{translate('key_id')}}</label>
                                            <span data-toggle="tooltip" data-title="{{translate('add_key_id')}}">
                                                <img width="16" class="svg" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" loading="lazy" alt="">
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="key_id"  placeholder="{{translate('ex').':'.translate('key_ID')}}"
                                               value="{{ $appleLoginService['key_id'] }}">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" name="service_file" class="custom-file-input form-control">
                                            <label class="custom-file-label" for="customFileUpload">{{translate('choose_updated_file')}}</label>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end flex-wrap gap-3">
                                        <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-5 {{env('APP_MODE')!='demo'?'':'call-demo'}}" >{{translate('save')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="modal fade" id="google-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="d-flex gap-3 flex-column align-items-center text-center mb-4">
                            <img width="80" src="{{dynamicAsset(path: 'public/assets/back-end/img/google-logo.png')}}" alt="">
                            <h5 class="modal-title" id="staticBackdropLabel">{{translate('google_API_Set_up_Instructions')}}</h5>
                        </div>
                        <ol class="d-flex flex-column gap-2">
                            <li>{{translate('go_to_the_Google_Developers_Console')}}.</li>
                            <li>{{translate('create_a_new_project_or_select_an_existing_project').'.'}}</li>
                            <li>{{translate('click_on_"Credentials"_in_the_left-hand_menu').'.'}}</li>
                            <li>{{translate('create_an_OAuth_client_ID_for_a_web_application').'.'}}</li>
                            <li>{{translate('enter_a_name_for_your_client ID_and_click_"Create"').'.'}}</li>
                            <li>{{translate('enter_the_URL_of_your_website_as_an_authorized_JavaScript_origin').'.'}}</li>
                            <li>{{translate('enter_the_callback_URL_as_an_authorized_redirect_URL').'.'}}</li>
                            <li>{{translate('copy_and_paste_the_client_ID_and_client_secret_into_your_application`s_settings').'.'}}</li>
                            <li>{{translate('enable_the_Google_login_option_in_your_application`s_settings_and_thoroughly_test_the_integration_before_deploying_it_to_a_live_environment').'.'}}</li>
                        </ol>
                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn--primary text-capitalize px-5 px-sm-10" data-dismiss="modal">{{translate('got_it')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="facebook-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="d-flex gap-3 flex-column align-items-center text-center mb-4">
                            <img width="80" src="{{dynamicAsset(path: 'public/assets/back-end/img/facebook.png')}}" alt="">
                            <h5 class="modal-title text-capitalize" id="staticBackdropLabel">{{translate('facebook_API_set_up_instructions')}}</h5>
                        </div>

                        <ol class="d-flex flex-column gap-2">
                            <li>{{translate('go_to_the_Facebook_Developer_website').'.'}}</li>
                            <li>{{translate('create_a_new_app_or_select_an_existing_app').'.'}}</li>
                            <li>{{translate('click_on_"Add_a_New_App"_or_select_an_existing_app_from_the_dashboard').'.'}}</li>
                            <li>{{translate('fill_in_the_required_details,_such_as_Display_Name,_Contact_Email,_and_App_Purpose').'.'}}</li>
                            <li>{{translate('click_"Create_App"_to_create_your_app').'.'}}</li>
                            <li>{{translate('in_the_left-hand_menu,_click_on "Settings"_and_then_"Basic"_to access_your_app`s_basic_settings').'.'}}</li>
                            <li>{{translate('scroll_down_to_the_"Facebook_Login"_section_and_click_on_"Set_Up"_to_configure_your_Facebook_login_settings').'.'}}</li>
                            <li>{{translate('choose_the_login_behavior,_permissions,_and_other_settings_as_per_your_requirements').'.'}}</li>
                            <li>{{translate('copy_and_paste_the_App_ID_and_App_Secret_into_your_application`s_settings').'.'}}</li>
                            <li>{{translate('enable_the_Facebook_login_option_in_your_application`s_settings_and_thoroughly_test_the_integration_before_deploying_it_to_a_live_environment').'.'}}</li>
                        </ol>
                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn--primary text-capitalize px-5 px-sm-10" data-dismiss="modal">{{translate('got_it')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="apple-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="d-flex gap-3 flex-column align-items-center text-center mb-4">
                            <img width="80" src="{{dynamicAsset(path: 'public/assets/back-end/img/apple.png')}}" alt="">
                        <h5 class="modal-title" id="staticBackdropLabel">{{translate('apple_API_Set_up_Instructions')}}</h5>
                        </div>

                        <ol class="d-flex flex-column gap-2">
                            <li>{{translate('go_to_apple_developer_page')}} (<a href="https://developer.apple.com/account/resources/identifiers/list" target="_blank">{{translate('click_here')}}</a>)</li>
                            <li>{{translate('here_in_top__left_corner_you_can_see_the')}} <b>{{ translate('team_ID') }}</b> [{{ translate('apple_developer_account_name')}}]{{'-'.' '. translate('team_ID') }}</li>
                            <li>{{translate('click_plus_icon')}} -> {{translate('select_app_IDs')}} -> {{translate('click_on_continue')}}</li>
                            <li>{{translate('put_a_description_and_also_identifier_(identifier that used for app)_and_this_is_the')}} <b>{{ translate('client_ID') }}</b> </li>
                            <li>{{translate('click_continue_and_download_the_file_in_device_named_AuthKey_ID.p8_(store_it_safely_and_it_is_used_for_push_notification)')}} </li>
                            <li>{{translate('again_click_plus_icon')}} -> {{translate('select_service_IDs').' '.'->'.' '.translate('click_on_continue')}}</li>
                            <li>{{translate('push_a_description_and_also_identifier_and_continue')}} </li>
                            <li>{{translate('download_the_file_in_device_named')}} <b>{{ translate('AuthKey_KeyID.p8') }}</b> [{{translate('this_is_the_service_key_ID_file_and_also_after_AuthKey_that_is_the_key_ID')}}]</li>
                        </ol>
                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn--primary text-capitalize px-5 px-sm-10" data-dismiss="modal">{{translate('got_it')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="twitter-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{translate('twitter_API_Set_up_Instructions')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"><b></b>
                        {{translate('instruction_will_be_available_very_soon')}}
                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn--primary text-capitalize px-5 px-sm-10" data-dismiss="modal">{{translate('got_it')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
