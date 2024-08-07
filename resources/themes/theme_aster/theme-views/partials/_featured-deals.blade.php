<section class="bg-primary-light">
    <div class="container">
        <div class="">
            <div class="py-4">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-3 mb-sm-4 text-capitalize">
                    <h2>{{ translate('featured_deals') }}</h2>
                    <div class="swiper-nav d-flex gap-2 align-items-center">
                        <div class="swiper-button-prev top-rated-nav-prev position-static rounded-10"></div>
                        <div class="swiper-button-next top-rated-nav-next position-static rounded-10"></div>
                    </div>
                </div>
                <div class="swiper-container">
                    <div class="position-relative">
                        <div class="swiper" data-swiper-loop="true" data-swiper-margin="20"
                             data-swiper-pagination-el="null" data-swiper-navigation-next=".top-rated-nav-next"
                             data-swiper-navigation-prev=".top-rated-nav-prev"
                             data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "340": {"slidesPerView": "2"}, "992": {"slidesPerView": "3"}, "1200": {"slidesPerView": "4"}, "1400": {"slidesPerView": "5"}}'>
                            <div class="swiper-wrapper swiper-wrapper-rtl">
                                @foreach($featured_deals as $key=>$product)
                                    <div class="swiper-slide mx-w300">
                                        @include('theme-views.partials._product-large-card',['product'=>$product])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
