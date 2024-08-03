@foreach($productReviews as $productReview)
    <div class="">
        <div class="row product-review d-flex mb-3">
            <div class="col-md-4 d-flex mb-3">
                <div class="media media-ie-fix me-4 {{ $productReview->reply ? 'before-content-border' : '' }}">
                    <img class="rounded-circle __img-64 object-cover"
                         src="{{ isset($productReview->user) ? getStorageImages(path: $productReview->user->image_full_url, type: 'avatar') : theme_asset(path: 'public/assets/front-end/img/image-place-holder.png') }}"
                         alt="{{isset($productReview->user)?$productReview->user->f_name : translate('not exist')}}"/>
                    <div
                        class="media-body {{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}} text-body">
                        <span class="mb-0 text-body font-semi-bold fs-13">{{isset($productReview->user)?$productReview->user->f_name.' '.$productReview->user->l_name : translate('not exist')}}</span>
                        <div class="d-flex ">
                            <div class="me-2">
                                <i class="sr-star czi-star-filled active"></i>
                            </div>
                            <div class="text-body text-nowrap">
                                {{ isset($productReview->rating) ? $productReview->rating : 0 }} / 5
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <p class="text-body __text-sm text-break m-0">{{isset($productReview->comment) ? $productReview->comment : ''}}</p>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    @if (!empty($productReview->attachment_full_url))
                        @foreach ($productReview->attachment_full_url as $key => $attachment)
                            <img data-link="{{ getStorageImages(path: $attachment, type: 'product')}}"
                                 class="cz-image-zoom __img-70 rounded border show-instant-image"
                                 src="{{ getStorageImages(path: $attachment, type: 'product') }}"
                                 alt="{{ translate('product') }}">
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-2 text-body">
                <span class="float-end font-semi-bold fs-13">{{isset($productReview->updated_at) ? $productReview->updated_at->format('M-d-Y') : ''}}</span>
            </div>
        </div>
    </div>

    @if($productReview->reply)
        <div class="pl-md-4 mt-3 mb-3">
            <div class="review-reply rounded bg-E9F3FF80 p-3 ml-md-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{dynamicAsset('public/assets/front-end/img/seller-reply-icon.png')}}" alt="">
                        <h6 class="font-bold fs-14 m-0">{{ translate('Reply_by_Seller') }}</h6>
                    </div>
                    <span class="opacity-50 fs-12">
                        {{ isset($productReview->reply->created_at) ? $productReview->reply->created_at->format('M-d-Y') : '' }}
                    </span>
                </div>
                <p class="fs-14">
                    {!! $productReview->reply->reply_text !!}
                </p>
            </div>
        </div>
    @endif
@endforeach
