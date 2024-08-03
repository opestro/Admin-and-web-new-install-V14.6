@php
    use App\Utils\Helpers;
    use App\Utils\ProductManager;
@endphp
<section>
    <div class="container">
        @if(auth('customer')->check() && count($order_again)>0)
            <div class="bg-primary-light rounded p-3 d-sm-none mb-4">
                <h3 class="text-primary mb-3 mt-2 text-capitalize">{{ translate('order_again') }}</h3>
                <p>{{ translate('want_to_order_your_usuals') }}
                    ? {{ translate('just_reorder_from_your_previous_orders').'.' }}</p>
                <div class="d-flex flex-wrap gap-3 custom-scrollbar height-26-5-rem">
                    @foreach($order_again as $order)
                        <div class="card rounded-10 flex-grow-1">
                            <div class="p-3">
                                <h6 class="fs-12 text-primary mb-1">
                                    @if($order['order_status'] =='processing')
                                        {{ translate('packaging') }}
                                    @elseif($order['order_status'] =='failed')
                                        {{ translate('failed_to_deliver') }}
                                    @elseif($order['order_status'] == 'all')
                                        {{ translate('all') }}
                                    @else
                                        {{ translate(str_replace('_',' ',$order['order_status'])) }}
                                    @endif
                                </h6>
                                <div
                                    class="fs-10">{{ translate('on') }} {{date('d M Y',strtotime($order['updated_at']))}}</div>
                                <div class="bg-light my-2 rounded-10 p-4">
                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                        @foreach($order['details']->take(3) as $key=>$detail)
                                            <div>
                                                <img width="42" loading="lazy" alt="" class="dark-support rounded"
                                                     src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.($detail['product']['thumbnail'] ?? ''), type: 'product') }}">
                                            </div>
                                        @endforeach

                                        @if(count($order['details']) > 3)
                                            <h6 class="fw-medium fs-12 text-center">+{{ count($order['details'])-3 }}
                                                <br>
                                                <a href="{{ route('account-order-details', ['order_id'=>$order['id']]) }}">{{ translate('more') }}</a>
                                            </h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <div class="">
                                        <h6 class="fs-10 mb-2">{{ translate('Order_ID').':'. '#' }}{{ $order['id'] }}</h6>
                                        <h6>{{ translate('final_total').':' }}{{ Helpers::currency_converter($order['order_amount']) }}</h6>
                                    </div>
                                    <a href="javascript:" data-order-id="{{ $order['id'] }}"
                                       class="btn btn-primary order-again">{{ translate('order_again') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="d-sm-none mb-4">
                @if($sidebar_banner)
                    <a href="{{ $sidebar_banner['url'] }}">
                        <img src="{{ getValidImage(path: 'storage/app/public/banner/'.($sidebar_banner ? $sidebar_banner['photo'] : ''), type:'banner') }}"
                            alt="" class="dark-support rounded w-100">
                    </a>
                @else
                    <img src="{{ theme_asset('assets/img/top-side-banner-placeholder.png') }}"
                         class="dark-support rounded w-100" alt="">
                @endif
            </div>
        @endif
        <div class="pb-3">
            <div class="">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                    <h2>{{translate('more_stores')}}</h2>
                    <a href="{{route('vendors')}}" class="btn-link">{{translate('view_all')}}
                        <i class="bi bi-chevron-right text-primary"></i></a>
                </div>
                <div class="table-responsive hide-scrollbar">
                    <div class="d-flex gap-3 {{count($more_seller) > 2 ? 'justify-content-between' : ''}} store-list">
                        @php($current_date = date('Y-m-d'))
                        @foreach($more_seller as $seller)
                            @php($start_date = date('Y-m-d', strtotime($seller->shop['vacation_start_date'])))
                            @php($end_date = date('Y-m-d', strtotime($seller->shop['vacation_end_date'])))
                            <a href="{{route('shopView',['id'=>$seller['id']])}}"
                               class="store-product d-flex flex-column gap-3 align-items-center">
                                <div class="position-relative">
                                    <div class="avatar rounded-circle">
                                        <img class="dark-support img-fit rounded-circle img-w-h-100"
                                             src="{{ getValidImage(path: 'storage/app/public/shop/'.$seller->shop->image, type:'shop') }}" alt=""
                                             loading="lazy">
                                    </div>
                                    @if($seller->shop->temporary_close)
                                        <span class="temporary-closed position-absolute rounded-circle">
                                            <span>{{translate('Temporary_OFF')}}</span>
                                        </span>
                                    @elseif($seller->shop->vacation_status && ($current_date >= $start_date) && ($current_date <= $end_date))
                                        <span class="temporary-closed position-absolute rounded-circle">
                                            <span>{{translate('closed_now')}}</span>
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex flex-column align-items-center text-center gap-2 w-100">
                                    <h6 class="text-truncate text-center">{{$seller->shop->name}}</h6>
                                    <div
                                        class="text-muted text-truncate product-count">{{$seller->product_count}} {{translate('products')}}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
