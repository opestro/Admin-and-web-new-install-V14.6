<section>
    <div class="container">
        <div class="card px-3 px-lg-0 py-4">
            <div class="flexible-grid lg-down-1 gap-30 gap-lg-0">
                <div class="">
                    <div class="flash-deal-countdown text-center">
                        <div class="mb-2 text-primary">
                            <img width="122" src="{{ theme_asset('assets/img/media/flash-deal.svg') }}" loading="lazy" alt="" class="dark-support svg">
                        </div>
                        <div class="d-flex justify-content-center align-items-end gap-2 mb-4">
                            <h2 class="text-primary fw-medium">{{ translate('hurry_up').'!' }}</h2>
                            <div class="text-muted">{{ translate('offer_ends_in').':' }}</div>
                        </div>

                        <div class="countdown-timer d-flex gap-3 gap-sm-4 flex-wrap justify-content-center" data-date="{{ $flashDeal['flashDeal']['end_date'] }}">
                            <div class="days d-flex flex-column gap-2 gap-sm-3"></div>
                            <div class="hours d-flex flex-column gap-2 gap-sm-3"></div>
                            <div class="minutes d-flex flex-column gap-2 gap-sm-3"></div>
                            <div class="seconds d-flex flex-column gap-2 gap-sm-3"></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-container">
                    <div class="mb-2 w-100 px-lg-4">
                        <a href="{{route('flash-deals', ['id' => $flashDeal['flashDeal']['id'] ])}}" class="btn-link text-primary text-capitalize float-end">{{ translate('View_all') }}</a>
                    </div>
                    <div class="auto-item-width position-relative">
                        <div class="swiper flash-deals-swiper" data-swiper-loop="false" data-swiper-items="auto" data-swiper-margin="20" data-swiper-pagination-el="null" data-swiper-navigation-next=".swiper-button-next--flash-deal" data-swiper-navigation-prev=".swiper-button-prev--flash-deal">
                            <div class="swiper-wrapper">
                                @foreach($flashDeal['flashDealProducts'] as $key => $flashDealProduct)
                                    <div class="swiper-slide">
                                        @include('theme-views.partials._product-medium-card',['product' => $flashDealProduct])
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if(count($flashDeal['flashDealProducts']) > 0)
                            <div class="swiper-button-next swiper-button-next--flash-deal"></div>
                            <div class="swiper-button-prev swiper-button-prev--flash-deal"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
