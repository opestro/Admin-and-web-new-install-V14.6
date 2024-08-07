@extends('layouts.back-end.app')

@section('title', translate('updated_product_list'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 text-capitalize mb-1 d-flex gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/inhouse-product-list.png') }}" alt="">
                {{ translate('update_product') }}
            </h2>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row gy-2 justify-content-between align-items-center">
                            <div class="col-auto">
                                <h5 class="mb-0">
                                    {{ translate('product_table') }}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">
                                        {{ $products->total() }}
                                    </span>
                                </h5>
                            </div>
                            <div class="col-auto">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue"
                                               class="form-control"
                                               placeholder="{{ translate('search_Product_Name') }}"
                                               aria-label="Search orders"
                                               value="{{ $searchValue }}" required>
                                        <button type="submit" class="btn btn--primary">
                                            {{ translate('search') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('SL') }}</th>
                                <th>{{ translate('product Name') }}</th>
                                <th>{{ translate('previous_shipping_cost') }}</th>
                                <th>{{ translate('new_shipping_cost') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key=>$product)
                                <tr>
                                    <th scope="row">{{ $products->firstItem()+$key}}</th>
                                    <td>
                                        <a href="{{route('admin.products.view',['addedBy'=>($product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$product['id']]) }}"
                                           class="title-color hover-c1">
                                            {{ Str::limit($product['name'],20) }}
                                        </a>
                                    </td>
                                    <td>
                                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product['shipping_cost']), currencyCode: getCurrencyCode()) }}
                                    </td>
                                    <td>
                                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product['temp_shipping_cost']), currencyCode: getCurrencyCode()) }}
                                    </td>

                                    <td>
                                        <div class="d-flex gap-10 align-items-center justify-content-center">
                                            <button class="btn btn--primary btn-sm update-status"
                                                    data-id="{{ $product['id'] }}"
                                                    data-message ="{{translate('want_to_approve_this_update_request').'?'}}"
                                                    data-status="1">
                                                {{ translate('approved') }}
                                            </button>
                                            <button class="btn btn-danger btn-sm update-status"
                                                    data-id="{{ $product['id'] }}"
                                                    data-message ="{{translate('want_to_deny_this_update_request').'?'}}"
                                                    data-status="2">
                                                {{ translate('denied') }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {{ $products->links() }}
                        </div>
                    </div>

                    @if(count($products)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_product_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>

<span id="get-update-status-route" data-action="{{ route('admin.products.updated-shipping') }}"></span>
@endsection
