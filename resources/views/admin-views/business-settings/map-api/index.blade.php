@php use App\Utils\Helpers; @endphp
@extends('layouts.back-end.app')

@section('title', translate('third_party_apis'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('3rd_party')}}
            </h2>
        </div>
        @include('admin-views.business-settings.third-party-inline-menu')

        <div class="card">
            <div class="card-body">
                <form action="{{ env('APP_MODE') != 'demo' ? route('admin.business-settings.map-api') : 'javascript:' }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex align-items-center gap-2 justify-content-between mb-3">
                        <h4 class="text-capitalize mb-0">{{translate('google_map_API_setup')}}</h4>
                        <div class="d-flex align-items-center gap-4">
                            <div class="">
                                <label class="switcher">
                                    <input class="switcher_input toggle-switch-message" type="checkbox" value="1"
                                           id="map-api-status-id" name="status" {{ $mapAPIStatus && $mapAPIStatus['value'] == 1 ? 'checked':''}}
                                           data-modal-id = "toggle-modal"
                                           data-toggle-id = "map-api-status-id"
                                           data-on-image = "location.png"
                                           data-off-image = "location.png"
                                           data-on-title = "{{translate('want_to_turn_ON_map').'?'}}"
                                           data-off-title = "{{translate('want_to_turn_OFF_map').'?'}}"
                                           data-on-message = "<p>{{translate('if_enabled,map_will_be_available_in_the_system')}}</p>"
                                           data-off-message = "<p>{{translate('if_enabled,map_will_be_hidden_from_the_system')}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                            <div class="btn-group">
                                <div class="ripple-animation" data-toggle="dropdown" aria-haspopup="true"
                                     aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                         fill="none" class="svg replaced-svg">
                                        <path
                                            d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </div>

                                <div  class="dropdown-menu dropdown-menu-right bg-aliceblue border border-color-primary-light p-4 dropdown-w-lg">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/note.png')}}" alt="">
                                        <h5 class="text-primary mb-0">{{translate('note')}}</h5>
                                    </div>
                                    <p class="title-color font-weight-medium mb-0">{{ translate('without_configuring_this_section_map_functionality_will_not_work_properly').' '.translate('thus_the_whole_system_will_not_work_as_it_planned')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 mb-4 valley-alert">
                        <img width="16" class="mt-1" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                             alt="">
                        <p class="mb-0">
                            <strong>{{translate('NB').':'}}</strong>
                             {{ translate('client_key_should_have_enable_map_javascript_api_and_you_can_restrict_it_with_http_refer').' '.translate('server_key_should_have_enable_place_api_key_and_you_can_restrict_it_with_ip').' '.translate('you_can_use_same_api_for_both_field_without_any_restrictions').'.'}}
                            </p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('map_api_key').'('.translate('client').')'}} </label>
                                <input type="text" placeholder="{{translate('map_api_key'.'('.translate('client').')')}}"
                                       class="form-control" name="map_api_key"
                                       value="{{env('APP_MODE')!='demo' ? $mapAPIKey ?? '' : ''}}" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('map_api_key')}} ({{translate('server')}}
                                    )</label>
                                <input type="text" placeholder="{{translate('map_api_key')}} ({{translate('server')}})"
                                       class="form-control" name="map_api_key_server"
                                       value="{{env('APP_MODE')!='demo' ? $mapAPIKeyServer ?? '' : ''}}" >
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                class="btn btn--primary px-5 {{env('APP_MODE')!='demo'? '' : 'call-demo'}}">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
