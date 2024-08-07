@extends('layouts.back-end.app-seller')

@section('title',translate('add_new_delivery_man'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/deliveryman.png')}}" alt="">
                {{translate('add_new_deliveryman')}}
            </h2>
        </div>
        <form action="{{route('vendor.delivery-man.index')}}" method="post" enctype="multipart/form-data" id="add-delivery-man-form">
            @csrf
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title-color" for="exampleFormControlInput1">{{translate('first_name')}}</label>
                                <input type="text" value="{{old('f_name')}}" name="f_name" class="form-control" placeholder="{{translate('first_name')}}" required>
                            </div>
                            <div class="form-group">
                                <label class="title-color" for="exampleFormControlInput1">{{translate('last_name')}}</label>
                                <input type="text" value="{{old('l_name')}}" name="l_name" class="form-control" placeholder="{{translate('last_name')}}" required>
                            </div>
                            <div class="form-group">
                                <label class="title-color d-flex" for="exampleFormControlInput1">{{translate('phone')}}</label>
                                <div class="input-group mb-3">
                                    <div>
                                        <select class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="country_code" id="colors-selector" required>
                                            @foreach($telephoneCodes as $code)
                                                <option value="{{ $code['code'] }}">{{ $code['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="text" value="{{old('phone')}}" name="phone" class="form-control" placeholder="{{translate('ex')}} : 017********"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title-color" for="exampleFormControlInput1">{{translate('identity_type')}}</label>
                                <select name="identity_type" class="form-control">
                                    <option value="passport">{{translate('passport')}}</option>
                                    <option value="driving_license">{{translate('driving_license')}}</option>
                                    <option value="nid">{{translate('nid')}}</option>
                                    <option value="company_id">{{translate('company_id')}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="title-color" for="exampleFormControlInput1">{{translate('identity_number')}}</label>
                                <input value="{{old('identity_number')}}" type="text" name="identity_number" class="form-control"
                                        placeholder="Ex : DH-23434-LS"
                                        required>
                            </div>
                            <div class="form-group">
                                <label class="title-color d-flex" for="exampleFormControlInput1">{{translate('address')}}</label>
                                <div class="input-group mb-3">
                                    <textarea name="address" class="form-control" id="address" rows="1" placeholder="Address">{{old('address')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="title-color">{{translate('deliveryman_image')}}</label>
                                <span class="text-info">* ( {{translate('ratio')}} 1:1 )</span>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="title-color custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    <label class="custom-file-label title-color" for="customFileEg1">
                                        {{translate('choose_File')}}
                                    </label>
                                </div>
                                <div class="mt-4 text-center">
                                    <img class="upload-img-view" id="viewer" src="{{dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg')}}" alt="{{translate('delivery_man_image')}}"/>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="title-color" for="exampleFormControlInput1">{{translate('identity_image')}}</label>
                                <div>
                                    <div class="row" id="coba"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="title-color">{{translate('email')}}</label>
                                <input type="email" value="{{old('email')}}" name="email" class="form-control" placeholder="{{translate('ex')}} : ex@example.com" autocomplete="off"
                                        required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex align-items-center">{{translate('password')}}
                                    <span class="input-label-secondary cursor-pointer d-flex" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{translate('The_password_must_be_at_least_8_characters_long_and_contain_at_least_one_uppercase_letter').','.translate('_one_lowercase_letter').','.translate('_one_digit_').','.translate('_one_special_character').','.translate('_and_no_spaces').'.'}}">
                                        <img alt="" width="16" src={{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}>
                                    </span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control password-check"
                                           autocomplete="off"
                                           name="password" required id="user_password" minlength="8"
                                           placeholder="{{ translate('password_minimum_8_characters') }}"
                                           data-hs-toggle-password-options='{
                                                         "target": "#changePassTarget",
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": "#changePassIcon"
                                                }'>
                                    <div id="changePassTarget" class="input-group-append">
                                        <a class="input-group-text" href="javascript:">
                                            <i id="changePassIcon" class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                                <span class="text-danger mx-1 password-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="title-color">{{translate('confirm_password')}}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control"
                                           name="confirm_password" required id="confirm_password"
                                           placeholder="{{ translate('confirm_password') }}"
                                           autocomplete="off"
                                           data-hs-toggle-password-options='{
                                                         "target": "#changeConfirmPassTarget",
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": "#changeConfirmPassIcon"
                                                }'>
                                    <div id="changeConfirmPassTarget" class="input-group-append">
                                        <a class="input-group-text" href="javascript:">
                                            <i id="changeConfirmPassIcon" class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="d-none" id="placeholderImg" data-img="{{dynamicAsset(path: 'public/assets/back-end/img/400x400/img3.png')}}"></span>

                    <div class="d-flex gap-3 justify-content-end">
                        <button type="reset" id="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                        <button type="button" class="btn btn--primary form-submit" data-form-id="add-delivery-man-form" data-redirect-route="{{route('vendor.delivery-man.list')}}"
                                data-message="{{translate('want_to_add_this_delivery_man').'?'}}">{{translate('submit')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <span id="coba-image" data-url="{{dynamicAsset(path: "public/assets/back-end/img/400x400/img3.png")}}"></span>
    <span id="extension-error" data-text="{{ translate("please_only_input_png_or_jpg_type_file") }}"></span>
    <span id="size-error" data-text="{{ translate("file_size_too_big") }}"></span>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/deliveryman.js')}}"></script>
@endpush
