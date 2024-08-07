@extends('layouts.back-end.app')

@section('title', translate('withdraw_method_list'))
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                    {{translate('withdraw_method_list')}}
                </h2>
                <a href="{{route('admin.vendors.withdraw-method.add')}}" class="btn btn--primary">+ {{translate('add_method')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="p-3">
                        <div class="row gy-1 align-items-center justify-content-between">
                            <div class="col-auto">
                                <h5>
                                {{ translate('methods')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1"> {{ $withdrawalMethods->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-auto">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                               placeholder="{{translate('search_Method_Name')}}" aria-label="Search orders"
                                               value="{{ request('searchValue') }}" required>
                                        <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('method_name')}}</th>
                                <th>{{ translate('method_fields') }}</th>
                                <th class="text-center">{{translate('active_status')}}</th>
                                <th class="text-center">{{translate('default_method')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($withdrawalMethods as $key => $withdrawalMethod)
                                <tr>
                                    <td>{{$withdrawalMethods->firstitem() + $key}}</td>
                                    <td>{{$withdrawalMethod['method_name']}}</td>
                                    <td>
                                        @foreach($withdrawalMethod['method_fields'] as $methodField)
                                            <span class="badge badge-success opacity-75 fz-12 border border-white">
                                                <b>{{translate('name').':'}}</b> {{translate($methodField['input_name'])}} |
                                                <b>{{translate('type').':'}}</b> {{ $methodField['input_type'] }} |
                                                <b>{{translate('placeholder').':'}}</b> {{ $methodField['placeholder'] }} |
                                                <b>{{translate('is_Required').':'}}</b> {{ $methodField['is_required'] ? translate('yes') : translate('no') }}
                                            </span><br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        <form action="{{route('admin.vendors.withdraw-method.status-update')}}" method="post" id="withdrawal-method-status{{$withdrawalMethod['id']}}-form" data-from="withdraw-method-status">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$withdrawalMethod['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                       id="withdrawal-method-status{{$withdrawalMethod['id']}}" name="status"
                                                       value="1" {{ $withdrawalMethod['is_active'] == 1 ? 'checked':'' }}
                                                       data-modal-id = "toggle-status-modal"
                                                       data-toggle-id = "withdrawal-method-status{{$withdrawalMethod['id']}}"
                                                       data-on-image = "wallet-on.png"
                                                       data-off-image = "wallet-off.png"
                                                       data-on-title = "{{translate('want_to_Turn_ON_This_Withdraw_Method').'?'}}"
                                                       data-off-title = "{{translate('want_to_Turn_OFF_This_Withdraw_Method').'?'}}"
                                                       data-on-message = "<p>{{translate('if_you_enable_this_Withdraw_method_will_be_shown_in_the_vendor_app_and_vendor_panel')}}</p>"
                                                       data-off-message = "<p>{{translate('if_you_disable_this_Withdraw_method_will_not_be_shown_in_the_vendor_app_and_vendor_panel')}}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>

                                    </td>
                                    <td>
                                        <form action="{{route('admin.vendors.withdraw-method.default-status')}}" method="post" id="withdrawal-method-default{{$withdrawalMethod['id']}}-form" data-from="default-withdraw-method-status">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$withdrawalMethod['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message" id="withdrawal-method-default{{$withdrawalMethod['id']}}" name="status" value="1" {{ $withdrawalMethod['is_default'] == 1 ? 'checked':'' }}
                                                       data-modal-id = "toggle-status-modal"
                                                       data-toggle-id = "withdrawal-method-default{{$withdrawalMethod['id']}}"
                                                       data-on-image = "wallet-on.png"
                                                       data-off-image = "wallet-off.png"
                                                       data-on-title = "{{translate('want_to_Turn_ON_This_Withdraw_Method').'?'}}"
                                                       data-off-title = "{{translate('want_to_Turn_OFF_This_Withdraw_Method').'?'}}"
                                                       data-on-message = "<p>{{translate('if_you_enable_this_Withdraw_method_will_be_set_as_Default_Withdraw_Method_in_the_vendor_app_and_vendor_panel')}}</p>"
                                                       data-off-message = "<p>{{translate('if_you_disable_this_Withdraw_method_will_be_remove_as_Default_Withdraw_Method_in_the_vendor_app_and_vendor_panel')}}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{route('admin.vendors.withdraw-method.edit',[$withdrawalMethod->id])}}"
                                               class="btn btn-outline--primary btn-sm square-btn">
                                                <i class="tio-edit"></i>
                                            </a>
                                            @if(!$withdrawalMethod->is_default)
                                                <a class="btn btn-outline-danger btn-sm square-btn delete-data" href="javascript:"
                                                   title="{{translate('delete')}}" data-id="delete-{{$withdrawalMethod->id}}">
                                                    <i class="tio-delete"></i>
                                                </a>
                                                <form action="{{route('admin.vendors.withdraw-method.delete',[$withdrawalMethod->id])}}"
                                                      method="post" id="delete-{{$withdrawalMethod->id}}">
                                                    @csrf @method('delete')
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            {{$withdrawalMethods->links()}}
                        </div>
                    </div>
                    @if(count($withdrawalMethods)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_withdraw_method_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
    <span id="get-withdrawal-method-default-text"
          data-success="{{translate('default_method_updated_successfully').'.'}}"
          data-error="{{translate('default_Method_updated_failed').'!!'}}">
    </span>
@endsection
