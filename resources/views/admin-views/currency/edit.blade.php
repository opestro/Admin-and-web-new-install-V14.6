@extends('layouts.back-end.app')

@section('title', translate('update_Currency'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/coupon_setup.png')}}" alt="">
                {{translate('currency_update')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="tio-money"></i>
                            {{translate('update_Currency')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.currency.update',[$currency['id']])}}" method="post"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            @csrf
                            <div class="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="title-color text-capitalize">{{translate('currency_name').':'}}</label>
                                        <input type="text" name="name"
                                               placeholder="{{translate('currency_name')}}"
                                               class="form-control" id="name"
                                               value="{{$currency['name']}}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="title-color text-capitalize">{{translate('currency_symbol').':'}}</label>
                                        <input type="text" name="symbol"
                                               placeholder="{{translate('currency_symbol')}}"
                                               class="form-control" id="symbol"
                                               value="{{$currency['symbol']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="title-color text-capitalize">{{translate('currency_code').':'}} </label>
                                        <input type="text" name="code"
                                               placeholder="{{translate('currency_code')}}"
                                               class="form-control" id="code"
                                               value="{{$currency['code']}}">
                                    </div>
                                    @if($currencyModel=='multi_currency')
                                        <div class="col-md-6 mb-3">
                                            <label class="title-color">{{translate('exchange_rate').':'}}</label>
                                            <input type="number" min="0" max="1000000"
                                                   name="exchange_rate" step="0.00000001"
                                                   placeholder="{{translate('exchange_Rate')}}"
                                                   class="form-control" id="exchange_rate"
                                                   value="{{$currency['exchange_rate']}}">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-10 justify-content-end">
                                <button type="submit" id="add" class="btn btn--primary">{{translate('update')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
