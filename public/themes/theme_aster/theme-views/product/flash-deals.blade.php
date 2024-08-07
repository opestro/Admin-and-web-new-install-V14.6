@extends('theme-views.layouts.app')

@section('title',translate('flash_Deal_Products').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3">
        <section>
            <div class="container">
                <div class=" lg-down-1 gap-3 wid width--16rem">
                    <div class="">
                        <div class="d-flex flex-wrap flex-lg-nowrap align-items-start justify-content-between gap-3 mb-2">
                            <div class="flex-middle gap-3"></div>
                            <div class="d-flex gap-3 mb-2 mb-lg-0">
                                <ul class="product-view-option option-select-btn gap-3">
                                    <li>
                                        <label>
                                            <input type="radio" name="product_view" value="grid-view" hidden="" checked="">
                                            <span class="py-2 d-flex align-items-center gap-2"><i class="bi bi-grid-fill"></i> {{ translate('Grid_View') }}</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="product_view" value="list-view" hidden="">
                                            <span class="py-2 d-flex align-items-center gap-2"><i class="bi bi-list-ul"></i> {{ translate('List_View') }}</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class=" d-flex gap-4 flex-column flex-sm-row align-items-center flex-wrap px-4 pt-4 pb-3 pb-sm-0">
                                <div class="flash-deal-countdown text-center ">
                                    <div class="mb-2 text-primary">
                                        <img width="122" src="{{ theme_asset('assets/img/media/flash-deal.svg') }}" loading="lazy" alt="" class="dark-support svg">
                                    </div>
                                    <div class="d-flex justify-content-center  align-items-end gap-2 mb-4">
                                        <h2 class="text-primary fw-medium">{{ translate('hurry_up').'!' }}</h2>
                                        <div class="text-muted">{{ translate('offer_ends_in').':' }}</div>
                                    </div>
                                </div>
                                <div class="countdown-timer justify-content-center d-flex gap-3 gap-sm-4 flex-wrap align-content-center" data-date="{{$web_config['flash_deals']?$web_config['flash_deals']['end_date']:''}}">
                                    <div class="days d-flex flex-column gap-2 gap-sm-3 text-center"></div>
                                    <div class="hours d-flex flex-column gap-2 gap-sm-3 text-center"></div>
                                    <div class="minutes d-flex flex-column gap-2 gap-sm-3 text-center"></div>
                                    <div class="seconds d-flex flex-column gap-2 gap-sm-3 text-center"></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3" id="filtered-products" class="minWidth-12rem">
                                    @foreach($flashDealProducts as $flashDealProduct)
                                        <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                                            @include('theme-views.partials._product-small-card',['product' => $flashDealProduct])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

