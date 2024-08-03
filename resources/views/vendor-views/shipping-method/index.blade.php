@php
use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app-seller')

@section('title', translate('add_Shipping'))

@section('content')
    @php($direction = Session::get('direction'))
    <div class="content container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/shipping_method.png')}}" alt="">
                {{translate('shipping_method')}}
            </h2>
            <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder text-capitalize">
                {{translate('delivery_restriction')}}
                <div class="ripple-animation" data-toggle="modal" data-target="#howItWorksModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"
                         class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z"
                              fill="currentColor"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="modal fade" id="howItWorksModal" tabindex="-1" aria-labelledby="howItWorksModal"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                        <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                                class="tio-clear"></i></button>
                    </div>
                    <div class="modal-body px-4 px-sm-5 pt-0 text-center">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/delivery-restriction.png')}}"
                                 loading="lazy" alt="">
                            <h4 class="lh-md text-capitalize">{{translate('delivery_restriction')}}</h4>
                            <div class="d-flex flex-column align-items-start">
                                <p class="text--black">{{'1'.'. '.translate('you_can_only_delivery_in_added_zip_code_areas_that_has_already_selected_by_admin').'.'}}</p>
                                <p class="text--black">{{'2'.'. '.translate('you_can_only_deliver_orders_to_the_selected_countries_which_admin_selected').'.'}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/delivery.png')}}" alt="">
                            {{translate('shipping')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-capitalize" style="text-align: {{$direction === "rtl" ? 'right' : 'left'}};">
                                <select class="form-control text-capitalize w-100 shipping-type" name="shippingCategory">
                                    <option value="0" selected disabled>{{'---'.translate('select').'---'}}</option>
                                    <option
                                        value="order_wise" {{$shippingType=='order_wise'?'selected':'' }} >{{translate('order_wise')}} </option>
                                    <option
                                        value="category_wise" {{$shippingType=='category_wise'?'selected':'' }} >{{translate('category_wise')}}</option>
                                    <option
                                        value="product_wise" {{$shippingType=='product_wise'?'selected':'' }}>{{translate('product_wise')}}</option>
                                </select>
                            </div>
                            <div class="mt-2 mx-3" id="product_wise_note">
                                <p>
                                    <img width="16" class="mt-n1"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/danger-info.png')}}" alt="">
                                    <strong>{{translate('note').' '.':'}}</strong>
                                    {{translate('please_make_sure_all_the product`s_delivery_charges_are_up_to_date').'.'}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="order_wise_shipping">
            <div class="card mt-2">
                <div class="card-header">
                    <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/delivery.png')}}" alt="">
                        {{translate('add_order_wise_shipping')}}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{route('vendor.business-settings.shipping-method.index')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <label class="title-color d-flex" for="title">{{translate('title')}}</label>
                                            <input type="text" name="title" class="form-control" placeholder="{{translate('title')}}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <label class="title-color d-flex" for="duration">{{translate('duration')}}</label>
                                            <input type="text" name="duration" class="form-control" placeholder="{{translate('ex').':'.translate('4_to_6_days')}}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <label class="title-color d-flex" for="cost">{{translate('cost')}}</label>
                                            <input type="number" min="0" step="0.01" max="1000000" name="cost" class="form-control" placeholder="{{translate('ex').':'.usdToDefaultCurrency('10')}}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-10">
                            <button type="submit" class="btn btn--primary px-5">{{translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-2">
                <div class="px-3 py-4">
                    <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/delivery.png')}}" alt="">
                        {{translate('order_wise_shipping_method')}}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ $shippingMethods->count() }}</span>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table id="datatable"
                           class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-start">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('title')}}</th>
                            <th>{{translate('duration')}}</th>
                            <th>{{translate('cost')}}</th>
                            <th class="text-center">{{translate('status')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($shippingMethods as $k=>$method)
                            <tr>
                                <th>{{$shippingMethods->firstItem()+$k}}</th>
                                <td>{{$method['title']}}</td>
                                <td>
                                    {{$method['duration']}}
                                </td>
                                <td>
                                    {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $method['cost']), currencyCode: getCurrencyCode(type: 'default'))}}
                                </td>

                                <td>
                                    <form action="{{route('vendor.business-settings.shipping-method.update-status')}}" method="post" id="shipping-methods{{$method['id']}}-form" class="shipping_methods_form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$method['id']}}">
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input toggle-switch-message"
                                                   id="shipping-methods{{$method['id']}}" name="status" value="1"
                                                   {{$method->status == 1 ? 'checked' : ''}}
                                                   data-modal-id = "toggle-status-modal"
                                                   data-toggle-id = "shipping-methods{{$method['id']}}"
                                                   data-on-image = "category-status-on.png"
                                                   data-off-image = "category-status-off.png"
                                                   data-on-title = "{{translate('want_to_Turn_ON_This_Shipping_Method').'?'}}"
                                                   data-off-title = "{{translate('want_to_Turn_OFF_This_Shipping_Method').'?'}}"
                                                   data-on-message = "<p>{{translate('if_you_enable_this_shipping_method_will_be_shown_in_the_user_app_and_website_for_customer_checkout')}}</p>"
                                                   data-off-message = "<p>{{translate('if_you_disable_this_shipping_method_will_not_be_shown_in_the_user_app_and_website_for_customer_checkout')}}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a  class="btn btn-outline--primary btn-sm square-btn"
                                            title="{{translate('edit')}}"
                                            href="{{route('vendor.business-settings.shipping-method.update',[$method['id']])}}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a  class="btn btn-outline-danger btn-sm  delete-data-without-form"
                                            data-action="{{route('vendor.business-settings.shipping-method.delete')}}"
                                            data-id="{{ $method['id'] }}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        {!! $shippingMethods->links() !!}
                    </div>
                </div>
                @if(count($shippingMethods)==0)
                    @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                @endif
            </div>
        </div>
        <div class="card mt-2" id="update_category_shipping_cost">
            <div class="px-3 pt-4">
                <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                    <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/delivery.png')}}" alt="">
                    {{translate('category_wise_shipping_cost')}}
                </h5>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive">
                    <form action="{{route('vendor.business-settings.category-wise-shipping-cost.index')}}" method="POST">
                        @csrf
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               style="text-align: {{$direction === "rtl" ? 'right' : 'left'}};">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('category_name')}}</th>
                                <th>{{translate('cost_per_product')}}</th>
                                <th class="text-center">{{translate('multiply_with_QTY')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($serial = 0)
                                @foreach ($allCategoryShippingCost as $key=>$item)
                                    @if($item->category)
                                        <tr>
                                            <td>
                                                {{++$serial}}
                                            </td>
                                            <td>
                                                {{$item->category->name}}
                                            </td>
                                            <td>
                                                <input type="hidden" class="form-control w-auto" name="ids[]" value="{{$item->id}}">
                                                <input type="number" class="form-control w-auto" min="0" step="0.01" name="cost[]" value="{{usdToDefaultCurrency($item->cost)}}">
                                            </td>
                                            <td>
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" name="multiplyQTY[]" class="switcher_input"
                                                           id="" value="{{$item->id}}" {{$item->multiply_qty == 1?'checked':''}}>
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="4">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn--primary ">{{translate('save')}}</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <span id="get-shipping-type-data" data-action="{{route('vendor.business-settings.shipping-type.index')}}" data-success="{{translate('shipping_method_updated_successfully').'!!'}}"></span>
    <span id="get-shipping-type-value" data-value="{{$shippingType}}"></span>
@endsection
@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/shipping-method.js')}}"></script>
@endpush

