@extends('layouts.back-end.app')
@section('title', translate('product_Report'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-center">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/seller_sale.png')}}" alt="">
                {{translate('product_Report')}}
            </h2>
        </div>

        @include('admin-views.report.product-report-inline-menu')

        <div class="card mb-2">
            <div class="card-body">
                <form action="" id="form-data" method="GET">
                    <h4 class="mb-3">{{translate('filter_Data')}}</h4>
                    <div class="row gx-2 gy-3 align-items-center text-left">
                        <div class="col-sm-6 col-md-3">
                            <select class="js-select2-custom form-control __form-control" name="seller_id">
                                <option class="text-center" value="all" {{ $seller_id == 'all' ? 'selected' : '' }}>
                                    {{translate('all')}}
                                </option>
                                <option class="text-center"
                                        value="inhouse" {{ $seller_id == 'inhouse' ? 'selected' : '' }}>
                                    {{translate('inhouse')}}
                                </option>
                                @foreach($sellers as $seller)
                                    <option
                                        value="{{$seller['id']}}" {{$seller_id==$seller['id']?'selected':''}}>
                                        {{$seller['f_name']}} {{$seller['l_name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select class="form-control __form-control" name="date_type" id="date_type">
                                <option
                                    value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{translate('this_Year')}}</option>
                                <option
                                    value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{translate('this_Month')}}</option>
                                <option
                                    value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{translate('this_Week')}}</option>
                                <option
                                    value="today" {{ $date_type == 'today'? 'selected' : '' }}>{{translate('today')}}</option>
                                <option
                                    value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{translate('custom_Date')}}</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3" id="from_div">
                            <div class="form-floating">
                                <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                                <label>{{ ucwords(translate('start_date'))}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div class="form-floating">
                                <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                                <label>{{ ucwords(translate('end_date'))}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 filter-btn">
                            <button type="submit" class="btn btn--primary px-4 px-md-5">
                                {{translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="store-report-content mb-2">
            <div class="left-content">
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/cart.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ $product_count['reject_product_count']+$product_count['active_product_count']+$product_count['pending_product_count'] }}</h4>
                        <h6 class="subtext">{{translate('total_Product')}}</h6>
                    </div>
                    <div class="coupon__discount w-100 text-right d-flex justify-content-between">
                        <div class="text-center">
                            <strong class="text-danger">{{ $product_count['reject_product_count'] }}</strong>
                            <div class="d-flex">
                                <span>{{translate('rejected')}}</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-primary">{{ $product_count['pending_product_count'] }}</strong>
                            <div class="d-flex">
                                <span>{{translate('pending')}}</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-success">{{ $product_count['active_product_count'] }}</strong>
                            <div class="d-flex">
                                <span>{{translate('active')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/products.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">
                            {{ $total_product_sale }}
                        </h4>
                        <h6 class="subtext">{{translate('total_Product_Sale')}}</h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/stores.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">
                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_discount_given), currencyCode: getCurrencyCode()) }}
                        </h4>
                        <h6 class="subtext d-flex">
                            {{translate('total_Discount_Given')}}
                            <span class="ml-2" data-toggle="tooltip" data-placement="top"
                                  title="{{translate('product_wise_discounted_amount_will_be_shown_here')}}">
                                <img class="info-img"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                     alt="img">
                            </span>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="center-chart-area">
                @include('layouts.back-end._apexcharts',['title'=>'product_Statistics','statisticsValue'=>$chart_data['total_product'],'label'=>array_keys($chart_data['total_product']),'statisticsTitle'=>'total_product','getCurrency'=>false])
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex flex-wrap w-100 gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{translate('total_Product')}}
                        <span class="badge badge-soft-dark radius-50 fz-12"> {{ $products->total() }}</span>
                    </h4>
                    <form action="#" method="GET">
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input type="hidden" name="seller_id" value="{{ $seller_id }}">
                            <input type="hidden" name="date_type" value="{{ $date_type }}">
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                   placeholder="{{translate('search_product_name')}}" aria-label="Search orders"
                                   value="{{ $search }}">
                            <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                        </div>
                    </form>
                    <div>
                        <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                data-toggle="dropdown">
                            <i class="tio-download-to"></i>
                            {{ translate('export') }}
                            <i class="tio-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('admin.report.all-product-excel', ['seller_id' => request('seller_id'), 'search' => request('search'), 'date_type' => request('date_type'), 'from' => request('from'), 'to' => request('to')]) }}">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}"
                                         alt="">
                                    {{translate('excel')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" id="products-table">
                    <table
                        class="table table-hover __table table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>
                                {{translate('product_Name')}}
                            </th>
                            <th>
                                {{translate('product_Unit_Price')}}
                            </th>
                            <th>
                                {{translate('total_Amount_Sold')}}
                            </th>
                            <th>
                                {{translate('total_Quantity_Sold')}}
                            </th>
                            <th>
                                <span>{{translate('average_Product_Value')}} </span>
                            </th>
                            <th>
                                {{translate('current_Stock_Amount')}}
                            </th>
                            <th>
                                {{translate('average_Ratings')}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $key=>$product)
                            <tr>
                                <td>{{ $products->firstItem()+$key }}</td>
                                <td>
                                    <a href="{{route('admin.products.view',['addedBy'=>($product['added_by'] =='seller'?'vendor' : 'in-house'),'id'=>$product['id']])}}">
                                            <span class="media-body title-color hover-c1">
                                                {{\Illuminate\Support\Str::limit($product['name'], 20)}}
                                            </span>
                                    </a>
                                </td>
                                <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->unit_price), currencyCode: getCurrencyCode()) }}</td>
                                <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: isset($product->orderDetails[0]->total_sold_amount) ? $product->orderDetails[0]->total_sold_amount : 0), currencyCode: getCurrencyCode()) }}</td>
                                <td>{{ isset($product->orderDetails[0]->product_quantity) ? $product->orderDetails[0]->product_quantity : 0 }}</td>
                                <td>
                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: (
                                            isset($product->orderDetails[0]->total_sold_amount) ? $product->orderDetails[0]->total_sold_amount : 0) /
                                            (isset($product->orderDetails[0]->product_quantity) ? $product->orderDetails[0]->product_quantity : 1)
                                        ), currencyCode: getCurrencyCode()) }}
                                </td>
                                <td>
                                    {{ $product->product_type == 'digital' ? ($product->status==1 ? translate('available') : translate('not_available')) : $product->current_stock }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rating mr-1"><i class="tio-star"></i>
                                            {{count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0}}
                                        </div>
                                        <div>
                                            ( {{$product->reviews->count()}} )
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-center justify-content-md-end">
                    {!! $products->links() !!}
                </div>
            </div>
            @if(count($products)==0)
                @include('layouts.back-end._empty-state',['text'=>'no_product_found'],['image'=>'default'])
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts-data-show.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/product-report.js') }}"></script>
@endpush
