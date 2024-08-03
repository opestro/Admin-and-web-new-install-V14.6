@extends('layouts.back-end.app-seller')
@section('title', translate('product_gallery'))

@section('content')
    <div class="content container-fluid">
        <div>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <h2 class="h1 mb-0">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/all-orders.png')}}" class="mb-1 mr-1" alt="">
                    {{translate('product_gallery')}}
                </h2>
                <span class="badge badge-soft-dark radius-50 fz-14">{{$products->total()}}</span>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row gx-2">
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color" for="store">{{translate('brand')}}</label>
                                <select name="brand_id" class="form-control js-select2-custom product-gallery-filter">
                                    <option value="all">{{translate('all_brand')}}</option>
                                    @foreach($brands as $brand)
                                        <option value="{{$brand['id']}}" {{$brand['id'] == request('brand_id') ? 'selected': ''}}>{{$brand['defaultName']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color" for="store">{{translate('category')}}</label>
                                <select name="category_id" class="form-control js-select2-custom product-gallery-filter">
                                    <option value="all">{{translate('all_category')}}</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category['id']}}" {{$category['id'] == request('category_id') ? 'selected': ''}}>{{$category['defaultName']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 d-flex align-items-center">
                            <form action="{{url()->current()}}">
                                <div class="input-group input-group-merge input-group-custom">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input type="search" name="searchValue" class="form-control"
                                           placeholder="{{translate('search_by_product_name')}}"
                                           aria-label="Search orders" value="{{ request('searchValue') }}">
                                    <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($products as $product)
                        <div class="mb-3 refund-details-card--2 p-3">
                            <div class="d-flex gap-3 flex-wrap flex-md-nowrap justify-content-center justify-content-md-start">
                                <div class="media flex-nowrap flex-column flex-sm-row gap-3">
                                    <div class="d-flex flex-column align-items-center __min-w-165px">
                                        <a class="aspect-1 float-left overflow-hidden"
                                           href="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'. $product['thumbnail'],type: 'backend-product') }}"
                                           data-lightbox="product-gallery-{{ $product['id'] }}">
                                            <img class="avatar avatar-170 rounded-0"
                                                 src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'. $product['thumbnail'],type: 'backend-product') }}"
                                                 alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="row gy-2 flex-grow-1">
                                    <div class="col-12">
                                        <div class="d-md-flex justify-content-md-between">
                                            <h4 class="text-capitalize">{{$product['name']}}</h4>
                                            <a class="btn btn--primary btn-sm" href="{{route('vendor.products.update',['id'=>$product['id'],'product-gallery'=>1]) }}">
                                                {{translate('use_this_product_info')}}
                                            </a>
                                        </div>
                                    </div>
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
                                    @if ($product->product_type == 'physical' && count(json_decode($product->choice_options)) >0 || count(json_decode($product->colors)) >0 )
                                        <div class="col-sm-6 col-xl-4">
                                        <h4 class="mb-3">{{ translate('available_variations') }}</h4>
                                        <div class="pair-list">
                                            @if (json_decode($product->choice_options) != null)
                                                @foreach (json_decode($product->choice_options) as $key => $value)
                                                    <div>
                                                        @if (array_filter($value->options) != null)
                                                            <span class="key text-nowrap">{{ translate($value->title) }}</span>
                                                            <span>:</span>
                                                            <span class="value">
                                                    @foreach ($value->options as $index => $option)
                                                                    {{ $option }}
                                                                    @if ($index === array_key_last(($value->options)))
                                                                        @break
                                                                    @endif
                                                                    ,
                                                                @endforeach
                                                </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if (isset($product['colorsName']))
                                                <div>
                                                    <span class="key text-nowrap">{{ translate('color') }}</span>
                                                    <span>:</span>
                                                    <span class="value">
                                                        @foreach ($product['colorsName'] as $key => $color)
                                                                    {{ $color }}
                                                            @if ($key === array_key_last($product['colorsName']))
                                                                @break
                                                            @endif
                                                            ,
                                                        @endforeach
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @if(count($product->tags)>0)
                                        <div class="col-sm-6 col-xl-4">
                                            <h4 class="mb-3">{{ translate('tags') }}</h4>
                                            <div class="pair-list"><div>
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
                            <div class="mt-3">
                                <div class="view--more rich-editor-html-content">
                                    <label class="text-gulf-blue font-weight-bold">{{ translate('description').' : ' }}</label>
                                    {!! $product['details'] !!}
                                    <button class="no-gutter expandable-btn d-none">
                                        <span class="more">{{translate('view_more')}}</span>
                                        <span class="less d-none">{{translate('view_less')}}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if(count($products)==0)
                    @include('layouts.back-end._empty-state',['text'=>'no_product_found'],['image'=>'default'])
                @endif
            </div>
            <div class=" mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {{ $products->links() }}
                </div>
            </div>

        </div>
    </div>
    <span id="get-product-gallery-route" data-action="{{route('vendor.products.product-gallery')}}" data-brand-id="{{request('brand_id')}}" data-category-id="{{request('category_id')}}" data-vendor-id="{{request('vendor_id')}}">
@endsection
