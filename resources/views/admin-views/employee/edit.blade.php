@extends('layouts.back-end.app')

@section('title', translate('employee_Edit'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/css/intlTelInput.css') }}">
@endpush
@section('content')
<div class="content container-fluid">
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-employee.png')}}" alt="">
            {{translate('employee_update')}}
        </h2>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{route('admin.employee.update',[$employee['id']])}}" method="post" enctype="multipart/form-data"
                  class="text-start">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-0 page-header-title d-flex text-capitalize align-items-center gap-2 border-bottom pb-3 mb-3">
                            <i class="tio-user"></i>
                            {{translate('general_information')}}
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="id" value="{{$employee['id']}}">
                                <div class="form-group">
                                    <label for="name"
                                        class="title-color">{{translate('full_Name')}}</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="{{translate('ex')}} : John Doe"
                                        value="{{$employee['name']}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="title-color">{{translate('phone')}}</label>
                                    <div class="mb-3">
                                        <input class="form-control form-control-user phone-input-with-country-picker"
                                               type="tel" id="exampleInputPhone" value="{{$employee['phone'] ?? old('phone')}}"
                                               placeholder="{{ translate('enter_phone_number') }}" required>
                                        <div class="">
                                            <input type="text" class="country-picker-phone-number w-50" value="{{$employee['phone'] ?? old('phone')}}" name="phone" hidden  readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="role_id" class="title-color">{{translate('role')}}</label>
                                    <select class="form-control" name="role_id" id="role_id">
                                        <option value="0" selected disabled>{{'---'.translate('select').'---'}}</option>
                                        @foreach($adminRoles as $adminRole)
                                            <option value="{{$adminRole->id}}" {{$adminRole['id']==$employee['admin_role_id']?'selected':''}}>{{ ucfirst($adminRole->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="identify_type" class="title-color">{{translate('identify_type')}}</label>
                                    <select class="form-control" name="identify_type" id="identify_type">
                                        <option value="" selected disabled>{{translate('select_identify_type')}} </option>
                                        <option value="nid" {{$employee->identify_type == 'nid' ?'selected' : ''}}>{{translate('NID')}}</option>
                                        <option value="passport" {{$employee->identify_type == 'passport' ?'selected' : ''}}>{{translate('passport')}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="identify_number" class="title-color">{{translate('identify_number')}}</label>
                                    <input type="number" name="identify_number" value="{{$employee->identify_number}}" class="form-control"
                                        placeholder="{{translate('ex').':'.'9876123123'}}" id="identify_number">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="text-center mb-3">
                                        <img class="upload-img-view" id="viewer"
                                             src="{{ getValidImage(path: 'storage/app/public/admin/'.$employee['image'] , type: 'backend-profile') }}"
                                            alt=""/>
                                    </div>
                                    <label for="customFileUpload" class="title-color">{{translate('employee_image')}}</label>
                                    <span class="text-info">( {{translate('ratio').'1:1'}})</span>
                                    <div class="form-group">
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="custom-file-upload" class="custom-file-input image-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" data-image-id="viewer">
                                            <label class="custom-file-label" for="custom-file-upload">{{translate('choose_file')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="title-color" for="exampleFormControlInput1">{{translate('identity_image')}}</label>
                                    <div>
                                        <div class="row select-multiple-image">
                                            @if ($employee['identify_image'])
                                                @foreach(json_decode($employee['identify_image'],true) as $img)
                                                    <div class="col-md-4 mb-3">
                                                        <img height="150" alt=""
                                                             src="{{ getValidImage(path: 'storage/app/public/admin/'.$img, type: 'backend-basic') }}">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                            <i class="tio-user"></i>
                            {{translate('account_information')}}
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email" class="title-color">{{translate('email')}}</label>
                                    <input type="email" name="email" value="{{$employee['email']}}" class="form-control"
                                        id="email" placeholder="{{translate('ex').':'.'ex@gmail.com'}}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password" class="title-color d-flex align-items-center">{{translate('password')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{translate('The_password_must_be_at_least_8_characters_long_and_contain_at_least_one_uppercase_letter').','.translate('_one_lowercase_letter').','.translate('_one_digit_').','.translate('_one_special_character').','.translate('_and_no_spaces').'.'}}">
                                            <img alt="" width="16" src={{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }} alt="" class="m-1">
                                        </span>
                                    </label>
                                    <input type="text" name="password" class="form-control password-check" id="password" placeholder="{{translate('password')}}">
                                    <span class="text-danger mx-1 password-error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="confirm_password"
                                        class="title-color">{{translate('confirm_password')}}</label>
                                    <input type="text" name="confirm_password" class="form-control"
                                        id="confirm_password"
                                        placeholder="{{translate('confirm_password')}}">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-3">
                            <button type="submit" class="btn btn--primary px-4">{{translate('update')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<span id="get-multiple-image-data"
      data-image="{{dynamicAsset(path: "public/assets/back-end/img/400x400/img2.jpg")}}"
      data-width=""
      data-group-class="col-6 col-lg-4"
      data-row-height="auto"
      data-max-count="5"
      data-field="identity_image[]">
</span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/select-multiple-image.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/country-picker-init.js') }}"></script>
@endpush
