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
                            @include('theme-views.users-profile.account-order-details._order-details-head',['order'=> $order])

                            @php($review_count = 0)
                            @foreach ($order->details as $order_details)
                                @isset($order_details->reviewData)
                                    @php($review_count++)
                                    <div class="mt-4">

                                    <div class="media gap-3">
                                        <div class="avatar avatar-xxl rounded border overflow-hidden">
                                            <img class="d-block img-fit" src="{{ getStorageImages(path: $order_details?->productAllStatus?->thumbnail_full_url, type: 'product') }}" alt="" width="60">
                                        </div>
                                        <div class="media-body d-flex gap-1 flex-column">
                                            <h6>
                                                <a href="{{ $order_details?->product?->slug ? route('product', $order_details?->product?->slug) : 'javascript:' }}">
                                                    {{ $order_details?->product?->name ? Str::limit($order_details?->product?->name, 40) : translate('Product_not_found') }}
                                                </a>
                                                <br>
                                            </h6>

                                            @if($order_details->variant)
                                                <small>
                                                    {{ translate('variant')}} : {{$order_details->variant}}
                                                </small>
                                            @endif

                                            <div class="d-inline-block">
                                                <button class="btn-star" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#reviewModal{{$order_details->id}}">
                                                    <i class="bi bi-star"></i>
                                                    <span>{{ translate('update_review') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                @include('theme-views.layouts.partials.modal._review', ['id'=>$order_details->id,'order_details'=>$order_details])

                                <div class="media flex-wrap align-items-center gap-3 py-3">
                                    <div class="media-body d-flex flex-column gap-2">
                                        <div
                                            class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-1 text-capitalize">
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.2559 16.25H16.2586V17.5H11.2559V16.25ZM11.2559 13.75H18.7599V15H11.2559V13.75ZM11.2559 11.25H18.7599V12.5H11.2559V11.25Z" fill="#EF7822"/>
                                                        <path d="M12.8495 7.00672L10.0051 1.24609L7.16063 7.00672L0.800781 7.93047L5.40293 12.4148L4.31617 18.7461L8.7545 16.4142V15.0017L5.97758 16.4611L6.63538 12.6261L6.74669 11.978L6.27584 11.5198L3.48829 8.80297L7.34071 8.24359L7.99102 8.14922L8.2824 7.55984L10.0051 4.07047L11.7278 7.55984L12.0192 8.14922L12.6695 8.24359L17.3304 8.92172L17.5086 7.68359L12.8495 7.00672Z" fill="#EF7822"/>
                                                    </svg>
                                                    {{ translate('My_Review') }}
                                                </h6>
                                                <div
                                                    class="d-flex gap-2 align-items-center">
                                                    <div
                                                        class="star-rating text-gold fs-12">
                                                        @for ($inc=0; $inc < 5; $inc++)
                                                            @if ($inc < $order_details->reviewData->rating)
                                                                <i class="bi bi-star-fill"></i>
                                                            @else
                                                                <i class="bi bi-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span>({{ $order_details->reviewData->rating }}/5)</span>
                                                </div>
                                            </div>
                                            <div>{{ $order_details->reviewData->created_at ? $order_details->reviewData->created_at->format("d M Y h:i:s A") : ($order_details->reviewData->updated_at->format("d M Y h:i:s A")) }}</div>
                                        </div>

                                        <p>{{$order_details->reviewData->comment}}</p>

                                        @if(count($order_details->reviewData->attachment_full_url)>0)
                                            <div
                                                class="d-flex flex-wrap gap-2 products-comments-img custom-image-popup-init">
                                                @foreach($order_details->reviewData->attachment_full_url as $img)
                                                    <a href="{{ getStorageImages(path: $img, type:'product') }}" class="custom-image-popup">
                                                        <img class="remove-mask-img" alt=""
                                                             src="{{ getStorageImages(path: $img, type:'product') }}">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($order_details->reviewData && $order_details->reviewData->reply)
                                    <div class="pb-3 mt-3">
                                        <div class="review-reply rounded bg-E9F3FF80 p-3 mx-4 {{ $order_details->reviewData->reply ? 'reply-dashed-border' : '' }}">
                                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="{{dynamicAsset('public/assets/front-end/img/seller-reply-icon.png')}}"
                                                         alt="">
                                                    <h6 class="font-bold fs-14 m-0">
                                                        {{ translate('Reply_by_Seller') }}
                                                    </h6>
                                                </div>
                                                <span class="opacity-50 fs-12">
                                                    {{ isset($order_details->reviewData->reply->created_at) ? $order_details->reviewData->reply->created_at->format('M-d-Y') : '' }}
                                                </span>
                                            </div>
                                            <p class="fs-14">
                                                {!! $order_details->reviewData->reply->reply_text !!}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endisset
                            @endforeach
                            @if ($review_count == 0)
                                <div class="text-center pt-5 text-capitalize">
                                    <img class="mb-1" src="{{dynamicAsset(path: 'public/assets/front-end/img/icons/empty-review.svg')}}"
                                         alt="">
                                    <p class="opacity-60 mt-3 text-capitalize">{{translate('no_review_found')}}!</p>
                                </div>
                            @endif
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
