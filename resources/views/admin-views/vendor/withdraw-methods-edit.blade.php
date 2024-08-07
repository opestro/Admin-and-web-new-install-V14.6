@extends('layouts.back-end.app')

@section('title', translate('withdrawal_Methods'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="page-title text-capitalize">
                    <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                    {{translate('withdrawal_methods')}}
                </h2>
                <button class="btn btn--primary text-capitalize" id="add-more-field">
                    <i class="tio-add"></i> {{translate('add_fields')}}
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('admin.vendors.withdraw-method.update')}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$withdrawalMethod['id']}}" name="id">
                    <div class=" p-30">
                        <div class="card card-body">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="method_name" id="method_name"
                                       placeholder="{{translate('select_method_name')}}"
                                       value="{{$withdrawalMethod['method_name']}}" required>
                                <label>{{translate('method_name').' '.'*'}}</label>
                            </div>
                        </div>
                        @if($withdrawalMethod['method_fields'][0])
                            @php($field = $withdrawalMethod['method_fields'][0])
                            <div class="card card-body mt-3">
                                <div class="row gy-4 align-items-center">
                                    <div class="col-md-6 col-12">
                                        <div class="">
                                            <select class="form-control" name="field_type[]" required>
                                                <option value="string" {{$field=='string'?'selected':''}}>{{translate('string')}}</option>
                                                <option value="number" {{$field=='number'?'selected':''}}>{{translate('number')}}</option>
                                                <option value="date" {{$field=='date'?'selected':''}}>{{translate('date')}}</option>
                                                <option value="password" {{$field=='password'?'selected':''}}>{{translate('password')}}</option>
                                                <option value="email" {{$field=='email'?'selected':''}}>{{translate('email')}}</option>
                                                <option value="phone" {{$field=='phone'?'selected':''}}>{{translate('phone')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="field_name[]"
                                                   placeholder="{{translate('select_field_name')}}"
                                                   value="{{$field['input_name']??''}}" required>
                                            <label>{{translate('field_name').' '.'*'}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="placeholder_text[]"
                                                   placeholder="{{translate('select_placeholder_text')}}"
                                                   value="{{$field['placeholder']??''}}"
                                                   required>
                                            <label>{{translate('placeholder_text').' '.'*'}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                   name="is_required[0]" id="flex-check-default"
                                                {{$field['is_required'] ? 'checked' : ''}}>
                                            <label class="form-check-label" for="flex-check-default">
                                                {{translate('this_field_required')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div id="custom-field-section" class="mt-3">
                            @foreach($withdrawalMethod['method_fields'] as $key=>$field)
                                @if($key>0)
                                    <div class="card card-body mb-30" id="field-row--{{$key}}">
                                        <div class="row gy-4 align-items-center">
                                            <div class="col-md-6 col-12">
                                                <div>
                                                    <select class="form-control" name="field_type[]" required>
                                                        <option value="string" {{$field['input_type']=='string'?'selected':''}}>{{translate('string')}}</option>
                                                        <option value="number" {{$field['input_type']=='number'?'selected':''}}>{{translate('number')}}</option>
                                                        <option value="date" {{$field['input_type']=='date'?'selected':''}}>{{translate('date')}}</option>
                                                        <option value="password" {{$field['input_type']=='password'?'selected':''}}>{{translate('password')}}</option>
                                                        <option value="email" {{$field['input_type']=='email'?'selected':''}}>{{translate('email')}}</option>
                                                        <option value="phone" {{$field['input_type']=='phone'?'selected':''}}>{{translate('phone')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="field_name[]"
                                                           placeholder="Select field name"
                                                           value="{{$field['input_name']??''}}"
                                                           required>
                                                    <label>{{translate('field_name').' '.'*'}}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="placeholder_text[]"
                                                           placeholder="Select placeholder text"
                                                           value="{{$field['placeholder']??''}}"
                                                           required>
                                                    <label>{{translate('placeholder_text').' '.'*'}}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           name="is_required[{{$key}}]" id="flexCheckDefault__0"
                                                        {{$field['is_required'] ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="flexCheckDefault__0">
                                                        {{translate('this_field_required')}}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <span class="btn btn-danger remove-field" data-key="{{$key}}">
                                                <i class="tio-delete"></i>
                                                    {{translate('remove')}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="is_default" id="flex-check-default-method" {{$withdrawalMethod['is_default'] == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="flex-check-default-method">
                                    {{translate('default_method')}}
                                </label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn--secondary mx-2">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary demo_check">{{translate('submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <span id="get-add-filed-text"
        data-input-filed="{{translate('input_Field_Type')}}"
        data-string="{{translate('string')}}"
        data-number="{{translate('number')}}"
        data-date="{{translate('date')}}"
        data-password="{{translate('password')}}"
        data-email="{{translate('email')}}"
        data-phone="{{translate('phone')}}"
        data-field-name="{{translate('field_name')}}"
        data-placeholder-text="{{translate('placeholder_text')}}"
        data-required="{{translate('this_field_required')}}"
        data-remove="{{translate('remove')}}"
        data-reached-maximum="{{translate('reached_maximum')}}"
        data-confirm="{{translate('ok')}}"
    >
    </span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/withdraw-method.js')}}"></script>
@endpush
