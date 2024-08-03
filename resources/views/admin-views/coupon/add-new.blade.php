@extends('layouts.back-end.app')

@section('title', translate('coupon_Add'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/coupon_setup.png')}}" alt="">
                {{translate('coupon_setup')}}
            </h2>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.coupon.add')}}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('coupon_type')}}</label>
                                    <select class="form-control" id="coupon_type" name="coupon_type" required>
                                        <option disabled selected>{{translate('select_coupon_type')}}</option>
                                        <option
                                            value="discount_on_purchase">{{translate('discount_on_Purchase')}}</option>
                                        <option value="free_delivery">{{translate('free_Delivery')}}</option>
                                        <option value="first_order">{{translate('first_Order')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('coupon_title')}}</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                           id="title"
                                           placeholder="{{translate('title')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="name"
                                               class="title-color font-weight-medium text-capitalize">{{translate('coupon_code')}}</label>
                                        <a href="javascript:void(0)" class="float-right c1 fz-12" id="generateCode">{{translate('generate_code')}}</a>
                                    </div>
                                    <input type="text" name="code" value=""
                                           class="form-control" id="code"
                                           placeholder="{{translate('ex')}}: EID100" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group first_order">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('coupon_bearer')}}</label>
                                    <select class="form-control" name="coupon_bearer" id="coupon_bearer">
                                        <option disabled selected>{{translate('select_coupon_bearer')}}</option>
                                        <option value="seller">{{translate('vendor')}}</option>
                                        <option value="inhouse">{{translate('admin')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group coupon_by first_order">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('vendor')}}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="seller_id" id="vendor_wise_coupon">
                                        <option disabled selected>{{translate('select_vendor')}}</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-4 form-group coupon_type first_order">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('customer')}}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="customer_id">
                                        <option disabled selected>{{translate('select_customer')}}</option>
                                        <option value="0">{{translate('all_customer')}}</option>
                                        @foreach($customers as $customer)
                                            <option
                                                value="{{ $customer->id }}">{{ $customer->f_name. ' '. $customer->l_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group first_order">
                                    <label
                                        for="exampleFormControlInput1"
                                        class="title-color font-weight-medium d-flex">{{translate('limit_for_same_user')}}</label>
                                    <input type="number" name="limit" value="{{ old('limit') }}" min="0"
                                           id="coupon_limit" class="form-control"
                                           placeholder="{{translate('ex')}}: 10">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('discount_type')}}</label>
                                    <select id="discount_type" class="form-control w-100" name="discount_type">
                                        <option value="amount">{{translate('amount')}}</option>
                                        <option value="percentage">{{translate('percentage')}} (%)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('discount_Amount')}}
                                        <span id="discount_percent"> (%)</span></label>
                                    <input type="number" min="1" max="1000000" name="discount"
                                           value="{{ old('discount') }}" class="form-control"
                                           id="discount"
                                           placeholder="{{translate('ex')}} : 500">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('minimum_purchase')}}
                                        ($)</label>
                                    <input type="number" min="1" max="1000000" name="min_purchase"
                                           value="{{ old('min_purchase') }}" class="form-control"
                                           id="minimum purchase"
                                           placeholder="{{translate('ex')}} : 100">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery" id="max-discount">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('maximum_discount')}}
                                        ($)</label>
                                    <input type="number" min="1" max="1000000" name="max_discount"
                                           value="{{ old('max_discount') }}"
                                           class="form-control" id="maximum discount"
                                           placeholder="{{translate('ex')}} : 5000">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('start_date')}}</label>
                                    <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}"
                                           class="form-control"
                                           placeholder="{{translate('start_date')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{translate('expire_date')}}</label>
                                    <input id="expire_date" type="date" name="expire_date"
                                           value="{{ old('expire_date') }}" class="form-control"
                                           placeholder="{{translate('expire_date')}}" required>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                                <button type="reset" class="btn btn-secondary px-4">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-4">{{translate('submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="d-flex flex-wrap  gap-3 align-items-center">
                            <h5 class="mb-0 text-capitalize d-flex gap-2 mr-auto">
                                {{translate('coupon_list')}}
                                <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $coupons->total() }}</span>
                            </h5>
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group input-group-merge input-group-custom">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                           placeholder="{{translate('search_by_Title_or_Code_or_Discount_Type')}}"
                                           value="{{ request('searchValue') }}" aria-label="Search orders" required>
                                    <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                </div>
                            </form>
                            <div>
                                <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                        data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('admin.coupon.export',['searchValue'=>request('searchValue')]) }}">
                                            <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}"
                                                 alt="">
                                            {{translate('excel')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{ Session::get('direction') === 'rtl' ? 'text-right' : 'text-left' }}">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('coupon')}}</th>
                                <th>{{translate('coupon_type')}}</th>
                                <th>{{translate('duration')}}</th>
                                <th>{{translate('user_limit')}}</th>
                                <th class="text-center">{{translate('discount_bearer')}}</th>
                                <th>{{translate('status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coupons as $key => $coupon )
                                <tr>
                                    <td>{{$coupons->firstItem() + $key }}</td>
                                    <td>
                                        <div>{{substr($coupon['title'],0,20)}}</div>
                                        <strong>{{translate('code')}}: {{$coupon['code']}}</strong>
                                    </td>
                                    <td class="text-capitalize">{{translate(str_replace('_',' ',$coupon['coupon_type']))}}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <span>{{date('d M, y',strtotime($coupon['start_date']))}} - </span>
                                            <span>{{date('d M, y',strtotime($coupon['expire_date']))}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span>{{translate('limit')}}:
                                            <strong>{{ $coupon['limit'] }},</strong>
                                        </span>

                                        <span class="ml-1">{{translate('used')}}:
                                            <strong>{{ $coupon['order_count'] }}</strong>
                                        </span>
                                    </td>
                                    <td class="text-center">{{ translate($coupon['coupon_bearer'] == 'inhouse' ? 'admin':$coupon['coupon_bearer']) }}</td>
                                    <td>
                                        <form
                                            action="{{route('admin.coupon.status',[$coupon['id'],$coupon['status']?0:1])}}"
                                            method="GET" id="coupon_status{{$coupon['id']}}-form"
                                            class="coupon_status_form">
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                       id="coupon_status{{$coupon['id']}}" name="status" value="1"
                                                       {{ $coupon['status'] == 1 ? 'checked':'' }}
                                                       data-modal-id="toggle-status-modal"
                                                       data-toggle-id="coupon_status{{$coupon['id']}}"
                                                       data-on-image="coupon-status-on.png"
                                                       data-off-image="coupon-status-off.png"
                                                       data-on-title="{{translate('Want_to_Turn_ON_Coupon_Status').'?'}}"
                                                       data-off-title="{{translate('Want_to_Turn_OFF_Coupon_Status').'?'}}"
                                                       data-on-message="<p>{{translate('if_enabled_this_coupon_will_be_available_on_the_website_and_customer_app')}}</p>"
                                                       data-off-message="<p>{{translate('if_disabled_this_coupon_will_be_hidden_from_the_website_and_customer_app')}}</p>"
                                                >
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-10 justify-content-center">
                                            <button class="btn btn-outline--primary square-btn btn-sm mr-1 get-quick-view" data-id="{{ $coupon['id'] }}">
                                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/eye.svg')}}" class="svg" alt="">
                                            </button>
                                            <a class="btn btn-outline--primary btn-sm edit"
                                               href="{{route('admin.coupon.update',[$coupon['id']])}}"
                                               title="{{ translate('edit')}}"
                                            >
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm delete delete-data"
                                               href="javascript:"
                                               data-id="coupon-{{$coupon['id']}}"
                                               title="{{translate('delete')}}"
                                            >
                                                <i class="tio-delete"></i>
                                            </a>
                                            <form action="{{route('admin.coupon.delete',[$coupon['id']])}}"
                                                  method="post" id="coupon-{{$coupon['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="modal fade" id="quick-view" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered coupon-details" role="document">
                                <div class="modal-content" id="quick-view-modal">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {{$coupons->links()}}
                        </div>
                    </div>

                    @if(count($coupons)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_coupon_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>

    <span id="coupon-bearer-url" data-url="{{route('admin.coupon.ajax-get-vendor')}}"></span>
    <span id="get-detail-url" data-url="{{ route('admin.coupon.quick-view-details') }}"></span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/coupon.js')}}"></script>
@endpush
