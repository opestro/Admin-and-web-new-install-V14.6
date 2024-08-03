@php
    use App\Models\BusinessSetting;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app')
@section('title', translate('language'))
@section('content')
    @php($direction = Session::get('direction') === "rtl" ? 'right' : 'left')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/system-setting.png')}}" alt="">
                {{translate('system_setup')}}
            </h2>
        </div>
        @include('admin-views.business-settings.system-settings-inline-menu')
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger mb-3" role="alert">
                    {{translate('changing_some_settings_will_take_time_to_show_effect_please_clear_session_or_wait_for_60_minutes_else_browse_from_incognito_mode')}}
                </div>
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <span class="title-color text-capitalize font-weight-bold">
                                    {{translate('language_table')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('after_adding_a_new_language,_you_need_to_translate_the_key_contents_for_users_to_experience_this_feature').' . '.translate('to_translate_a_language_click_the_action_button_from_the_language_table_&_click_translate').'.'.translate('then_change_the_key_language_value_manually_or_click_the_‘Auto_Translate’_button').'.'.translate('Finally,_click_‘Update’_to_save_the_changes').'.'}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <div class="d-flex gap-10 justify-content-sm-end">
                                    <button class="btn btn--primary btn-icon-split" data-toggle="modal"
                                            data-target="#lang-modal">
                                        <i class="tio-add"></i>
                                        <span class="text">{{translate('add_new_language')}}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive pb-3">
                        <table
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            style="text-align: {{$direction}};">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('SL')}}</th>
                                <th>{{translate('ID')}}</th>
                                <th>{{translate('name')}}</th>
                                <th>{{translate('code')}}</th>
                                <th class="text-center">{{translate('status')}}</th>
                                <th class="text-center">{{translate('default_status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($language=BusinessSetting::where('type','language')->first())
                            @foreach(json_decode($language['value'],true) as $key =>$data)
                                <tr>
                                    <td>{{$key++}}</td>
                                    <td>{{$data['id']}}</td>
                                    <td>{{$data['name']}} ({{$data['direction']??'ltr'}})
                                    </td>
                                    <td>{{$data['code']}}</td>
                                    <td>
                                        @if (array_key_exists('default', $data) && $data['default'])
                                            <label class="switcher mx-auto" id="default-language-status-alert"
                                                   data-text="{{translate('default_language_can_not_be_deactive').'!'}}">
                                                <input type="checkbox" class="switcher_input" checked disabled>
                                                <span class="switcher_control"></span>
                                            </label>
                                        @else
                                            <form action="{{ route('admin.business-settings.language.update-status') }}"
                                                  method="post" id="language-id-{{$data['id']}}-form">
                                                @csrf
                                                <input type="hidden" name="code" value="{{$data['code']}}">
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" class="switcher_input toggle-switch-message"
                                                           {{$data['status']==1?'checked':''}}
                                                           id="language-id-{{$data['id']}}" name="status"
                                                           data-modal-id="toggle-status-modal"
                                                           data-toggle-id="language-id-{{$data['id']}}"
                                                           data-on-image="language-on.png"
                                                           data-off-image="language-off.png"
                                                           data-on-title="{{translate('want_to_Turn_ON_Language_Status').'?'}}"
                                                           data-off-title="{{translate('want_to_Turn_OFF_Language_Status').'?'}}"
                                                           data-on-message="<p>{{translate('if_enabled_this_language_will_be_available_throughout_the_entire_system')}}</p>"
                                                           data-off-message="<p>{{translate('if_disabled_this_language_will_be_hidden_from_the_entire_system')}}</p>">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        @if (array_key_exists('default', $data) && $data['default']===true)
                                            <label class="switcher mx-auto" id="default-language-status-alert">
                                                <input type="checkbox" class="switcher_input" checked disabled>
                                                <span class="switcher_control"></span>
                                            </label>
                                        @elseif(array_key_exists('default', $data) && $data['default']===false)
                                            <form
                                                action="{{route('admin.business-settings.language.update-default-status', ['code'=>$data['code']])}}"
                                                method="get" id="language-default-id-{{$data['id']}}-form" data-from="default-language">
                                                @csrf
                                                <input type="hidden" name="code" value="{{$data['code']}}">
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" class="switcher_input toggle-switch-message"
                                                           id="language-default-id-{{$data['id']}}" name="default"
                                                           data-modal-id="toggle-status-modal"
                                                           data-toggle-id="language-default-id-{{$data['id']}}"
                                                           data-on-image="language-on.png"
                                                           data-off-image="language-off.png"
                                                           data-on-title="{{translate('want_to_Change_Default_Language_Status').'?'}}"
                                                           data-off-title="{{translate('want_to_Change_Default_Language_Status').'?'}}"
                                                           data-on-message="<p>{{translate('if_enabled_this_language_will_be_set_as_default_for_the_entire_system')}}</p>"
                                                           data-off-message="<p>{{translate('if_disabled_this_language_will_be_unset_as_default_for_the_entire_system')}}</p>">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-seconary btn-sm dropdown-toggle"
                                                    type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @if($data['code']!='en')
                                                    <a class="dropdown-item" data-toggle="modal"
                                                       data-target="#lang-modal-update-{{$data['code']}}">{{translate('update')}}</a>
                                                    @if ($data['default'] === true)
                                                        <a class="dropdown-item default-language-delete-alert"
                                                           href="javascript:"
                                                           data-text="{{translate('default_language_can_not_be_deleted').'!'.translate('to_delete_change_the_default_language_first').'!' }}"
                                                           >{{translate('delete')}}</a>
                                                    @else
                                                        <a class="dropdown-item delete"
                                                           id="{{route('admin.business-settings.language.delete',[$data['code']])}}">{{translate('delete')}}</a>

                                                    @endif
                                                @endif
                                                <a class="dropdown-item"
                                                   href="{{route('admin.business-settings.language.translate',[$data['code']])}}">{{translate('translate')}}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="lang-modal" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{translate('new_language')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('admin.business-settings.language.add-new')}}" method="post"
                          style="text-align: {{$direction}};">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="recipient-name"
                                               class="col-form-label">{{translate('language')}} </label>
                                        <input type="text" class="form-control" id="recipient-name" name="name" placeholder="{{translate('language_name')}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="message-text"
                                               class="col-form-label">{{translate('country_code')}}</label>
                                        <select class="form-control select-country w-100" name="code">
                                            @foreach(File::files(base_path('public/assets/front-end/img/flags')) as $path)
                                                @if(pathinfo($path)['filename'] !='en')
                                                    <option value="{{ pathinfo($path)['filename'] }}"
                                                            title="{{ dynamicAsset(path: 'public/assets/front-end/img/flags/'.pathinfo($path)['filename'].'.png') }}">
                                                        {{ strtoupper(pathinfo($path)['filename']) }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">{{translate('direction').':'}}</label>
                                        <select class="form-control" name="direction">
                                            <option value="ltr">{{translate('LTR')}}</option>
                                            <option value="rtl">{{translate('RTL')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{translate('close')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('add')}}
                                <i class="fa fa-plus"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach(json_decode($language['value'],true) as $key =>$data)
            <div class="modal fade" id="lang-modal-update-{{$data['code']}}" tabindex="-1" role="dialog"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{translate('new_language')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('admin.business-settings.language.update')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="recipient-name"
                                                   class="col-form-label">{{translate('language')}} </label>
                                            <input type="text" class="form-control" value="{{$data['name']}}"
                                                   name="name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="message-text"
                                                   class="col-form-label">{{translate('country_code')}}</label>
                                            <select class="form-control select-country w-100" name="code">
                                                @foreach(File::files(base_path('public/assets/front-end/img/flags')) as $path)
                                                    @if(pathinfo($path)['filename'] !='en' && $data['code']==pathinfo($path)['filename'])
                                                        <option value="{{ pathinfo($path)['filename'] }}"
                                                                title="{{ dynamicAsset(path: 'public/assets/front-end/img/flags/'.pathinfo($path)['filename'].'.png') }}">
                                                            {{ strtoupper(pathinfo($path)['filename']) }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">{{translate('direction')}} :</label>
                                            <select class="form-control" name="direction">
                                                <option
                                                    value="ltr" {{isset($data['direction'])?$data['direction']=='ltr'?'selected':'':''}}>
                                                    {{translate('LTR')}}
                                                </option>
                                                <option
                                                    value="rtl" {{isset($data['direction'])?$data['direction']=='rtl'?'selected':'':''}}>
                                                    {{translate('RTL')}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('update')}} <i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/language.js')}}"></script>
@endpush
