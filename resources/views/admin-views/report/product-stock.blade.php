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
                            <select
                                    class="js-select2-custom form-control text-ellipsis"
                                    name="seller_id">
                                <option value="all" {{ $seller_id == 'all' ? 'selected' : '' }}>{{translate('all')}}</option>
                                <option value="in_house" {{ $seller_id == 'in_house' ? 'selected' : '' }}>{{translate('in-House')}}</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller['id'] }}" {{ $seller_id == $seller['id'] ? 'selected' : '' }}>
                                        {{$seller['f_name']}} {{$seller['l_name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select class="js-select2-custom form-control __form-control" name="category_id"
                                    id="cat_id">
                                <option value="all" {{ $category_id == 'all' ? 'selected' : '' }}>{{translate('all_category')}}</option>
                                @foreach($categories as $category)
                                    <option value="{{$category['id']}}" {{ $category_id == $category['id'] ? 'selected' : '' }}>{{ $category['default_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="">
                                <select
                                        class="form-control"
                                        name="sort">
                                    <option value="ASC" {{ $sort == 'ASC' ? 'selected' : '' }}>{{translate('stock_sort_by_(low_to_high)')}}</option>
                                    <option value="DESC" {{ $sort == 'DESC' ? 'selected' : '' }}>{{translate('stock_sort_by_(high_to_low)')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <button type="submit" class="btn btn--primary w-100">
                                {{translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex flex-wrap w-100 gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{translate('total_Products')}}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ $products->total() }}</span>
                    </h4>
                    <form action="" method="GET">
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $seller_id }}" name="seller_id">
                            <input type="hidden" value="{{ $category_id }}" name="category_id">
                            <input type="hidden" value="{{ $sort }}" name="sort">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                   placeholder="{{translate('search_Product_Name')}}" aria-label="Search orders"
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
                                   href="{{ route('admin.stock.product-stock-export', ['sort' => request('sort'), 'category_id' => request('category_id'), 'seller_id' => request('seller_id'), 'search' => request('search')]) }}">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                    {{translate('excel')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" id="products-table">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>
                                {{translate('product_Name')}}
                            </th>
                            <th>
                                {{translate('last_Updated_Stock')}}
                            </th>
                            <th class="text-center">
                                {{translate('current_Stock')}}
                            </th>
                            <th class="text-center">
                                {{translate('status')}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $key=>$data)
                            <tr>
                                <td>{{$products->firstItem()+$key}}</td>
                                <td>
                                    <div class="p-name">
                                        <a href="{{route('admin.products.view',['addedBy'=>($data['added_by'] =='seller'?'vendor' : 'in-house'),'id'=>$data['id']])}}"
                                           class="media align-items-center gap-2 title-color">
                                            <span>{{\Illuminate\Support\Str::limit($data['name'],20)}}</span>
                                        </a>
                                    </div>
                                </td>
                                <td>{{ date('d M Y, h:i:s a', $data['updated_at'] ? strtotime($data['updated_at']) : null) }}</td>
                                <td class="text-center">{{$data['current_stock']}}</td>
                                <td>
                                    <div class="text-center">
                                        @if($data['current_stock'] >= $stock_limit)
                                            <span class="badge __badge badge-soft-success">{{translate('in-Stock')}}</span>
                                        @elseif($data['current_stock']  <= 0)
                                            <span class="badge __badge badge-soft-warning">{{translate('out_of_Stock')}}</span>
                                        @elseif($data['current_stock'] < $stock_limit)
                                            <span class="badge __badge badge-soft--primary">{{translate('soon_Stock_Out')}}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
    </div>
@endsection
