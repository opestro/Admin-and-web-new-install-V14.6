@php
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app')

@section('title', translate('edit_Role'))

@section('content')
    @php($direction = Session::get('direction'))
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{translate('role_update')}}
            </h2>
        </div>
        <div class="card">
            <div class="card-body">
                <form id="submit-create-role" action="{{route('admin.custom-role.update',[$role['id']])}}" method="post" class="text-start">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="hidden" name="id" value="{{$role['id']}}">
                            <div class="form-group mb-4">
                                <label for="name" class="title-color">{{translate('role_name')}}</label>
                                <input type="text" name="name" value="{{$role['name']}}" class="form-control" id="name"
                                       aria-describedby="emailHelp"
                                       placeholder="{{translate('ex').':'.translate('store')}}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-4 flex-wrap">
                        <label for="module" class="title-color mb-0">{{translate('module_permission').':'}}</label>
                        <div class="form-group d-flex gap-2">
                            <input type="checkbox" id="select-all" class="cursor-pointer">
                            <label class="title-color mb-0 cursor-pointer text-capitalize"
                                   for="select-all">{{translate('select_all')}}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="dashboard"
                                       class="form-check-input module-permission"
                                       id="dashboard" {{in_array('dashboard',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="dashboard">{{translate('dashboard')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input module-permission" name="modules[]"
                                       {{in_array('pos_management',(array)json_decode($role['module_access']))?'checked':''}} value="pos_management"
                                       id="pos_management">
                                <label class="title-color mb-0"
                                       style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="pos_management">{{translate('pos_management')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="order_management"
                                       class="form-check-input module-permission"
                                       id="order" {{in_array('order_management',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label text-capitalize"
                                       style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="order">{{translate('order_management')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="product_management"
                                       class="form-check-input module-permission"
                                       id="product" {{in_array('product_management',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label text-capitalize"
                                       style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="product">{{translate('product_management')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="promotion_management"
                                       class="form-check-input module-permission"
                                       id="promotion_management" {{in_array('promotion_management',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label text-capitalize"
                                       style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="promotion_management">{{translate('promotion_management')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="support_section"
                                       class="form-check-input module-permission"
                                       id="support_section" {{in_array('support_section',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label text-capitalize"
                                       style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="support_section">{{translate('help_&_support_Section')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="report"
                                       class="form-check-input module-permission"
                                       id="report" {{in_array('report',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="report">{{translate('reports_and_analytics')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="user_section"
                                       class="form-check-input module-permission"
                                       id="user_section" {{in_array('user_section',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="user_section">{{translate('user_management')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group form-check">
                                <input type="checkbox" name="modules[]" value="system_settings"
                                       class="form-check-input module-permission"
                                       id="system_settings" {{in_array('system_settings',(array)json_decode($role['module_access']))?'checked':''}}>
                                <label class="form-check-label"
                                       style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                       for="system_settings">{{translate('system_Settings')}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/custom-role.js') }}"></script>
@endpush
