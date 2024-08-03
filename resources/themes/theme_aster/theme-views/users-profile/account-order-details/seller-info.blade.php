@extends('theme-views.layouts.app')

@section('title', translate('order_Details').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-4">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            @include('theme-views.users-profile.account-order-details._order-details-head',['order'=>$order])
                            <div class="mt-4 card pb-xl-5">
                                <div class="card-body mb-xl-5">
                                    @if($order->seller_is =='seller')
                                    <div class="d-flex justify-content-between align-items-center gap-4 flex-wrap">
                                        <div class="media align-items-center gap-3">
                                            <div class="avatar rounded store-avatar overflow-hidden d-flex align-items-center justify-content-center">
                                                <img alt="" class="dark-support rounded img-fit"
                                                    src="{{ getValidImage(path: 'storage/app/public/shop/'.($order?->seller?->shop->image), type:'shop') }}">
                                            </div>
                                            <div class="media-body d-flex flex-column gap-2">
                                                <h4>{{ $order?->seller?->shop->name}}</h4>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <div class="star-rating text-gold fs-12">
                                                        @for($inc=1;$inc<=5;$inc++)
                                                            @if ($inc <= (int)$avg_rating)
                                                                <i class="bi bi-star-fill"></i>
                                                                @elseif ($avg_rating != 0 && $inc <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                                <i class="bi bi-star-half"></i>
                                                            @else
                                                                <i class="bi bi-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="text-muted fw-semibold">{{number_format($avg_rating,1)}}</span>
                                                </div>
                                                <ul class="list-unstyled list-inline-dot fs-12">
                                                    <li>{{ $rating_count }} {{ translate('reviews') }} </li>
                                                </ul>
                                            </div>
                                        </div>
                                        @if(isset($order->seller->shop) && $order->seller->shop['id'] != 0)
                                        <div class="d-flex flex-column gap-3">
                                            <button  class="btn btn-primary"
                                                     data-bs-toggle="modal" data-bs-target="#contact_sellerModal">
                                                <i class="bi bi-chat-square-fill"></i>
                                                {{ translate('Chat_with_vendor') }}
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                    @if(isset($order->seller->shop) && $order->seller->shop['id'] != 0)
                                        @include('theme-views.layouts.partials.modal._chat-with-seller',['shop'=>$order->seller->shop, 'user_type' => 'seller'])
                                    @endif

                                    <div class="d-flex gap-3 flex-wrap mt-4">
                                        <div class="card flex-grow-1">
                                            <div class="card-body grid-center">
                                                <div class="text-center">
                                                    <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{round($rating_percentage)}}%</h2>
                                                    <p class="text-muted">{{ translate('positive_review') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card flex-grow-1">
                                            <div class="card-body grid-center">
                                                <div class="text-center">
                                                    <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{ $product_count }}</h2>
                                                    <p class="text-muted">{{ translate('products') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                        <div class="d-flex justify-content-between align-items-center gap-4 flex-wrap">
                                            <div class="media align-items-center gap-3">
                                                <div class="avatar rounded store-avatar overflow-hidden d-flex align-items-center justify-content-center">
                                                    <img  class="dark-support rounded img-fit" alt=""
                                                         src="{{ getValidImage(path: 'storage/app/public/company/'.($web_config['fav_icon']->value), type:'shop') }}">
                                                </div>
                                                <div class="media-body d-flex flex-column gap-2">
                                                    <h4> {{ $web_config['name']->value}}</h4>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <div class="star-rating text-gold fs-12">
                                                            @for($inc=1;$inc<=5;$inc++)
                                                                @if ($inc <= (int)$avg_rating)
                                                                    <i class="bi bi-star-fill"></i>
                                                                @elseif ($avg_rating != 0 && $inc <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                                    <i class="bi bi-star-half"></i>
                                                                @else
                                                                    <i class="bi bi-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <span class="text-muted fw-semibold">{{number_format($avg_rating,1)}}</span>
                                                    </div>
                                                    <ul class="list-unstyled list-inline-dot fs-12">
                                                        <li>{{ $rating_count }} {{ translate('reviews') }} </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-column gap-3">
                                                <button  class="btn btn-primary"
                                                         data-bs-toggle="modal" data-bs-target="#contact_sellerModal">
                                                    <i class="bi bi-chat-square-fill"></i>
                                                    {{ translate('Chat_with_vendor') }}
                                                </button>
                                            </div>
                                        </div>

                                        @include('theme-views.layouts.partials.modal._chat-with-seller',['shop'=>0, 'user_type' => 'admin'])

                                        <div class="d-flex gap-3 flex-wrap mt-4">
                                            <div class="card flex-grow-1">
                                                <div class="card-body grid-center">
                                                    <div class="text-center">
                                                        <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{round($rating_percentage)}}%</h2>
                                                        <p class="text-muted">{{ translate('positive_review') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card flex-grow-1">
                                                <div class="card-body grid-center">
                                                    <div class="text-center">
                                                        <h2 class="fs-28 text-primary fw-extra-bold mb-2">{{ $product_count }}</h2>
                                                        <p class="text-muted">{{ translate('products') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('script')
    <script src="{{ theme_asset('assets/js/order-summary.js') }}"></script>
@endpush
