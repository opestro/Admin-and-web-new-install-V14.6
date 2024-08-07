@php
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app')
@section('title', translate('create_Role'))
@section('content')
    @php($direction = Session::get('direction'))
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{translate('employee_role_setup')}}
            </h2>
        </div>
        <div class="card">
            <div class="card-body">
                <form id="submit-create-role" method="post" action="{{route('admin.custom-role.store')}}" class="text-start">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label for="name" class="title-color">{{translate('role_name')}}</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    aria-describedby="emailHelp"
                                    placeholder="{{translate('ex').':'.translate('store')}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-4 flex-wrap">
                        <label for="name" class="title-color font-weight-bold mb-0">{{translate('module_permission')}} </label>
                        <div class="form-group d-flex gap-2">
                            <input type="checkbox" id="select-all" class="cursor-pointer">
                            <label class="title-color mb-0 cursor-pointer text-capitalize" for="select-all">{{translate('select_all')}}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" name="modules[]" value="dashboard" class="module-permission" id="dashboard">
                                <label class="title-color mb-0" style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="dashboard">{{translate('dashboard')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" name="modules[]" value="pos_management" class="module-permission" id="pos_management">
                                <label class="title-color mb-0" style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="pos_management">{{translate('pos_management')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission" name="modules[]" value="order_management" id="order">
                                <label class="title-color mb-0 text-capitalize" style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};" for="order">{{translate('order_management')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission" name="modules[]" value="product_management" id="product">
                                <label class="title-color mb-0 text-capitalize" style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="product">{{translate('product_management')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission" name="modules[]" value="promotion_management" id="promotion_management">
                                <label class="title-color mb-0 text-capitalize" style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="promotion_management">{{translate('promotion_management')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" name="modules[]" class="module-permission" value="support_section" id="support_section">
                                <label class="title-color mb-0 text-capitalize" style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="support_section">{{translate('help_&_support_section')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission" name="modules[]" value="report" id="report">
                                <label class="title-color mb-0 text-capitalize" style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="report">{{translate('reports_&_analytics')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission" name="modules[]" value="user_section" id="user_section">
                                <label class="title-color mb-0 text-capitalize" style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="user_section">{{translate('user_management')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" class="module-permission" name="modules[]" value="system_settings" id="system_settings">
                                <label class="title-color mb-0 text-capitalize" style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="system_settings">{{translate('system_settings')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="px-3 py-4">
                <div class="row justify-content-between align-items-center flex-grow-1">
                    <div class="col-md-4 col-lg-6 mb-2 mb-sm-0">
                        <h5 class="d-flex align-items-center gap-2">
                            {{translate('employee_Roles')}}
                            <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ count($roles) }}</span>
                        </h5>
                    </div>
                    <div class="col-md-8 col-lg-6 d-flex flex-wrap flex-sm-nowrap justify-content-sm-end gap-3">
                        <form action="{{url()->current()}}?search={{ request('searchValue') }}" method="GET">
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="searchValue" class="form-control" placeholder="{{translate('search_role')}}"
                                        value="{{ request('searchValue') }}">
                                <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                            </div>
                        </form>
                        <div class="">
                            <button type="button" class="btn btn-outline--primary text-nowrap" data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{translate('export')}}
                                <i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.custom-role.export',['searchValue'=>request('searchValue')])}}">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                        {{translate('excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-3">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table text-start">
                        <thead class="thead-light thead-50 text-capitalize table-nowrap">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('role_name')}}</th>
                                <th>{{translate('modules')}}</th>
                                <th>{{translate('created_at')}}</th>
                                <th>{{translate('status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $key => $role)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$role['name']}}</td>
                                <td class="text-capitalize">
                                    @if($role['module_access'] != null)
                                        @foreach((array)json_decode($role['module_access']) as $module)
                                            @if($module == 'report')
                                                {{translate('reports_and_analytics').(!$loop->last ? ',': '')}} <br>
                                            @elseif($module == 'user_section')
                                                {{translate('user_management').(!$loop->last ? ',': '')}} <br>
                                            @elseif($module == 'support_section')
                                                {{translate('Help_&_Support_Section').(!$loop->last ? ',': '')}} <br>
                                            @else
                                                {{translate(str_replace('_',' ', $module)).(!$loop->last ? ',': '')}} <br>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{date('d-M-y',strtotime($role['created_at']))}}</td>
                                <td>
                                    <form action="{{route('admin.custom-role.employee-role-status')}}" method="post" id="employee-role-status{{$role['id']}}-form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$role['id']}}">
                                        <label class="switcher" for="employee-role-status{{$role['id']}}">
                                            <input type="checkbox" class="switcher_input toggle-switch-message" id="employee-role-status{{$role['id']}}" name="status" value="1" {{$role['status'] == 1?'checked':''}}
                                                   data-modal-id = "toggle-status-modal"
                                                   data-toggle-id = "employee-role-status{{$role['id']}}"
                                                   data-on-image = "employee-on.png"
                                                   data-off-image = "employee-off.png"
                                                   data-on-title = "{{translate('want_to_Turn_ON_Employee_Status').'?'}}"
                                                   data-off-title = "{{translate('want_to_Turn_OFF_Employee_Status').'?'}}"
                                                   data-on-message = "<p>{{translate('when_the_status_is_enabled_employees_can_access_the_system_to_perform_their_responsibilities')}}</p>"
                                                   data-off-message = "<p>{{translate('when_the_status_is_disabled_employees_cannot_access_the_system_to_perform_their_responsibilities')}}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{route('admin.custom-role.update',[$role['id']])}}"
                                            class="btn btn-outline--primary btn-sm square-btn"
                                            title="{{translate('edit') }}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a href="javascript:"
                                            class="btn btn-outline-danger btn-sm delete-data-without-form"
                                           data-action="{{route('admin.custom-role.delete')}}"
                                            title="{{translate('delete') }}" data-id="{{$role['id']}}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if(count($roles)==0)
                @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/custom-role.js')}}"></script>
@endpush
