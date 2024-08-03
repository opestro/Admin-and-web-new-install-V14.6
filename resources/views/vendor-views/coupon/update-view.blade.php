@extends('layouts.back-end.app-seller')

@section('title', translate('coupon_Edit'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/coupon_setup.png')}}" class="mb-1 mr-1" alt="">
                {{translate('coupon_update')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('vendor.coupon.update',[$coupon['id']])}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color text-capitalize">{{translate('coupon_type')}}</label>
                                    <select class="form-control" id="coupon_type" name="coupon_type" required>
                                        <option disabled selected>{{translate('select_Coupon_Type')}}</option>
                                        <option value="discount_on_purchase" {{$coupon['coupon_type']=='discount_on_purchase'?'selected':''}}>{{translate('discount_on_purchase')}}</option>
                                        <option value="free_delivery" {{$coupon['coupon_type']=='free_delivery'?'selected':''}}>{{translate('free_Delivery')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color text-capitalize">{{translate('coupon_title')}}</label>
                                    <input type="text" name="title" class="form-control" id="title"
                                           value="{{$coupon['title']}}"
                                           placeholder="{{translate('title')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color text-capitalize">{{translate('coupon_code')}}</label>
                                    <a href="javascript:void(0)" class="float-right"
                                       id="generateCode">{{translate('generate_code')}}</a>
                                    <input type="text" name="code" value="{{$coupon['code']}}"
                                           class="form-control" id="code"
                                           placeholder="{{translate('ex'.':'.'EID100')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group coupon_type">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('customer')}}</label>
                                    <select class="js-example-basic-multiple js-states js-example-responsive form-control"
                                            name="customer_id">
                                        <option disabled selected>{{translate('select_customer')}}</option>
                                        <option value="0" {{$coupon['customer_id']=='0'?'selected':''}}>{{translate('all_customer')}}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{$coupon['customer_id']==$customer->id ? 'selected':''}}>{{ $customer->f_name. ' '. $customer->l_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="exampleFormControlInput1"
                                           class="title-color text-capitalize">{{translate('limit_for_same_user')}}</label>
                                    <input type="number" name="limit" min="0" value="{{ $coupon['limit'] }}"
                                           id="coupon_limit" class="form-control"
                                           placeholder="{{translate('ex')}}: {{translate('10')}}">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery">
                                    <label for="name"
                                           class="title-color text-capitalize">{{translate('discount_type')}}</label>
                                    <select id="discount_type" class="form-control" name="discount_type">
                                        <option value="amount" {{$coupon['discount_type']=='amount'?'selected':''}}>{{translate('amount')}}</option>
                                        <option value="percentage" {{$coupon['discount_type']=='percentage'?'selected':''}}>{{translate('percentage')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery">
                                    <label for="name"
                                           class="title-color text-capitalize">{{translate('discount_Amount')}} <span
                                                id="discount_percent"> (%)</span></label>
                                    <input type="number" min="0" max="1000000" step=".01" name="discount"
                                           class="form-control" id="discount"
                                           value="{{$coupon['discount_type']=='amount' ? currencyConverter(amount: $coupon['discount']) : $coupon['discount']}}"
                                           placeholder="{{translate('ex')}}: 500" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color text-capitalize">{{translate('minimum_purchase')}}</label>
                                    <input type="number" min="0" max="1000000" step=".01" name="min_purchase"
                                           class="form-control" id="minimum purchase"
                                           value="{{currencyConverter(amount:$coupon['min_purchase'])}}"
                                           placeholder="{{translate('minimum purchase')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery" id="max-discount">
                                    <label for="name"
                                           class="title-color text-capitalize">{{translate('maximum_discount')}}</label>
                                    <input type="number" min="0" max="1000000" step=".01" name="max_discount"
                                           class="form-control" id="maximum discount"
                                           value="{{currencyConverter(amount:$coupon['max_discount'])}}"
                                           placeholder="{{translate('maximum discount')}}">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color text-capitalize">{{translate('start_date')}}</label>
                                    <input type="date" name="start_date" class="form-control" id="start_date"
                                           value="{{date('Y-m-d',strtotime($coupon['start_date']))}}"
                                           placeholder="{{translate('start date')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color text-capitalize">{{translate('expire_date')}}</label>
                                    <input type="date" name="expire_date" class="form-control" id="expire_date"
                                           value="{{date('Y-m-d',strtotime($coupon['expire_date']))}}"
                                           placeholder="{{translate('expire date')}}" required>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                                <button type="reset" class="btn btn-secondary px-4">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-4">{{translate('Update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/coupon.js')}}"></script>
@endpush
