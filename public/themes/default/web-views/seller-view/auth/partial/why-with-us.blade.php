<section class="section-padding">
    <div class="container">
        <div class="d-flex flex-column gap-2 align-items-center text-center">
            <h2 class="section-title-style-2 mb-2 text-capitalize">{{$sellWithUs?->title ?? translate('why_sell_with_Us')}}</h2>
            <p class="max-w-500 mb-4">{{$sellWithUs?->sub_title}}</p>
            <img width="395" src="{{!empty($sellWithUs?->image) ? getValidImage(path:'storage/app/public/vendor-registration-setting/'.$sellWithUs?->image,type: 'product') : theme_asset('public/assets/front-end/img/media/sell-with-us.png')}}" alt="" class="dark-support">
        </div>
        @include('web-views.seller-view.auth.partial.reason')
    </div>
</section>
