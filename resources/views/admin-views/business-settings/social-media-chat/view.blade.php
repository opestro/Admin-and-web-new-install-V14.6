@extends('layouts.back-end.app')

@section('title', translate('social_Media_Chatting'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('3rd_party')}}
            </h2>
        </div>
        @include('admin-views.business-settings.third-party-inline-menu')
        @php($whatsapp = getWebConfig('whatsapp'))
        <div class="card overflow-hidden">
            <form action="{{route('admin.social-media-chat.update',['whatsapp'])}}" method="post">
                @csrf
                <div class="card-header mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/whatsapp.png')}}" alt="">
                        <h4 class="text-center mb-0">{{translate('whatsApp')}}</h4>
                    </div>
                    <label class="switcher">
                        <input class="switcher_input toggle-switch-message" type="checkbox" value="1"
                               id="whatsapp-id" name="status" {{$whatsapp['status']==1?'checked':''}}
                               data-modal-id = "toggle-modal"
                               data-toggle-id = "whatsapp-id"
                               data-on-image = "social/whatsapp-on.png"
                               data-off-image = "social/whatsapp-off.png"
                               data-on-title = "{{translate('want_to_turn_ON_WhatsApp_as_social_media_chat_option').'?'}}"
                               data-off-title = "{{translate('want_to_turn_OFF_WhatsApp_as_social_media_chat_option').'?'}}"
                               data-on-message = "<p>{{translate('if_enabled,WhatsApp_chatting_option_will_be_available_in_the_system')}}</p>"
                               data-off-message = "<p>{{translate('if_enabled,WhatsApp_chatting_option_will_be_hidden_from_the_system')}}</p>">
                        <span class="switcher_control"></span>
                    </label>
                </div>
                <div class="card-body text-start">
                    @if($whatsapp)
                        <div class="form-group">
                            <label class="title-color font-weight-bold text-capitalize">{{translate('whatsapp_number')}}</label>
                            <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{translate('provide_a_WhatsApp_number_without_country_code')}}">
                                <img class="info-img" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="img">
                            </span>
                            <input type="text" class="form-control form-ellipsis" name="phone" value="{{ $whatsapp['phone'] }}" placeholder="{{translate('ex').':'.'1234567890'}}">
                        </div>
                        <div class="d-flex justify-content-end flex-wrap gap-3">
                            <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-5">{{translate('save')}}</button>
                        </div>
                    @else
                        <div class="mt-3 d-flex flex-wrap justify-content-center gap-10">
                            <button type="submit" class="btn btn--primary px-5 text-uppercase">{{translate('configure')}}</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
