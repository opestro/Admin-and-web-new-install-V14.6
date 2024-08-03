<div class="container rtl pt-4 px-0 px-md-3">
    <div class="seller-card">
        <div class="card __shadow h-100">
            <div class="card-body pb-1">
                <div class="row d-flex justify-content-between">
                    <div class="seller-list-title">
                        <h5 class="font-bold m-0 text-capitalize">
                            {{ translate('top_sellers')}}
                        </h5>
                    </div>
                    <div class="seller-list-view-all">
                        <a class="text-capitalize view-all-text web-text-primary"
                            href="{{ route('vendors', ['filter'=>'top-vendors']) }}">
                            {{ translate('view_all')}}
                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 float-left' : 'right ml-1 mr-n1'}}"></i>
                        </a>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="others-store-slider owl-theme owl-carousel">

                        @foreach ($topVendorsList as $vendorData)
                            <a href="{{route('shopView',['id'=> $vendorData['id']])}}" class="others-store-card text-capitalize">
                                <div class="overflow-hidden other-store-banner">
                                    @if($vendorData['id'] != 0)
                                        <img class="w-100 h-100 object-cover" alt=""
                                         src="{{ getValidImage(path: 'storage/app/public/shop/banner/'.($vendorData->banner), type: 'shop-banner') }}">
                                    @else
                                        <img class="w-100 h-100 object-cover" alt=""
                                             src="{{ getValidImage(path: 'storage/app/public/shop/'.($vendorData->banner), type: 'shop-banner') }}">
                                    @endif
                                </div>
                                <div class="name-area">
                                    <div class="position-relative">
                                        <div class="overflow-hidden other-store-logo rounded-full">
                                            @if($vendorData['id'] != 0)
                                                <img class="rounded-full" alt="{{ translate('store') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/shop/'.($vendorData->image), type: 'shop') }}">
                                            @else
                                                <img class="rounded-full" alt="{{ translate('store') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/company/'.($vendorData->image), type: 'shop') }}">
                                            @endif
                                        </div>

                                        @if($vendorData->temporary_close)
                                            <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                <span>{{translate('Temporary_OFF')}}</span>
                                            </span>
                                        @elseif($vendorData->vacation_status && ($current_date >= $vendorData->vacation_start_date) && ($current_date <= $vendorData->vacation_end_date))
                                            <span class="temporary-closed position-absolute text-center rounded-full p-2">
                                                <span>{{translate('closed_now')}}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="info pt-2">
                                        <h5>{{ $vendorData->name }}</h5>
                                        <div class="d-flex align-items-center">
                                            <h6 class="web-text-primary">{{number_format($vendorData->average_rating,1)}}</h6>
                                            <i class="tio-star text-star mx-1"></i>
                                            <small>{{ translate('rating') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="info-area">
                                    <div class="info-item">
                                        <h6 class="web-text-primary">
                                            {{$vendorData->review_count < 1000 ? $vendorData->review_count : number_format($vendorData->review_count/1000 , 1).'K'}}
                                        </h6>
                                        <span>{{ translate('reviews') }}</span>
                                    </div>
                                    <div class="info-item">
                                        <h6 class="web-text-primary">
                                            {{$vendorData->products_count < 1000 ? $vendorData->products_count : number_format($vendorData->products_count/1000 , 1).'K'}}
                                        </h6>
                                        <span>{{ translate('products') }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
