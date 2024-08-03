<div class="modal fade" id="add-customer" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('add_new_customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vendor.customer.add') }}" method="post" id="product_form">
                    @csrf
                    <div class="row pl-2">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('first_name') }} <span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="text" name="f_name" class="form-control" value="{{ old('f_name') }}"
                                       placeholder="{{ translate('first_name') }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('last_name') }}
                                    <span class="input-label-secondary text-danger">*</span></label>
                                <input type="text" name="l_name" class="form-control" value="{{ old('l_name') }}"
                                       placeholder="{{ translate('last_name') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-2">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('email') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                       placeholder="{{ translate('ex').': ex@example.com' }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('phone') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <div class="mb-3">
                                    <input class="form-control form-control-user phone-input-with-country-picker"
                                           type="tel" id="exampleInputPhone" value="{{old('phone')}}"
                                           placeholder="{{ translate('enter_phone_number') }}" required>
                                    <div class="">
                                        <input type="text" class="country-picker-phone-number w-50" value="{{old('phone')}}" name="phone" hidden  readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pl-2">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('country') }}</label>
                                <select name="country" class="form-control js-select2-custom" data-live-search="true">
                                    @foreach($countries as $country)
                                        <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('city') }}</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}"
                                       placeholder="{{ translate('city') }}">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('zip_code') }}</label>
                                @if($zipCodes)
                                    <select name="zip" class="form-control js-select2-custom" data-live-search="true">
                                        @foreach($zipCodes as $code)
                                            <option
                                                value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                        @endforeach
                                    </select>
                                @else
                                <input type="text" name="zip_code" class="form-control"
                                       value="{{ old('zip_code') }}" placeholder="{{ translate('zip_code') }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('address') }}</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}"
                                       placeholder="{{ translate('address') }}">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-end">
                        <button type="submit" id="submit_new_customer"
                                class="btn btn--primary">{{ translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
