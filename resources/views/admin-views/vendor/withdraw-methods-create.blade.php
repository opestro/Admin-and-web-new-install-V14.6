@extends('layouts.back-end.app')

@section('title', translate('Withdrawal_Methods'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="page-title text-capitalize">
                    <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                    {{translate('withdrawal_methods')}}
                </h2>
                <button class="btn btn--primary" id="add-more-field">
                    <i class="tio-add"></i> {{translate('add_Fields')}}
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('admin.vendors.withdraw-method.add')}}" method="POST">
                    @csrf
                    <div class="card card-body">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="method_name" id="method_name"
                                   placeholder="{{translate('select_method_name')}}" value="" required>
                            <label>{{translate('method_name').' '.'*'}}</label>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div id="custom-field-section">
                            <div class="card card-body">
                                <div class="row gy-4 align-items-center">
                                    <div class="col-md-6 col-12">
                                        <select class="form-control js-select" name="field_type[]" required>
                                            <option value="" selected disabled>{{translate('input_Field_Type').' '.'*'}}</option>
                                            <option value="string">{{translate('string')}}</option>
                                            <option value="number">{{translate('number')}}</option>
                                            <option value="date">{{translate('date')}}</option>
                                            <option value="password">{{translate('password')}}</option>
                                            <option value="email">{{translate('email')}}</option>
                                            <option value="phone">{{translate('phone')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="field_name[]"
                                                   placeholder="{{translate('select_field_name')}}" value="" required>
                                            <label>{{translate('field_name').' '.'*'}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="placeholder_text[]"
                                                   placeholder="{{translate('select_placeholder_text')}}" value="" required>
                                            <label>{{translate('placeholder_text').' '.'*'}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" name="is_required[0]" id="flex-check-default--0" checked>
                                            <label class="form-check-label" for="flex-check-default--0">
                                                {{translate('this_field_required')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex my-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="is_default" id="flex-check-default-method">
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
          data-input-filed="{{translate('input_field_type')}}"
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
