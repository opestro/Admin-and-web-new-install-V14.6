@extends('layouts.back-end.app')

@section('title', translate('employee_list'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/employee.png')}}" width="20" alt="">
                {{translate('employee_list')}}
            </h2>
        </div>
        <div class="card">
            <div class="card-header flex-wrap gap-10">
                <div class="px-sm-3 py-4 flex-grow-1">
                    <div class="d-flex justify-content-between gap-3 flex-wrap align-items-center">
                        <div class="">
                            <h5 class="mb-0 text-capitalize gap-2">
                                {{translate('employee_table')}}
                                <span class="badge badge-soft-dark radius-50 fz-12">{{$employees->total()}}</span>
                            </h5>
                        </div>
                        <div class="align-items-center d-flex gap-3 justify-content-lg-end flex-wrap flex-lg-nowrap flex-grow-1">
                            <div class="">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input type="search" name="searchValue" class="form-control"
                                                placeholder="{{translate('search_by_name_or_email_or_phone')}}"
                                                value="{{ request('searchValue') }}" required>
                                        <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="d-flex gap-2 align-items-center text-left">
                                        <div class="">
                                            <select class="form-control text-ellipsis min-w-200" name="admin_role_id">
                                                <option value="all" {{ request('employee_role') == 'all' ? 'selected' : '' }}>{{translate('all')}}</option>
                                                @foreach($employee_roles as $employee_role)
                                                    <option value="{{ $employee_role['id'] }}" {{ request('admin_role_id') == $employee_role['id'] ? 'selected' : '' }}>
                                                            {{ ucfirst($employee_role['name']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="">
                                            <button type="submit" class="btn btn--primary px-4 w-100 text-nowrap">
                                                {{ translate('filter')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="">
                                <button type="button" class="btn btn-outline--primary text-nowrap" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a class="dropdown-item" href="{{route('admin.employee.export',['role'=>request('admin_role_id'),'searchValue'=>request('searchValue')])}}">
                                            <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                            {{translate('excel')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="">
                                <a href="{{route('admin.employee.add-new')}}" class="btn btn--primary text-nowrap">
                                    <i class="tio-add"></i>
                                    <span class="text ">{{translate('add_new')}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize table-nowrap">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('name')}}</th>
                            <th>{{translate('email')}}</th>
                            <th>{{translate('phone')}}</th>
                            <th>{{translate('role')}}</th>
                            <th>{{translate('status')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $key => $employee)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="text-capitalize">
                                    <div class="media align-items-center gap-10">
                                        <img class="rounded-circle avatar avatar-lg" alt=""
                                             src="{{getValidImage(path: 'storage/app/public/admin/'.$employee['image'],type:'backend-profile')}}">
                                        <div class="media-body">
                                            {{$employee['name']}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{$employee['email']}}
                                </td>
                                <td>{{$employee['phone']}}</td>
                                <td>{{$employee?->role['name'] ?? translate('role_not_found')}}</td>
                                <td>
                                    @if($employee['id'] == 1)
                                        <label class="badge badge-primary-light">{{ translate('admin') }}</label>
                                    @else
                                        <form action="{{route('admin.employee.status')}}" method="post" id="employee-id-{{$employee['id']}}-form" class="employee_id_form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$employee['id']}}">
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input toggle-switch-message" value="1" id="employee-id-{{$employee['id']}}" name="status"
                                                       {{$employee->status?'checked':''}}
                                                       data-modal-id = "toggle-status-modal"
                                                       data-toggle-id = "employee-id-{{$employee['id']}}"
                                                       data-on-image = "employee-on.png"
                                                       data-off-image = "employee-off.png"
                                                       data-on-title = "{{translate('want_to_Turn_ON_Employee_Status').'?'}}"
                                                       data-off-title = "{{translate('want_to_Turn_OFF_Employee_Status').'?'}}"
                                                       data-on-message = "<p>{{translate('if_enabled_this_employee_can_log_in_to_the_system_and_perform_his_role')}}</p>"
                                                       data-off-message = "<p>{{translate('if_disabled_this_employee_can_not_log_in_to_the_system_and_perform_his_role')}}</p>">`)">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($employee['id'] == 1)
                                        <label class="badge badge-primary-light">{{ translate('default') }}</label>
                                    @else
                                        <div class="d-flex gap-10 justify-content-center">
                                            <a href="{{route('admin.employee.update',[$employee['id']])}}"
                                               class="btn btn-outline--primary btn-sm square-btn"
                                               title="{{translate('edit')}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-info btn-sm square-btn" title="View" href="{{route('admin.employee.view',['id'=>$employee['id']])}}">
                                                <i class="tio-invisible"></i>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        {{$employees->links()}}
                    </div>
                </div>
                @if(count($employees)==0)
                    <div class="w-100">
                        @include('layouts.back-end._empty-state',['text'=>'no_employee_found'],['image'=>'default'])
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
