<section class="py-4 py-lg-5">
    <div class="container">
        <div class="app-section">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="text-center">
                        <img width="360" src="{{ !empty($downloadVendorApp?->image) ? getValidImage(path:'storage/app/public/vendor-registration-setting/'.$downloadVendorApp?->image,type: 'product') : theme_asset('assets/img/media/phone.png')}}" class="dark-support" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mt-5">
                        <h3 class="mb-2 text-capitalize">{{$downloadVendorApp?->title ?? translate('download_free_vendor_app')}}</h3>
                        <p class="max-w-500 mb-4">{{$downloadVendorApp?->sub_title}}</p>
                        <div class="d-flex gap-3 flex-wrap">
                            @if(isset($downloadVendorApp->download_google_app) && $downloadVendorApp?->download_google_app_status ==1)
                                <a href="{{$downloadVendorApp?->download_google_app}}"><img width="130" src="{{theme_asset('assets/img/media/google-btn.png')}}" alt=""></a>
                            @endif
                            @if(isset($downloadVendorApp->download_apple_app) && $downloadVendorApp?->download_apple_app_status ==1)
                                <a href="{{$downloadVendorApp?->download_apple_app}}"><img width="130" src="{{theme_asset('assets/img/media/app-btn.png')}}" alt=""></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
