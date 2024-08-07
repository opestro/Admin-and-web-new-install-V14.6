<div class="col-sm-6 col-lg-3">
    <a class="business-analytics card" href="{{route('admin.orders.list',['all'])}}">
        <h5 class="business-analytics__subtitle">{{translate('total_order')}}</h5>
        <h2 class="business-analytics__title">{{ $data['order'] }}</h2>
        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/all-orders.png')}}" width="30" height="30" class="business-analytics__img" alt="">
    </a>
</div>
<div class="col-sm-6 col-lg-3">
    <a class="business-analytics get-view-by-onclick card" href="{{route('admin.vendors.vendor-list')}}">
        <h5 class="business-analytics__subtitle">{{translate('total_Stores')}}</h5>
        <h2 class="business-analytics__title">{{ $data['store'] }}</h2>
        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/total-stores.png')}}" class="business-analytics__img" alt="">
    </a>
</div>
<div class="col-sm-6 col-lg-3">
    <a class="business-analytics card">
        <h5 class="business-analytics__subtitle">{{translate('total_Products')}}</h5>
        <h2 class="business-analytics__title">{{ $data['product'] }}</h2>
        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/total-product.png')}}" class="business-analytics__img" alt="">
    </a>
</div>
<div class="col-sm-6 col-lg-3">
    <a class="business-analytics card" href="{{route('admin.customer.list')}}">
        <h5 class="business-analytics__subtitle">{{translate('total_Customers')}}</h5>
        <h2 class="business-analytics__title">{{ $data['customer'] }}</h2>
        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/total-customer.png')}}" class="business-analytics__img" alt="">
    </a>
</div>


<div class="col-sm-6 col-lg-3">
    <a class="order-stats order-stats_pending" href="{{route('admin.orders.list',['pending'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{dynamicAsset(path: '/public/assets/back-end/img/pending.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{translate('pending')}}</h6>
        </div>
        <span class="order-stats__title">
            {{$data['pending']}}
        </span>
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats order-stats_confirmed" href="{{route('admin.orders.list',['confirmed'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/confirmed.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{translate('confirmed')}}</h6>
        </div>
        <span class="order-stats__title">
            {{$data['confirmed']}}
        </span>
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats order-stats_packaging" href="{{route('admin.orders.list',['processing'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/packaging.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{translate('packaging')}}</h6>
        </div>
        <span class="order-stats__title">
            {{$data['processing']}}
        </span>
    </a>
</div>

<div class="col-sm-6 col-lg-3">
    <a class="order-stats order-stats_out-for-delivery" href="{{route('admin.orders.list',['out_for_delivery'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/out-of-delivery.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{translate('out_for_delivery')}}</h6>
        </div>
        <span class="order-stats__title">
            {{$data['out_for_delivery']}}
        </span>
    </a>
</div>



<div class="col-sm-6 col-lg-3">
    <div class="order-stats order-stats_delivered cursor-pointer get-view-by-onclick" data-link="{{route('admin.orders.list',['delivered'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/delivered.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{translate('delivered')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['delivered']}}</span>
    </div>
</div>

<div class="col-sm-6 col-lg-3">
    <div class="order-stats order-stats_canceled cursor-pointer get-view-by-onclick" data-link="{{route('admin.orders.list',['canceled'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/canceled.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{translate('canceled')}}</h6>
        </div>
        <span class="order-stats__title h3">{{$data['canceled']}}</span>
    </div>
</div>

<div class="col-sm-6 col-lg-3">
    <div class="order-stats order-stats_returned cursor-pointer get-view-by-onclick" data-link="{{route('admin.orders.list',['returned'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/returned.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{translate('returned')}}</h6>
        </div>
        <span class="order-stats__title h3">{{$data['returned']}}</span>
    </div>
</div>

<div class="col-sm-6 col-lg-3">
    <div class="order-stats order-stats_failed cursor-pointer get-view-by-onclick" data-link="{{route('admin.orders.list',['failed'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/failed-to-deliver.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{translate('failed_to_delivery')}}</h6>
        </div>
        <span class="order-stats__title h3">{{$data['failed']}}</span>
    </div>
</div>
