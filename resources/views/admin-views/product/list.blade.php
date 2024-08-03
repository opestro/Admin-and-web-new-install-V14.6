@extends('layouts.back-end.app')

@section('title', translate('product_List'))

@section('content')
    <div class="content container-fluid">

        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/inhouse-product-list.png') }}" alt="">
                @if($type == 'in_house')
                    {{ translate('in_House_Product_List') }}
                @elseif($type == 'seller')
                    {{ translate('vendor_Product_List') }}
                @endif
                <span class="badge badge-soft-dark radius-50 fz-14 ml-1">{{ $products->total() }}</span>
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ url()->current() }}" method="GET">
                    <input type="hidden" value="{{ request('status') }}" name="status">
                    <div class="row gx-2">
                        <div class="col-12">
                            <h4 class="mb-3">{{ translate('filter_Products') }}</h4>
                        </div>
                        @if (request('type') == 'seller')
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label class="title-color" for="store">{{ translate('store') }}</label>
                                    <select name="seller_id" class="form-control text-capitalize">
                                        <option value="" selected>{{ translate('all_store') }}</option>
                                        @foreach ($sellers as $seller)
                                            <option value="{{ $seller->id}}"{{request('seller_id')==$seller->id ? 'selected' :''}}>
                                                {{ $seller->shop->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color" for="store">{{ translate('brand') }}</label>
                                <select name="brand_id" class="js-select2-custom form-control text-capitalize">
                                    <option value="" selected>{{ translate('all_brand') }}</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id}}" {{request('brand_id')==$brand->id ? 'selected' :''}}>{{ $brand->default_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('category') }}</label>
                                <select class="js-select2-custom form-control action-get-request-onchange" name="category_id"
                                        data-url-prefix="{{ url('/admin/products/get-categories?parent_id=') }}"
                                        data-element-id="sub-category-select"
                                        data-element-type="select">
                                    <option value="{{ old('category_id') }}" selected
                                            disabled>{{ translate('select_category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category['id'] }}"
                                                {{ request('category_id') == $category['id'] ? 'selected' : '' }}>
                                            {{ $category['defaultName'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('sub_Category') }}</label>
                                <select class="js-select2-custom form-control action-get-request-onchange" name="sub_category_id"
                                        id="sub-category-select"
                                        data-url-prefix="{{ url('/admin/products/get-categories?parent_id=') }}"
                                        data-element-id="sub-sub-category-select"
                                        data-element-type="select">
                                    <option value="{{request('sub_category_id') != null ? request('sub_category_id') : null}}"
                                            selected {{request('sub_category_id') != null ? '' : 'disabled'}}>{{request('sub_category_id') != null ? $subCategory['defaultName']: translate('select_Sub_Category') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('sub_Sub_Category') }}</label>
                                <select class="js-select2-custom form-control" name="sub_sub_category_id"
                                        id="sub-sub-category-select">
                                    <option value="{{request('sub_sub_category_id') != null ? request('sub_sub_category_id') : null}}"
                                            selected {{request('sub_sub_category_id') != null ? '' : 'disabled'}}>{{request('sub_sub_category_id') != null ? $subSubCategory['defaultName'] : translate('select_Sub_Sub_Category') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('admin.products.list',['type'=>request('type')]) }}"
                                   class="btn btn-secondary px-5">
                                    {{ translate('reset') }}
                                </a>
                                <button type="submit" class="btn btn--primary px-5 action-get-element-type">
                                    {{ translate('show_data') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-lg-4">

                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue"
                                               class="form-control"
                                               placeholder="{{ translate('search_by_Product_Name') }}"
                                               aria-label="Search orders"
                                               value="{{ request('searchValue') }}">
                                        <input type="hidden" value="{{ request('status') }}" name="status">
                                        <button type="submit" class="btn btn--primary">{{ translate('search') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-8 mt-3 mt-lg-0 d-flex flex-wrap gap-3 justify-content-lg-end">

                                <div>
                                    <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{ translate('export') }}
                                        <i class="tio-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route('admin.products.export-excel',['type'=>request('type')]) }}?brand_id={{request('brand_id') }}&searchValue={{ request('searchValue') }}&category_id={{request('category_id') }}&sub_category_id={{request('sub_category_id') }}&sub_sub_category_id={{request('sub_sub_category_id') }}&seller_id={{request('seller_id') }}&status={{request('status') }}">
                                                <img width="14" src="{{ dynamicAsset(path: 'public/assets/back-end/img/excel.png') }}"
                                                     alt="">
                                                {{ translate('excel') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                @if($type == 'in_house')
                                    <a href="{{ route('admin.products.stock-limit-list',['in_house']) }}"
                                       class="btn btn-info">
                                        <span class="text">{{ translate('limited_Stocks') }}</span>
                                    </a>
                                    <a href="{{ route('admin.products.add') }}" class="btn btn--primary">
                                        <i class="tio-add"></i>
                                        <span class="text">{{ translate('add_new_product') }}</span>
                                    </a>
                                @endif
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
                                <th class="text-center">{{ translate('product Type') }}</th>
                                <th class="text-center">{{ translate('unit_price') }}</th>
                                <th class="text-center">{{ translate('show_as_featured') }}</th>
                                <th class="text-center">{{ translate('active_status') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key=>$product)
                                <tr>
                                    <th scope="row">{{ $products->firstItem()+$key}}</th>
                                    <td>
                                        <a href="{{ route('admin.products.view',['addedBy'=>($product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$product['id']]) }}"
                                           class="media align-items-center gap-2">
                                            <img src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'backend-product') }}"
                                                 class="avatar border" alt="">
                                            <span class="media-body title-color hover-c1">
                                            {{ Str::limit($product['name'], 20) }}
                                        </span>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{ translate(str_replace('_',' ',$product['product_type'])) }}
                                    </td>
                                    <td class="text-center">
                                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product['unit_price']), currencyCode: getCurrencyCode()) }}
                                    </td>
                                    <td class="text-center">

                                        @php($productName = str_replace("'",'`',$product['name']))
                                        <form action="{{ route('admin.products.featured-status') }}" method="post"
                                              id="product-featured{{ $product['id']}}-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                       name="status"
                                                       id="product-featured{{ $product['id'] }}" value="1"
                                                       {{ $product['featured'] == 1 ? 'checked' : '' }}
                                                       data-modal-id="toggle-status-modal"
                                                       data-toggle-id="product-featured{{ $product['id'] }}"
                                                       data-on-image="product-status-on.png"
                                                       data-off-image="product-status-off.png"
                                                       data-on-title="{{ translate('Want_to_Add').' '.$productName.' '.translate('to_the_featured_section') }}"
                                                       data-off-title="{{ translate('Want_to_Remove').' '.$productName.' '.translate('to_the_featured_section') }}"
                                                       data-on-message="<p>{{ translate('if_enabled_this_product_will_be_shown_in_the_featured_product_on_the_website_and_customer_app') }}</p>"
                                                       data-off-message="<p>{{ translate('if_disabled_this_product_will_be_removed_from_the_featured_product_section_of_the_website_and_customer_app') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>

                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.products.status-update') }}" method="post" data-from="product-status"
                                              id="product-status{{ $product['id']}}-form" class="admin-product-status-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                       name="status"
                                                       id="product-status{{ $product['id'] }}" value="1"
                                                       {{ $product['status'] == 1 ? 'checked' : '' }}
                                                       data-modal-id="toggle-status-modal"
                                                       data-toggle-id="product-status{{ $product['id'] }}"
                                                       data-on-image="product-status-on.png"
                                                       data-off-image="product-status-off.png"
                                                       data-on-title="{{ translate('Want_to_Turn_ON').' '.$productName.' '.translate('status') }}"
                                                       data-off-title="{{ translate('Want_to_Turn_OFF').' '.$productName.' '.translate('status') }}"
                                                       data-on-message="<p>{{ translate('if_enabled_this_product_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                       data-off-message="<p>{{ translate('if_disabled_this_product_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline-info btn-sm square-btn"
                                               title="{{ translate('barcode') }}"
                                               href="{{ route('admin.products.barcode', [$product['id']]) }}">
                                                <i class="tio-barcode"></i>
                                            </a>
                                            <a class="btn btn-outline-info btn-sm square-btn" title="View"
                                               href="{{ route('admin.products.view',['addedBy'=>($product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$product['id']]) }}">
                                                <i class="tio-invisible"></i>
                                            </a>
                                            <a class="btn btn-outline--primary btn-sm square-btn"
                                               title="{{ translate('edit') }}"
                                               href="{{ route('admin.products.update',[$product['id']]) }}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <span class="btn btn-outline-danger btn-sm square-btn delete-data"
                                               title="{{ translate('delete') }}"
                                               data-id="product-{{ $product['id']}}">
                                                <i class="tio-delete"></i>
                                            </span>
                                        </div>
                                        <form action="{{ route('admin.products.delete',[$product['id']]) }}"
                                              method="post" id="product-{{ $product['id']}}">
                                            @csrf @method('delete')
                                        </form>
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
    <span id="message-select-word" data-text="{{ translate('select') }}"></span>
@endsection
