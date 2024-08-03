@php use Illuminate\Support\Str; @endphp
@extends('layouts.back-end.app')
@section('title', translate('product_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 text-capitalize">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/inhouse-product-list.png')}}" alt="">
                {{translate('vendor_product_list')}}
                <span class="badge badge-soft-dark radius-50 fz-14 ml-1">{{ $products->total() }}</span>
            </h2>
        </div>
        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-3">
                        <div class="row g-2 justify-content-between align-items-center">
                            <div class="col-lg-4">
                                <h5 class="m-0">{{translate('product_table')}}
                                    <span class="badge badge-soft-dark ml-2">{{$products->total()}}</span>
                                </h5>
                            </div>
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
                                               placeholder="{{translate('search_Product_Name')}}"
                                               aria-label="Search orders"
                                               value="{{ request('searchValue') }}">
                                        <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                               style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('product Name')}}</th>
                                <th class="text-right">{{translate('product Type')}}</th>
                                <th class="text-right">{{translate('purchase_price')}}</th>
                                <th class="text-right">{{translate('selling_price')}}</th>
                                <th class="text-center">{{translate('show_as_featured')}}</th>
                                <th class="text-center">{{translate('active_status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key=>$product)
                                <tr>
                                    <th scope="row">{{$products->firstItem()+$key}}</th>
                                    <td>
                                        <a href="{{route('admin.products.view',['addedBy'=>($product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$product['id']])}}"
                                           class="media align-items-center gap-2">
                                            <img src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'backend-product') }}"
                                                 class="avatar border" alt="">
                                            <span class="media-body title-color hover-c1">
                                            {{Str::limit($product['name'],20)}}
                                        </span>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{translate(str_replace('_',' ',$product['product_type']))}}
                                    </td>
                                    <td class="text-right">
                                        {{setCurrencySymbol(amount:usdToDefaultCurrency(amount: $product['purchase_price']))}}
                                    </td>
                                    <td class="text-right">
                                        {{setCurrencySymbol(amount:usdToDefaultCurrency(amount: $product['unit_price']))}}
                                    </td>
                                    <td class="text-center">

                                        @php($product_name = str_replace("'",'`',$product['name']))
                                        <form action="{{route('admin.products.featured-status')}}" method="post"
                                              id="product-featured{{$product['id']}}-form"
                                              data-from="featured-product-status">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$product['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                       id="product-featured{{$product['id']}}" name="status" value="1"
                                                       {{ $product['featured'] == 1 ? 'checked':'' }}
                                                       data-modal-id = "toggle-status-modal"
                                                       data-toggle-id = "product-featured{{$product['id']}}"
                                                       data-on-image = "product-status-on.png"
                                                       data-off-image = "product-status-off.png"
                                                       data-on-title = "{{translate('Want_to_Add').' '.$product_name.' '.translate('to_the_featured_section').'?'}}"
                                                       data-off-title = "{{translate('Want_to_Remove').' '.$product_name.' '.translate('to_the_featured_section').'?'}}"
                                                       data-on-message = "<p>{{translate('if_enabled_this_product_will_be_shown_in_the_featured_product_on_the_website_and_customer_app')}}</p>"
                                                       data-off-message = "<p>{{translate('if_disabled_this_product_will_be_removed_from_the_featured_product_section_of_the_website_and_customer_app')}}</p>">`)">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>

                                    </td>
                                    <td class="text-center">
                                        <form action="{{route('admin.products.status-update')}}" method="post"
                                              id="product-status{{$product['id']}}-form" data-from="product-status-update">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$product['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                   id="product-status{{$product['id']}}" name="status" value="1"
                                                   {{ $product['status'] == 1 ? 'checked':'' }}
                                                   data-modal-id = "toggle-status-modal"
                                                   data-toggle-id = "product-status{{$product['id']}}"
                                                   data-on-image = "product-status-on.png"
                                                   data-off-image = "product-status-off.png"
                                                   data-on-title = "{{translate('Want_to_Turn_ON').' '.$product_name.' '.translate('status').'?'}}"
                                                   data-off-title = "{{translate('Want_to_Turn_OFF').' '.$product_name.' '.translate('status').'?'}}"
                                                   data-on-message = "<p>{{translate('if_enabled_this_product_will_be_available_on_the_website_and_customer_app')}}</p>"
                                                   data-off-message = "<p>{{translate('if_disabled_this_product_will_be_hidden_from_the_website_and_customer_app')}}</p>">`)">
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
                                               href="{{route('admin.products.view',['addedBy'=>($product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$product['id']])}}">
                                                <i class="tio-invisible"></i>
                                            </a>
                                            <a class="btn btn-outline--primary btn-sm square-btn"
                                               title="{{translate('edit')}}"
                                               href="{{route('admin.products.update',[$product['id']])}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm square-btn delete-data" href="javascript:"
                                               title="{{translate('delete')}}"
                                               data-id="product-{{$product['id']}}">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                        <form action="{{route('admin.products.delete',[$product['id']])}}"
                                              method="post" id="product-{{$product['id']}}">
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
                            {{$products->links()}}
                        </div>
                    </div>
                    @if(count($products)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160"
                                 src="{{dynamicAsset(path: 'public/assets/back-end/svg/illustrations/sorry.svg')}}"
                                 alt="{{translate('image_description')}}">
                            <p class="mb-0">{{translate('no_data_to_show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
