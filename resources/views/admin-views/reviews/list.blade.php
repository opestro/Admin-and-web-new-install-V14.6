@extends('layouts.back-end.app')

@section('title', translate('review_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-center">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/customer_review.png')}}" alt="">
                {{translate('customer_reviews')}}
            </h2>
        </div>

        <div class="card card-body">
            <div class="row border-bottom pb-3 align-items-center mb-20">
                <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                    <h5 class="text-capitalize d-flex gap-2 align-items-center">
                        {{ translate('review_table') }}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ $reviews->total() }}</span>
                    </h5>
                </div>
                <div class="col-sm-8 col-md-6 col-lg-4">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                   placeholder="{{ translate('search_by_Product_or_Customer') }}"
                                   aria-label="Search orders" value="{{ request('searchValue') }}" required>
                            <button type="submit" class="btn btn--primary">{{ translate('search') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <form action="{{ url()->current() }}" method="GET">
                <div class="row gy-3 align-items-end">
                    <div class="col-md-4">
                        <label for="name" class="title-color">{{ translate('products')}}</label>
                        <div class="dropdown select-product-search w-100">
                            <input type="text" class="product_id" name="product_id" value="{{request('product_id')}}"
                                   hidden>
                            <button class="form-control text-start dropdown-toggle text-truncate select-product-button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button">
                                {{request('product_id') !=null ? $product['name']: translate('select_Product')}}
                            </button>
                            <div class="dropdown-menu w-100 px-2">
                                <div class="search-form mb-3">
                                    <button type="button" class="btn"><i class="tio-search"></i></button>
                                    <input type="text" class="js-form-search form-control search-bar-input search-product" placeholder="{{translate('search_product').'...'}}">
                                </div>
                                <div class="d-flex flex-column gap-3 max-h-40vh overflow-y-auto overflow-x-hidden search-result-box">
                                    @include('admin-views.partials._search-product',['products'=>$products])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="title-color" for="customer">{{translate('customer')}}</label>
                        <input type="hidden" id='customer_id' name="customer_id"
                               value="{{request('customer_id') ? request('customer_id') : 'all'}}">
                        <select data-placeholder="
                                        @if($customer == 'all')
                                            {{translate('all_customer')}}
                                        @else
                                            {{$customer['name'] ?? $customer['f_name'].' '.$customer['l_name'].' '.'('.$customer['phone'].')'}}
                                        @endif"
                                class="get-customer-list-by-ajax-request form-control form-ellipsis set-customer-value">
                            <option value="all">{{translate('all_customer')}}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <label for="status" class="title-color d-flex">{{ translate('choose') }}
                                {{ translate('status') }}</label>
                            <select class="form-control" name="status">
                                <option value="" selected> {{ '---'.translate('select_status').'---' }} </option>
                                <option value="1" {{ !is_null($status) && $status == 1 ? 'selected' : '' }}>
                                    {{ translate('active') }}</option>
                                <option value="0" {{ !is_null($status) && $status == 0 ? 'selected' : '' }}>
                                    {{ translate('inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 mr-auto">
                        <div>
                            <label for="from" class="title-color d-flex">{{ translate('Date_Wise_Filter') }}</label>
                            <input type="text" name="from" id="start-date-time" value="{{ $from }}"
                                   class="form-control date-range-js"
                                   title="{{ translate('Date_Wise_Filter') }}" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div>
                            <button id="filter" type="submit" class="btn btn--primary btn-block filter">
                                <i class="tio-filter-list nav-icon"></i>
                                {{ translate('filter') }}
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div>
                            <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                    data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{ translate('export') }}
                                <i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('admin.reviews.export', ['search'=>request('search'), 'product_id' => $product_id, 'customer_id' => $customer_id, 'status' => $status, 'from' => $from, 'to' => $to]) }}">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                        {{ translate('excel') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card mt-20">
            <div class="table-responsive datatable-custom">
                <table
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
                        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{ translate('SL') }}</th>
                        <th>{{ translate('Review_ID') }}</th>
                        <th>{{ translate('product') }}</th>
                        <th>{{ translate('customer') }}</th>
                        <th>{{ translate('rating') }}</th>
                        <th>{{ translate('review') }}</th>
                        <th>{{ translate('Reply') }}</th>
                        <th>{{ translate('date') }}</th>
                        <th class="text-center">{{ translate('status') }}</th>
                        <th class="text-center">{{ translate('action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($reviews as $key => $review)
                        <tr>
                            <td>
                                {{ $reviews->firstItem()+$key }}
                            </td>
                            <td class="text-center">
                                {{ $review->id }}
                            </td>
                            <td>
                                @if(isset($review->product))
                                    <a href="{{$review['product_id'] ? route('admin.products.view', ['addedBy'=>($review->product->added_by =='seller'?'vendor' : 'in-house'),'id'=>$review->product->id]) : 'javascript:'}}"
                                       class="title-color hover-c1">
                                        {{ Str::limit($review->product['name'], 25) }}
                                    </a>
                                @else
                                    <span class="title-color">
                                        {{ translate('product_not_found') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($review->customer)
                                    <a href="{{ route('admin.customer.view', [$review->customer_id]) }}"
                                       class="title-color hover-c1">
                                        {{ $review->customer->f_name . ' ' . $review->customer->l_name }}
                                    </a>
                                @else
                                    <label class="badge badge-soft-danger">{{ translate('customer_removed') }}</label>
                                @endif
                            </td>
                            <td>
                                <label class="badge badge-soft-info mb-0">
                                        <span class="fz-12 d-flex align-items-center gap-1">{{ $review->rating }}
                                            <i class="tio-star"></i>
                                        </span>
                                </label>
                            </td>
                            <td>
                                <div class="gap-1">
                                    <div>
                                        {{ $review->comment ? Str::limit($review->comment, 35) : translate('no_comment_found') }}
                                    </div>
                                    <br>
                                    @if(count($review->attachment_full_url) > 0)
                                        <div class="d-flex flex-wrap gap-1 min-w-200">
                                            @foreach ($review->attachment_full_url as $img)
                                                <a href="{{ $img['path'] }}"
                                                   data-lightbox="mygallery">
                                                    <img width="60" height="60"
                                                         class="aspect-1 rounded object-fit-cover"
                                                         src="{{ getStorageImages(path: $img, type: 'backend-basic') }}"
                                                         alt="{{translate('image')}}">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="line--limit-2 max-w-250 word-break">
                                    {{ $review?->reply?->reply_text ?? '-' }}
                                </div>
                            </td>
                            <td>{{ date('d M Y', strtotime($review->created_at)) }}</td>
                            <td>
                                <form action="{{ route('admin.reviews.status', [$review['id'], $review->status ? 0 : 1]) }}"
                                      method="get" id="reviews-status{{$review['id']}}-form"
                                      class="reviews_status_form">
                                    <label class="switcher mx-auto">
                                        <input type="checkbox" class="switcher_input toggle-switch-message"
                                               id="reviews-status{{$review['id']}}"
                                               {{ $review->status ? 'checked' : '' }}
                                               data-modal-id = "toggle-status-modal"
                                               data-toggle-id = "reviews-status{{$review['id']}}"
                                               data-on-image = "customer-reviews-on.png"
                                               data-off-image = "customer-reviews-off.png"
                                               data-on-title = "{{translate('Want_to_Turn_ON_Customer_Reviews').'?'}}"
                                               data-off-title = "{{translate('Want_to_Turn_OFF_Customer_Reviews').'?'}}"
                                               data-on-message = "<p>{{translate('if_enabled_anyone_can_see_this_review_on_the_user_website_and_customer_app')}}</p>"
                                               data-off-message = "<p>{{translate('if_disabled_this_review_will_be_hidden_from_the_user_website_and_customer_app')}}</p>">`)">
                                        <span class="switcher_control"></span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <div data-toggle="modal" data-target="#review-view-for-{{ $review['id'] }}">
                                        <a class="btn btn-outline-info btn-sm square-btn" title="{{ translate('View') }}" data-toggle="tooltip">
                                            <i class="tio-invisible"></i>
                                        </a>
                                    </div>

                                    @if(isset($review->product) && $review?->product?->added_by == 'admin')
                                        <div data-toggle="modal" data-target="#review-update-for-{{ $review['id'] }}">
                                            @if($review?->reply)
                                                <a class="btn btn-outline-primary btn-sm square-btn" title="{{ translate('Update_Review') }}" data-toggle="tooltip">
                                                    <i class="tio-edit"></i>
                                                </a>
                                            @else
                                                <div class="btn btn-outline--primary btn-sm square-btn" title="{{ translate('Review_Reply') }}" data-toggle="tooltip">
                                                    <i class="tio-reply-all"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($review?->product?->added_by == 'seller')
                                        <div>
                                            <a class="btn btn-outline-primary btn-sm square-btn" title="{{ translate('Admin_can_not_reply_to_vendor_product_review') }}" data-toggle="tooltip">
                                                @if($review?->reply)
                                                    <i class="tio-edit"></i>
                                                @else
                                                    <i class="tio-reply-all"></i>
                                                @endif
                                            </a>
                                        </div>
                                    @else
                                        <div>
                                            <a class="btn btn-outline-primary btn-sm square-btn" title="{{ translate('product_not_found') }}" data-toggle="tooltip">
                                                @if($review?->reply)
                                                    <i class="tio-edit"></i>
                                                @else
                                                    <i class="tio-reply-all"></i>
                                                @endif
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @foreach($reviews as $key => $review)
                @if(isset($review->customer))
                    <div class="modal fade" id="review-update-for-{{ $review['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close text-BFBFBF" data-dismiss="modal" aria-label="Close">
                                        <i class="tio-clear-circle"></i>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('admin.reviews.add-review-reply') }}">
                                    @csrf
                                    <div class="modal-body pt-0">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                                            @if(isset($review->product))
                                                <img src="{{ getStorageImages(path: $review->product->thumbnail_full_url, type: 'backend-product') }}" width="100" class="rounded aspect-1 border" alt="">
                                                <div class="w-0 flex-grow-1 font-weight-semibold">
                                                    @if($review['order_id'])
                                                        <div class="mb-2">
                                                            {{ translate('Order_ID') }} # {{ $review['order_id'] }}
                                                        </div>
                                                    @endif
                                                    <h4 class="line--limit-2">{{ $review->product['name'] }}</h4>
                                                </div>
                                            @else
                                                <span class="title-color">
                                                    {{ translate('product_not_found') }}
                                                </span>
                                            @endif

                                        </div>
                                        <label class="input-label text--title font-weight-bold">
                                            {{ translate('Review') }}
                                        </label>
                                        <div class="__bg-F3F5F9 p-3 rounded border mb-2">
                                            {{ $review['comment'] }}
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if(count($review->attachment_full_url)>0)
                                                @foreach ($review->attachment_full_url as $img)
                                                    <a class="aspect-1 float-left overflow-hidden"
                                                       href="{{ getStorageImages(path: $img,type: 'backend-basic') }}"
                                                       data-lightbox="review-gallery-modal{{ $review['id'] }}" >
                                                        <img width="45" class="rounded aspect-1 border"
                                                             src="{{ getStorageImages(path:$img,type: 'backend-basic') }}"
                                                             alt="{{translate('review_image')}}">
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                        <label class="input-label text--title font-weight-bold pt-4">
                                            {{ translate('Reply') }}
                                        </label>
                                        <input type="hidden" name="review_id" value="{{ $review['id'] }}">
                                        <textarea class="form-control text-area-max-min" rows="3" name="reply_text"
                                                  placeholder="{{ translate('Write_the_reply_of_the_product_review') }}...">{{ $review?->reply?->reply_text ?? '' }}</textarea>
                                        <div class="text-right mt-4">
                                            <button type="submit" class="btn btn--primary">
                                                @if($review?->reply?->reply_text)
                                                    {{ translate('Update') }}
                                                @else
                                                    {{ translate('submit') }}
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                    <div class="modal fade" id="review-view-for-{{ $review['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close text-BFBFBF" data-dismiss="modal" aria-label="Close">
                                        <i class="tio-clear-circle"></i>
                                    </button>
                                </div>
                                <div class="modal-body pt-0">
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-3 text-center border-bottom">
                                        <div class="w-0 flex-grow-1 font-weight-semibold">
                                            <div class="mb-2">
                                                {{ translate('Review_ID') }} # {{ $review['id'] }}
                                            </div>

                                            @if($review['order_id'])
                                                <div class="mb-2">
                                                    {{ translate('Order_ID') }} # {{ $review['order_id'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <h2 class="text-center">
                                        <span class="text-primary">{{ $review['rating'].'.0' }}</span><span class="fz-16 text-muted">{{ '/5' }}</span>
                                    </h2>
                                    <div class="d-flex align-items-center gap-1 text-primary justify-content-center fz-14 mb-4">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review['rating'])
                                                <i class="tio-star"></i>
                                            @else
                                                <i class="tio-star-outlined"></i>
                                            @endif
                                        @endfor
                                    </div>

                                    <label class="input-label text--title font-weight-bold">
                                        {{ translate('Review') }}
                                    </label>
                                    <div class="__bg-F3F5F9 p-3 rounded border mb-2">
                                        {{ $review['comment'] }}
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if(count($review->attachment_full_url) > 0)
                                            @foreach ($review->attachment_full_url as $img)
                                                <a class="aspect-1 float-left overflow-hidden"
                                                   href="{{ getStorageImages(path: $img,type: 'backend-basic') }}"
                                                   data-lightbox="review-gallery-modal{{ $review['id'] }}" >
                                                    <img width="45" class="rounded aspect-1 border"
                                                         src="{{ getStorageImages(path: $img,type: 'backend-basic') }}"
                                                         alt="{{translate('review_image')}}">
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                    @if($review?->reply?->reply_text)
                                        <label class="input-label text--title font-weight-bold pt-4">
                                            {{ translate('Reply') }}
                                        </label>
                                        <div class="__bg-F3F5F9 p-3 rounded border mb-2">
                                            {{ $review?->reply?->reply_text ?? '' }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach

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
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/search-product.js')}}"></script>
@endpush
