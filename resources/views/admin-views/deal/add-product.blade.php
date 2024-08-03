@extends('layouts.back-end.app')

@section('title', translate('deal_Product'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/inhouse-product-list.png')}}" class="mb-1 mr-1" alt="">
                {{translate('add_new_product')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 text-capitalize">{{$deal['title']}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.deal.add-product',[$deal['id']])}}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <label for="name" class="title-color">{{ translate('products')}}</label>
                                        <div class="dropdown select-product-search w-100">
                                            <input type="text" class="product_id" name="product_id" hidden>
                                            <button class="form-control text-start dropdown-toggle text-capitalize select-product-button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{translate('select_product')}}

                                            </button>
                                            <div class="dropdown-menu w-100 px-2">
                                                <div class="search-form mb-3">
                                                    <button type="button" class="btn"><i class="tio-search"></i></button>
                                                    <input type="text" class="js-form-search form-control search-bar-input search-product"
                                                           placeholder="{{translate('search menu').'...'}}">
                                                </div>
                                                <div
                                                    class="d-flex flex-column gap-3 max-h-200 overflow-y-auto overflow-x-hidden search-result-box">
                                                    @include('admin-views.partials._search-product',['products'=>$products])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn--primary px-4">{{ translate('add')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <h5 class="mb-0 text-capitalize">
                            {{ translate('product_table')}}
                            <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $dealProducts->total() }}</span>
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('SL')}}</th>
                                <th>{{ translate('name')}}</th>
                                <th>{{ translate('price')}}</th>
                                <th class="text-center">{{ translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dealProducts as $key => $product)
                                <tr>
                                    <td>{{$dealProducts->firstitem() + $key}}</td>
                                    <td><a href="javascript:" target="_blank"
                                           class="font-weight-semibold title-color hover-c1">{{$product['name']}}</a>
                                    </td>
                                    <td>{{usdToDefaultCurrency(amount: $product['unit_price'])}}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a title="{{ translate ('delete')}}"
                                               class="btn btn-outline-danger btn-sm delete-data-without-form"
                                               data-action="{{route('admin.deal.delete-product')}}" data-id="{{$product['id']}}">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <table>
                            <tfoot>
                            {!! $dealProducts->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/search-product.js')}}"></script>
@endpush
