<div class="modal-body">
    <button class="radius-50 border-0 font-weight-bold text-black-50 position-absolute right-3 top-3 z-index-99" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="row gy-3">
        <div class="col-md-5">
            <div class="d-flex align-items-center justify-content-center active">
                <img class="img-responsive w-100 rounded"
                     src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'backend-product') }}"
                     data-zoom="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'backend-product') }}"
                     alt="{{translate('product_image')}}">
                <div class="cz-image-zoom-pane"></div>
            </div>

            <div class="d-flex flex-column gap-10 fz-14 mt-3">

                <div class="d-flex align-items-center gap-2">
                    <div class="font-weight-bold text-dark">{{ translate('SKU') }}:</div>
                    <div>{{ $product->code }}</div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="font-weight-bold text-dark">{{ translate('categories') }}: </div>
                    <div>{{ $product->category->name ?? translate('not_found') }}</div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="font-weight-bold text-dark">{{ translate('brand') }}:</div>
                    <div>{{ $product->brand->name ?? translate('not_found') }}</div>
                </div>

                @if (count($product->tags) > 0)
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="font-weight-bold text-dark">{{ translate('tag') }}:</div>
                        @foreach ($product->tags as $tag)
                            <div>{{ $tag->tag }},</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-7">
            <div class="details">
                <div class="d-flex flex-wrap gap-3 mb-3">
                    <div class="d-flex gap-2 align-items-center text-success rounded-pill bg-success-light px-2 py-1 stock-status-in-quick-view">
                        <i class="tio-checkmark-circle-outlined"></i>
                        {{translate('in_stock')}}
                    </div>
                </div>
                <h2 class="mb-3 product-title">{{ $product->name }}</h2>

                @if($product->reviews_count > 0)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="tio-star text-warning"></i>
                        <span class="text-muted text-capitalize">({{$product->reviews_count.' '.translate('customer_review')}})</span>
                    </div>
                @endif

                <div class="d-flex flex-wrap align-items-center gap-3 mb-2 text-dark">
                    <h2 class="c1 text-accent price-range-with-discount d-flex gap-2 align-items-center">
                        {!! getPriceRangeWithDiscount(product: $product) !!}
                    </h2>
                </div>
            </div>

            <div class="mt-3">
                <?php
                $cart = false;
                if (session()->has('cart')) {
                    foreach (session()->get('cart') as $key => $cartItem) {
                        if (is_array($cartItem) && $cartItem['id'] == $product['id']) {
                            $cart = $cartItem;
                        }
                    }
                }

                ?>

                <form id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <div class="variant-change">
                        <div class="position-relative mb-4">
                            @if (count(json_decode($product->colors)) > 0)
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <strong class="text-dark">{{translate('color')}}</strong>

                                    <div class="color-select d-flex gap-2 flex-wrap" id="option1">
                                        @foreach (json_decode($product->colors) as $key => $color)
                                            <input class="btn-check action-color-change" type="radio"
                                                   id="{{ $product->id }}-color-{{ $key }}"
                                                   name="color" value="{{ $color }}"
                                                   @if($key == 0) checked @endif autocomplete="off">
                                            <label id="label-{{ $product->id }}-color-{{ $key }}" class="color-ball mb-0 {{ $key== 0 ?'border-add':"" }}"
                                                   style="background: {{ $color }};" for="{{ $product->id }}-color-{{ $key }}"
                                                   data-toggle="tooltip">
                                                <i class="tio-done"></i>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @php
                                $qty = 0;
                                if(!empty($product->variation)){
                                foreach (json_decode($product->variation) as $key => $variation) {
                                        $qty += $variation->qty;
                                    }
                                }
                            @endphp
                        </div>
                        @foreach (json_decode($product->choice_options) as $key => $choice)
                            <div class="d-flex gap-3 flex-wrap align-items-center mb-3">
                                <div class="my-2 w-43px">
                                    <strong class="text-dark">{{ ucfirst($choice->title) }}</strong>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach ($choice->options as $index => $option)
                                        <input class="btn-check" type="radio"
                                               id="{{ $choice->name }}-{{ $option }}"
                                               name="{{ $choice->name }}" value="{{ $option }}"
                                               @if($index == 0) checked @endif autocomplete="off">
                                        <label class="btn btn-sm check-label border-0 mb-0 w-auto pos-check-label"
                                               for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex flex-wrap gap-2 position-relative price-section">
                        <div class="alert alert--message flex-row alert-dismissible fade show pos-alert-message gap-2 d-none" role="alert">
                            <img class="mb-1" src="{{dynamicAsset(path: 'public/assets/back-end/img/warning-icon.png')}}" alt="{{translate('warning')}}">
                            <div class="w-0">
                                <h6>{{translate('warning')}}</h6>
                                <div class="product-stock-message"></div>
                            </div>
                            <a href="javascript:" class="align-items-center close-alert-message" >
                                <i class="tio-clear"></i>
                            </a>
                        </div>
                        <div class="default-quantity-system d-none">
                            <div class="d-flex gap-2 align-items-center mt-3">
                                <strong class="text-dark">{{translate('qty')}}:</strong>
                                <div class="product-quantity d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                    <span class="product-quantity-group">
                                        <button type="button" class="btn-number bg-transparent"
                                                data-type="minus" data-field="quantity"
                                                disabled="disabled">
                                                <i class="tio-remove"></i>
                                        </button>
                                        <input type="text" name="quantity"
                                               class="form-control input-number text-center cart-qty-field"
                                               placeholder="1" value="1" min="1" max="100">
                                        <button type="button" class="btn-number bg-transparent cart-qty-field-plus" data-type="plus"
                                                data-field="quantity">
                                                <i class="tio-add"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="in-cart-quantity-system d--none">
                            <div class="d-flex gap-2 align-items-center mt-3">
                                <strong class="text-dark">{{translate('qty')}}:</strong>
                                <div class="product-quantity d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                    <span class="product-quantity-group">
                                        <button type="button" class="btn-number bg-transparent in-cart-quantity-minus action-get-variant-for-already-in-cart" data-action="minus">
                                                <i class="tio-remove"></i>
                                        </button>
                                        <input type="text" name="quantity_in_cart"
                                               class="form-control text-center in-cart-quantity-field"
                                               placeholder="1" value="1" min="1" max="100">
                                        <button type="button" class="btn-number bg-transparent in-cart-quantity-plus action-get-variant-for-already-in-cart" data-action="plus">
                                                <i class="tio-add"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-1 mt-3 title-color">
                            <div class="product-description-label text-dark font-weight-bold">{{translate('total_Price')}}:</div>
                            <div class="product-price c1">
                                <strong> {{getCurrencySymbol()}}</strong>
                                <strong class="set-price"></strong>
                                <span class="text-muted fz-10">
                                    ( {{ ($product->tax_model == 'include' ? '':'+').' '.translate('tax') }} <span class="set-product-tax"></span>)</span>
                            </div>
                        </div>
                        <div class="align-self-center">
                            @if ($product->discount > 0)
                                <div class="d-flex gap-1 align-items-center text-primary rounded-pill bg-primary-light px-2 py-1">
                                    @if ($product->discount_type === "percent")
                                        {{$product->discount.' % '.translate('OFF')}}
                                    @else
                                        {{ translate('save').' '.getCurrencySymbol()}}<span class="set-discount-amount"></span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn--primary btn-block quick-view-modal-add-cart-button action-add-to-cart" type="button">
                            {{translate('add_to_cart')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
