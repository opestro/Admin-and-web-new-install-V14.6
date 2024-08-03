@extends('layouts.back-end.app-seller')

@section('title', $product->name . ' '.translate('barcode').' ' . date('Y/m/d'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/barcode.css') }}"/>
@endpush

@section('content')
    <div class="row m-2 show-div pt-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <div class="mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                    <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/inhouse-product-list.png') }}" alt="">
                    {{ translate('generate_Barcode') }}
                </h2>
            </div>

            <div class="card">
                <div class="py-4">
                    <div class="table-responsive">
                        <form action="{{ url()->current() }}" method="GET">
                            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ translate('code') }}</th>
                                    <th>{{ translate('name') }}</th>
                                    <th>{{ translate('quantity') }}</th>
                                    <th class="text-center">{{ translate('action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>
                                        @if ($product->code)
                                            <span>{{$product->code}}</span>
                                        @else
                                            <a class="title-color hover-c1"
                                               href="{{route('vendor.products.edit', [$product['id']]) }}">
                                                {{ translate('update_your_product_code') }}
                                            </a>
                                        @endif
                                    </th>
                                    <th>{{ Str::limit($product->name, 20) }}</th>
                                    <th>
                                        <input id="limit" class="form-control" type="number" name="limit" min="1"
                                               value="{{ request('limit') ?? 4 }}">
                                        <span class="text-danger mt-1 d-block">
                                            {{ translate('maximum_quantity_270') }}
                                        </span>
                                    </th>

                                    <th>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-outline-info" type="submit">
                                                {{ translate('generate_barcode') }}
                                            </button>
                                            <a href="{{ route('vendor.products.barcode', [$product['id']]) }}"
                                               class="btn btn-outline-danger">
                                                {{ translate('reset') }}
                                            </a>
                                            <button type="button" id="print_bar" data-value="print-area"
                                                    class="btn btn-outline--primary action-print-invoice">
                                                {{ translate('print') }}
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-5 p-4">
            <h1 class="style-one-br show-div2">
                {{ translate("this_page_is_for_A4_size_page_printer_so_it_will_not_be_visible_in_smaller_devices.") }}
            </h1>
        </div>
    </div>

    <div id="print-area" class="show-div pb-5">
        @foreach($barcodes as $key => $array)
            <div class="barcode-a4">
                @for ($i = 0; $i < count($array); $i++)
                    <div class="item style24">
                        <span class="barcode_site text-capitalize">
                            {{ getWebConfig(name: 'company_name') }}
                        </span>
                        <span class="barcode_name text-capitalize">
                            {{ Str::limit($product->name, 20) }}
                        </span>
                        <div class="barcode_price text-capitalize">
                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->unit_price), currencyCode: getCurrencyCode()) }}
                        </div>

                        @if ($product->code !== null)
                            <div class="barcode_image d-flex justify-content-center">
                                {!! DNS1D::getBarcodeHTML($product->code, 'C128') !!}
                            </div>
                            <div class="barcode_code text-capitalize">
                                {{ translate('code') }} : {{ $product->code }}
                            </div>
                        @else
                            <p class="text-danger">
                                {{ translate('please_update_product_code') }}
                            </p>
                        @endif
                    </div>
                @endfor
            </div>
        @endforeach
    </div>
@endsection
