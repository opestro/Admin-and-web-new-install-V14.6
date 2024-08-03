@extends('layouts.back-end.app')

@section('title', translate('product_Preview'))

@section('content')
    <div class="content container-fluid text-start">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-10 mb-3">
            <div class="">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{ asset('public/assets/back-end/img/inhouse-product-list.png') }}" alt="">
                    {{ translate('product_details') }}
                </h2>
            </div>
        </div>
        <div class="card card-top-bg-element">
            <div class="card-body">
                <div>
                    <div class="media flex-nowrap flex-column flex-sm-row gap-3 flex-grow-1">
                        <div class="d-flex flex-column align-items-center __min-w-165px">
                            <a class="aspect-1 float-left overflow-hidden"
                               href="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'. $product['thumbnail'],type: 'backend-product') }}"
                               data-lightbox="product-gallery-{{ $product['id'] }}">
                                <img class="avatar avatar-170 rounded-0"
                                     src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'. $product['thumbnail'],type: 'backend-product') }}"
                                     alt="">
                            </a>
                            @if ($productActive)
                                <a href="{{ route('product', $product['slug']) }}"
                                   class="btn btn-outline--primary mr-1 mt-2" target="_blank">
                                    <i class="tio-globe"></i>
                                    {{ translate('view_live') }}
                                </a>
                            @endif
                        </div>
                        @if($product->digital_file_ready && file_exists(base_path('storage/app/public/product/digital-product/'.$product->digital_file_ready)))
                            <a href="{{ dynamicAsset(path: 'storage/app/public/product/digital-product/'.$product->digital_file_ready) }}"
                               class="btn btn-outline--primary mr-1" title="{{translate('Download')}}">
                                <i class="tio-download"></i>
                                {{ translate('download') }}
                            </a>
                        @endif
                        <div class="d-block flex-grow-1 w-max-md-100">
                            @php($languages = getWebConfig(name:'pnc_language'))
                            @php($defaultLanguage = 'en')
                            @php($defaultLanguage = $languages[0])
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <ul class="nav nav-tabs w-fit-content mb-2">
                                    @foreach($languages as $language)
                                        <li class="nav-item text-capitalize">
                                            <a class="nav-link lang-link {{$language == $defaultLanguage? 'active':''}}"
                                            href="javascript:"
                                            id="{{$language}}-link">{{ getLanguageName($language).'('.strtoupper($language).')' }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                @if($product['added_by'] == 'seller' && ($product['request_status'] == 0 || $product['request_status'] == 1))
                                    <div class="d-flex justify-content-sm-end flex-wrap gap-2 pb-4">
                                        <div>
                                            <button class="btn btn-danger p-2 px-3" data-toggle="modal" data-target="#publishNoteModal">
                                                {{ translate('reject') }}
                                            </button>
                                        </div>
                                        <div>
                                            @if($product['request_status'] == 0)
                                                <button class="btn btn-success p-2 px-3 update-status"
                                                   data-id="{{ $product['id'] }}"
                                                   data-redirect-route="{{route('admin.products.list',['vendor', 'status' => $product['request_status']])}}"
                                                   data-message ="{{translate('want_to_approve_this_product_request_request').'?'}}" data-status="1">
                                                    {{ translate('approve') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if($product['added_by'] == 'seller' && ($product['request_status'] == 2))
                                    <div class="d-flex justify-content-sm-end flex-wrap gap-2 pb-4">
                                        <div>
                                            <span>{{translate('status').' : '}}</span>
                                            <span class="__badge badge badge-soft-danger">{{translate('rejected')}}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex flex-wrap align-items-center flex-sm-nowrap justify-content-between gap-3 min-h-50">
                                <div class="d-flex flex-wrap gap-2 align-items-center">

                                    @if ($product->product_type == 'physical' && !empty($product->color_image) && count(json_decode($product->color_image))>0)
                                        @foreach (json_decode($product->color_image) as $colorImageKey => $photo)
                                            @if( $colorImageKey < 3 || count(json_decode($product->color_image, true)) < 5)
                                                <a class="aspect-1 float-left overflow-hidden img_row{{$colorImageKey}}"
                                                   href="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'backend-product') }}"
                                                   data-lightbox="product-gallery-{{ $product['id'] }}">
                                                    <img width="50" class="img-fit max-50" alt=""
                                                         src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'backend-product') }}">
                                                </a>
                                            @elseif($colorImageKey == 3)
                                                <a class="aspect-1 float-left overflow-hidden d-block border rounded-lg position-relative img_row{{$colorImageKey}}"
                                                   href="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'backend-product') }}"
                                                   data-lightbox="product-gallery-{{ $product['id'] }}">
                                                    <img width="50" class="img-fit max-50" alt=""
                                                         src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'backend-product') }}">
                                                    <div class="extra-images">
                                                        <span class="extra-image-count">
                                                            +{{ (count(json_decode($product->color_image, true)) - $colorImageKey) + 1 }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @else
                                                <a class="aspect-1 float-left overflow-hidden d-none img_row{{$colorImageKey}}"
                                                   href="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'backend-product') }}"
                                                   data-lightbox="product-gallery-{{ $product['id'] }}">
                                                    <img width="50" class="img-fit max-50" alt=""
                                                         src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'backend-product') }}">
                                                </a>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach (json_decode($product->images) as $imageKey => $photo)
                                            @if($imageKey < 3 || count(json_decode($product->images, true)) < 5)
                                                <a class="aspect-1 float-left overflow-hidden"
                                                   href="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'backend-product') }}"
                                                   data-lightbox="product-gallery-{{ $product['id'] }}">
                                                    <img width="50"  class="img-fit max-50" alt=""
                                                         src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'backend-product') }}">
                                                </a>
                                            @elseif($imageKey == 3)
                                                <a class="aspect-1 float-left overflow-hidden d-block border rounded-lg position-relative"
                                                   href="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'backend-product') }}"
                                                   data-lightbox="product-gallery-{{ $product['id'] }}">
                                                    <img width="50"  class="img-fit max-50" alt=""
                                                         src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'backend-product') }}">
                                                    <div class="extra-images">
                                                        <span class="extra-image-count">
                                                            +{{ (count(json_decode($product->images, true)) - $imageKey) + 1 }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @else
                                                <a class="aspect-1 float-left overflow-hidden d-none"
                                                   href="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'backend-product') }}"
                                                   data-lightbox="product-gallery-{{ $product['id'] }}">
                                                    <img width="50"  class="img-fit max-50" alt=""
                                                         src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'backend-product') }}">
                                                </a>
                                            @endif
                                        @endforeach
                                    @endif

                                </div>
                                <div class="d-flex gap-3 flex-nowrap lh-1 badge badge--primary-light justify-content-sm-end height-30px align-items-center">
                                    <span class="text-dark">
                                        {{ count($product->orderDetails) }} {{ translate('orders') }}
                                    </span>
                                    <span class="border-left py-2"></span>
                                    <div class="review-hover position-relative cursor-pointer d-flex gap-2 align-items-center">
                                            <i class="tio-star"></i>
                                        <span>
                                            {{ count($product->rating)>0 ? number_format($product->rating[0]->average, 2, '.', ' '):0 }}
                                        </span>
                                        <div class="review-details-popup">
                                            <h6 class="mb-2">{{ translate('rating') }}</h6>
                                            <div class="">
                                                <ul class="list-unstyled list-unstyled-py-2 mb-0">
                                                    @php($total = $product->reviews->count())

                                                    <li class="d-flex align-items-center font-size-sm">
                                                        @php($five = getRatingCount($product['id'], 5))
                                                        <span class="{{ Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3' }}">
                                                        {{ translate('5') }} {{ translate('star') }}
                                                    </span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar" style="width: {{ $total == 0 ? 0 : ($five/$total)*100 }}%;"
                                                                    aria-valuenow="{{ $total == 0 ? 0 : ($five/$total)*100 }}"
                                                                    aria-valuemin="0" aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <span
                                                            class="{{ Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3' }}">{{ $five }}</span>
                                                    </li>

                                                    <li class="d-flex align-items-center font-size-sm">
                                                        @php($four=getRatingCount($product['id'],4))
                                                        <span
                                                            class="{{ Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3' }}">{{ translate('4') }} {{ translate('star') }}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar"
                                                                    style="width: {{ $total == 0 ? 0 : ($four/$total)*100}}%;"
                                                                    aria-valuenow="{{ $total == 0 ? 0 : ($four/$total)*100}}"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span
                                                            class="{{ Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3' }}">{{ $four }}</span>
                                                    </li>

                                                    <li class="d-flex align-items-center font-size-sm">
                                                        @php($three=getRatingCount($product['id'],3))
                                                        <span
                                                            class="{{ Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{ translate('3') }} {{ translate('star') }}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar"
                                                                    style="width: {{ $total == 0 ? 0 : ($three/$total)*100 }}%;"
                                                                    aria-valuenow="{{ $total == 0 ? 0 : ($three/$total)*100 }}"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span
                                                            class="{{ Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{ $three }}</span>
                                                    </li>

                                                    <li class="d-flex align-items-center font-size-sm">
                                                        @php($two=getRatingCount($product['id'],2))
                                                        <span
                                                            class="{{ Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{ translate('2') }} {{ translate('star') }}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar"
                                                                    style="width: {{ $total == 0 ? 0 : ($two/$total)*100}}%;"
                                                                    aria-valuenow="{{ $total == 0 ? 0 : ($two/$total)*100}}"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span
                                                            class="{{ Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{ $two }}</span>
                                                    </li>

                                                    <li class="d-flex align-items-center font-size-sm">
                                                        @php($one=getRatingCount($product['id'],1))
                                                        <span
                                                            class="{{ Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{ translate('1') }} {{ translate('star') }}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar"
                                                                    style="width: {{ $total == 0 ? 0 : ($one/$total)*100}}%;"
                                                                    aria-valuenow="{{ $total == 0 ? 0 : ($one/$total)*100}}"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span
                                                            class="{{ Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{ $one }}</span>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="border-left py-2"></span>
                                    <span class="text-dark">
                                        {{ $product->reviews->whereNotNull('comment')->count() }} {{ translate('reviews') }}
                                    </span>
                                </div>
                            </div>
                            <div class="d-block mt-2">
                                @foreach($languages as $language)
                                        <?php
                                        if (count($product['translations'])) {
                                            $translate = [];
                                            foreach ($product['translations'] as $translation) {
                                                if ($translation->locale == $language && $translation->key == "name") {
                                                    $translate[$language]['name'] = $translation->value;
                                                }
                                                if ($translation->locale == $language && $translation->key == "description") {
                                                    $translate[$language]['description'] = $translation->value;
                                                }
                                            }
                                        }
                                        ?>
                                    <div class="{{ $language != 'en'? 'd-none':''}} lang-form" id="{{ $language}}-form">
                                        <div class="d-flex">
                                            <h2 class="mb-2 pb-1 text-gulf-blue">{{ $translate[$language]['name']??$product['name']}}</h2>
                                            <a class="btn btn-outline--primary btn-sm square-btn mx-2 w-auto h-25"
                                               title="{{ translate('edit') }}"
                                               href="{{ route('admin.products.update', [$product['id']]) }}">
                                                <i class="tio-edit"></i>
                                            </a>
                                        </div>
                                        <div class="">
                                            <label class="text-gulf-blue font-weight-bold">{{ translate('description').' : ' }}</label>
                                            <div class="rich-editor-html-content">
                                                {!! $translate[$language]['description'] ?? $product['details'] !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-3 flex-wrap">
                    <div class="border p-3 mobile-w-100 w-170">
                        <div class="d-flex flex-column mb-1">
                            <h6 class="font-weight-normal text-capitalize">{{ translate('total_sold') }} :</h6>
                            <h3 class="text-primary fs-18">{{ $product['qtySum'] }}</h3>
                        </div>
                        <div class="d-flex flex-column">
                            <h6 class="font-weight-normal text-capitalize">{{ translate('total_sold_amount') }} :</h6>
                            <h3 class="text-primary fs-18">
                                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: ($product['priceSum'] - $product['discountSum']))) }}
                            </h3>
                        </div>
                    </div>

                    <div class="row gy-3 flex-grow-1">
                        <div class="col-sm-6 col-xl-4">
                            <h4 class="mb-3 text-capitalize">{{ translate('general_information') }}</h4>

                            <div class="pair-list">
                                <div>
                                    <span class="key text-nowrap">{{ translate('brand') }}</span>
                                    <span>:</span>
                                    <span class="value">
                                        {{isset($product->brand) ? $product->brand->default_name : translate('brand_not_found') }}
                                    </span>
                                </div>

                                <div>
                                    <span class="key text-nowrap">{{ translate('category') }}</span>
                                    <span>:</span>
                                    <span class="value">
                                        {{isset($product->category) ? $product->category->default_name : translate('category_not_found') }}
                                    </span>
                                </div>

                                <div>
                                    <span class="key text-nowrap text-capitalize">{{ translate('product_type') }}</span>
                                    <span>:</span>
                                    <span class="value">{{ translate($product->product_type) }}</span>
                                </div>
                                @if($product->product_type == 'physical')
                                    <div>
                                        <span class="key text-nowrap text-capitalize">{{ translate('product_unit') }}</span>
                                        <span>:</span>
                                        <span class="value">{{ $product['unit']}}</span>
                                    </div>
                                    <div>
                                        <span class="key text-nowrap">{{ translate('current_Stock') }}</span>
                                        <span>:</span>
                                        <span class="value">{{ $product->current_stock}}</span>
                                    </div>
                                @endif
                                <div>
                                    <span class="key text-nowrap">{{ translate('product_SKU') }}</span>
                                    <span>:</span>
                                    <span class="value">{{ $product->code}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-4">
                            <h4 class="mb-3 text-capitalize">{{ translate('price_information') }}</h4>

                            <div class="pair-list">
                                <div>
                                    <span class="key text-nowrap text-capitalize">{{ translate('unit_price') }}</span>
                                    <span>:</span>
                                    <span class="value">
                                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->unit_price), currencyCode: getCurrencyCode()) }}
                                    </span>
                                </div>

                                <div>
                                    <span class="key text-nowrap">{{ translate('tax') }}</span>
                                    <span>:</span>
                                    @if ($product->tax_type =='percent')
                                        <span class="value">
                                            {{ $product->tax}}% ({{ $product->tax_model}})
                                        </span>
                                    @else
                                        <span class="value">
                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->tax)) }} ({{ $product->tax_model }})
                                        </span>
                                    @endif
                                </div>
                                @if($product->product_type == 'physical')
                                    <div>
                                        <span class="key text-nowrap text-capitalize">{{ translate('shipping_cost') }}</span>
                                        <span>:</span>
                                        <span class="value">
                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->shipping_cost)) }}
                                            @if ($product->multiply_qty == 1)
                                                ({{ translate('multiply_with_quantity') }})
                                            @endif
                                        </span>
                                    </div>
                                @endif
                                @if($product->discount > 0)
                                    <div>
                                        <span class="key text-nowrap">{{ translate('discount') }}</span>
                                        <span>:</span>
                                        @if ($product->discount_type == 'percent')
                                            <span class="value">{{ $product->discount }}%</span>
                                        @else
                                            <span class="value">
                                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->discount), currencyCode: getCurrencyCode()) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if(count($product->tags)>0)
                            <div class="col-sm-6 col-xl-4">
                                <h4 class="mb-3">{{ translate('tags') }}</h4>
                                <div class="pair-list">
                                    <div>
                                        <span class="value">
                                            @foreach ($product->tags as $key=>$tag)
                                                {{ $tag['tag'] }}
                                                @if ($key === (count($product->tags)-1))
                                                    @break
                                                @endif
                                                ,
                                            @endforeach
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-2 mt-3">
            @if(!empty($product['variation']) && count(json_decode($product['variation'])) >0)
            <div class="col-md-12">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive datatable-custom">
                            <table
                                class="table table-borderless table-nowrap table-align-middle card-table w-100 text-start">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th class="text-center">{{ translate('SKU') }}</th>
                                    <th class="text-center text-capitalize">{{ translate('variation_wise_price') }}</th>
                                    <th class="text-center">{{ translate('stock') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach(json_decode($product['variation']) as $key=>$value)
                                        <tr>
                                            <td class="text-center">
                                                <span class="py-1">{{$value->sku}}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="py-1">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $value->price), currencyCode: getCurrencyCode())}}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="py-1">{{($value->qty)}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg--primary--light">
                        <h5 class="card-title text-capitalize">{{translate('product_SEO_&_meta_data')}}</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <h6 class="mb-3 text-capitalize">
                                {{$product['meta_title'] ?? translate('meta_title_not_found').' '.'!'}}
                            </h6>
                        </div>
                        <p class="text-capitalize">
                            {{$product['meta_description'] ?? translate('meta_description_not_found').' '.'!'}}
                        </p>
                        @if($product['meta_image'])
                            <div class="d-flex flex-wrap gap-2">
                                <a class="aspect-1 float-left overflow-hidden"
                                   href="{{ getValidImage(path: 'storage/app/public/product/meta/'.$product['meta_image'],type: 'backend-basic') }}"
                                   data-lightbox="meta-thumbnail">
                                    <img class="max-width-100px"
                                         src="{{ getValidImage(path: 'storage/app/public/product/meta/'.$product['meta_image'],type: 'backend-basic') }}" alt="{{translate('meta_image')}}">
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg--primary--light">
                        <h5 class="card-title text-capitalize">{{translate('product_video')}}</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <h6 class="mb-3 text-capitalize">
                                {{$product['video_provider'].' '.translate('video_link')}}
                            </h6>
                        </div>
                        @if($product['video_url'])
                            <a href="{{ (str_contains($product->video_url, "https://") || str_contains($product->video_url, "http://")) ? $product['video_url'] : "javascript:"}}" target="_blank"
                               class="text-primary {{(str_contains($product->video_url, "https://") || str_contains($product->video_url, "http://"))?'' : 'cursor-default' }}">
                                {{$product['video_url']}}
                            </a>
                        @else
                            <span>{{ translate('no_data_to_show').' '.'!'}}</span>
                        @endif
                    </div>
                </div>
            </div>
            @if ($product->denied_note && $product['request_status'] == 2)
                <div class="col-md-12">
                    <div class="card h-100">
                        <div class="card-header bg--primary--light">
                            <h5 class="card-title text-capitalize">{{translate('reject_reason')}}</h5>
                        </div>
                        <div class="card-body">
                            <div>
                                {{ $product->denied_note}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="card mt-3">
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{ translate('SL') }}</th>
                        <th>{{ translate('reviewer') }}</th>
                        <th>{{ translate('rating') }}</th>
                        <th>{{ translate('review') }}</th>
                        <th>{{ translate('date') }}</th>
                        <th class="text-center">{{ translate('action') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($reviews as $key=>$review)
                        @if(isset($review->customer))
                            <tr>
                                <td>{{ $reviews->firstItem()+$key}}</td>
                                <td>
                                    <a class="d-flex align-items-center"
                                       href="{{ route('admin.customer.view',[$review['customer_id']]) }}">
                                        <div class="avatar rounded">
                                            <img class="avatar-img"
                                                 src="{{ getValidImage(path: 'storage/app/public/profile/'.$review->customer->image,type: 'backend-profile') }}"
                                                 alt="">
                                        </div>
                                        <div class="{{ Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                            <span class="d-block h5 text-hover-primary mb-0">
                                                {{ $review->customer['f_name']." ".$review->customer['l_name']}}
                                                <i class="tio-verified text-primary" data-toggle="tooltip"
                                                   data-placement="top" title="Verified Customer"></i>
                                            </span>
                                            <span class="d-block font-size-sm text-body">
                                                {{ $review->customer->email ?? "" }}
                                            </span>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 text-primary">
                                        <i class="tio-star"></i>
                                        <span>{{ $review->rating }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-wrap max-w-400 min-w-200">
                                        <p>
                                            {{ $review['comment']}}
                                        </p>
                                        @if(json_decode($review->attachment))
                                            @foreach (json_decode($review->attachment) as $img)
                                                <a class="aspect-1 float-left overflow-hidden"
                                                   href="{{ getValidImage(path: 'storage/app/public/review/'.$img,type: 'backend-basic') }}"
                                                   data-lightbox="review-gallery{{ $review['id'] }}" >
                                                    <img class="p-2" width="60" height="60"
                                                         src="{{ getValidImage(path: 'storage/app/public/review/'.$img,type: 'backend-basic') }}" alt="{{translate('review_image')}}">
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    {{date('d M Y H:i:s',strtotime($review['updated_at'])) }}
                                </td>
                                <td>
                                    <form
                                        action="{{ route('admin.reviews.status', [$review['id'], $review->status ? 0 : 1]) }}"
                                        method="get" id="reviews-status{{ $review['id']}}-form">
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input toggle-switch-message"
                                                   name="status"
                                                   id="reviews-status{{ $review['id'] }}" value="1"
                                                   {{ $review['status'] == 1 ? 'checked' : '' }}
                                                   data-modal-id="toggle-status-modal"
                                                   data-toggle-id="reviews-status{{ $review['id'] }}"
                                                   data-on-image="customer-reviews-on.png"
                                                   data-off-image="customer-reviews-off.png"
                                                   data-on-title="{{ translate('Want_to_Turn_ON_Customer_Reviews') }}"
                                                   data-off-title="{{ translate('Want_to_Turn_OFF_Customer_Reviews') }}"
                                                   data-on-message="<p>{{ translate('if_enabled_anyone_can_see_this_review_on_the_user_website_and_customer_app') }}</p>"
                                                   data-off-message="<p>{{ translate('if_disabled_this_review_will_be_hidden_from_the_user_website_and_customer_app') }}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {!! $reviews->links() !!}
                </div>
            </div>

            @if(count($reviews)==0)
                @include('layouts.back-end._empty-state',['text'=>'no_review_found'],['image'=>'default'])
            @endif
        </div>
    </div>

    <div class="modal fade" id="publishNoteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('rejected_note') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-group" action="{{ route('admin.products.deny', ['id'=>$product['id']]) }}"
                      method="post" id="product-status-denied">
                    @csrf
                    <div class="modal-body">
                        <textarea class="form-control text-area-max-min" name="denied_note" rows="3"></textarea>
                        <span id="denied-note-word-count">{{translate('0/100')}}</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('close') }}
                        </button>
                        <button type="button" class="btn btn--primary form-submit"
                                data-redirect-route="{{route('admin.products.list',['vendor','status' => $product['request_status']])}}"
                                data-form-id="product-status-denied">{{ translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <span id="get-update-status-route" data-action="{{ route('admin.products.approve-status')}}"></span>
@endsection
@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/product-view.js') }}"></script>
@endpush
