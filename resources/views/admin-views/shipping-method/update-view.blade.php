@extends('layouts.back-end.app')

@section('title', translate('shipping_method'))

@section('content')
<div class="content container-fluid">
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/business-setup.png')}}" alt="">
            {{translate('shipping_method_update')}}
        </h2>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.business-settings.shipping-method.update',[$method['id']])}}"
                          class="text-start"
                          method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row ">
                                <div class="col-md-12">
                                    <label class="title-color" for="title">{{translate('title')}}</label>
                                    <input type="text" name="title" value="{{$method['title']}}" class="form-control" placeholder="{{translate('title')}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row ">
                                <div class="col-md-12">
                                    <label class="title-color" for="duration">{{translate('duration')}}</label>
                                    <input type="text" name="duration" value="{{$method['duration']}}"
                                           class="form-control"
                                           placeholder="{{translate('ex').' '.':'.' '.translate('4_to_6_days')}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row ">
                                <div class="col-md-12">
                                    <label class="title-color" for="cost">{{translate('cost')}}</label>
                                    <input type="number" min="0" max="1000000" name="cost"
                                           value="{{usdToDefaultCurrency(amount: $method['cost'])}}"
                                           class="form-control"
                                           placeholder="{{translate('ex').' '.':'.' '.translate('10')}}$">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-10 flex-wrap justify-content-end">
                            <button type="submit" class="btn btn--primary px-4">{{translate('update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
