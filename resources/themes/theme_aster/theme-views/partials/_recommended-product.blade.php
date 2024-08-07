<section class="py-3">
    <div class="container">
        <h2 class="text-center mb-3 text-capitalize">{{ translate('recommended_for_you') }}</h2>
        <nav class="d-flex justify-content-center">
            <div class="nav nav-nowrap gap-3 gap-xl-5 nav--tabs hide-scrollbar" id="nav-tab" role="tablist">
                <button class="active text-capitalize" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#featured_product"
                        role="tab" aria-controls="featured_product">{{ translate('featured_products') }}
                </button>
                <button class="text-capitalize" data-bs-toggle="tab" data-bs-target="#best_selling" role="tab"
                        aria-controls="best_selling">{{ translate('best_selling') }}
                </button>
                <button class="text-capitalize" data-bs-toggle="tab" data-bs-target="#latest_product" role="tab"
                        aria-controls="latest_product">{{ translate('latest_products') }}
                </button>
            </div>
        </nav>
        <div class="card mt-3">
            <div class="p-2 p-sm-3">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="featured_product" role="tabpanel" tabindex="0">
                        <div class="d-flex flex-wrap justify-content-end gap-3 mb-3">
                            <a href="{{route('products',['data_from'=>'featured'])}}" class="btn-link text-capitalize">{{ translate('view_all') }}
                                <i class="bi bi-chevron-right text-primary"></i>
                            </a>
                        </div>
                        <div class="auto-col mobile-items-2 gap-2 gap-sm-3 recommended-product-grid minWidth-12rem">
                            @foreach($featuredProductsList as $product)
                                @if($product)
                                    @include('theme-views.partials._product-large-card',['product'=>$product])
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="best_selling" role="tabpanel" tabindex="0">
                        <div class="d-flex flex-wrap justify-content-end gap-3 mb-3">
                            <a href="{{route('products',['data_from'=>'best-selling'])}}" class="btn-link text-capitalize">{{ translate('view_all') }}
                                <i class="bi bi-chevron-right text-primary"></i>
                            </a>
                        </div>
                        <div class="auto-col mobile-items-2 gap-2 gap-sm-3 recommended-product-grid minWidth-12rem">
                            @foreach($bestSellProduct as $singleProduct)
                                @include('theme-views.partials._product-large-card',['product'=>$singleProduct])
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="latest_product" role="tabpanel" tabindex="0">
                        <div class="d-flex flex-wrap justify-content-end gap-3 mb-3">
                            <a href="{{route('products',['data_from'=>'latest'])}}" class="btn-link text-capitalize">{{ translate('view_all') }}
                                <i class="bi bi-chevron-right text-primary"></i>
                            </a>
                        </div>
                        <div class="auto-col mobile-items-2 gap-2 gap-sm-3 recommended-product-grid minWidth-12rem">
                            @foreach($latest_products as $product)
                                @if($product)
                                    @include('theme-views.partials._product-large-card',['product'=>$product])
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
