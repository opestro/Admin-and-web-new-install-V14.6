<div class="col-lg-4">
    <div class="">
        <div class="d-flex justify-content-center">
            <div class="ext-center">
                <h3 class="mb-2 text-capitalize">{{$vendorRegistrationHeader?->title ?? translate('vendor_registration')}}</h3>
                <p>{{$vendorRegistrationHeader?->sub_title ?? translate('create_your_own_store').'.'.translate('already_have_store').'?'}}
                    <a class="text-primary fw-bold" href="{{route('vendor.auth.login')}}">{{translate('login')}}</a>
                </p>
                <div class="my-4 text-center">
                    <img width="308" src="{{ !empty($vendorRegistrationHeader?->image) ? getValidImage(path:'storage/app/public/vendor-registration-setting/'.$vendorRegistrationHeader?->image,type: 'product') : theme_asset('assets/img/media/seller-registration.png')}}" alt="" class="dark-support">
                </div>
            </div>
        </div>
    </div>
</div>
