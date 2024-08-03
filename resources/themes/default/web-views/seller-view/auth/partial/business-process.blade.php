<section class="bg-section--2 py-4 py-lg-5">
    <div class="container">
        <div class="d-flex flex-column gap-2 align-items-center text-center mb-5">
            <h2 class="text-absolute-white section-title-style-2 text-capitalize">{{$businessProcess?->title??translate('3_easy_steps_to_start_sell')}}</h2>
            <p class="max-w-500 text-absolute-white">{{$businessProcess?->sub_title}}</p>
        </div>

        <div class="horizontal-scroll d-flex justify-content-between gap-3 gap-lg-4">
            <div class="d-flex flex-column gap-3 align-items-center text-center">
                <img width="100 " src="{{isset($businessProcessStep[0]) && !empty($businessProcessStep[0]->image) ? getValidImage(path:'storage/app/public/vendor-registration-setting/'.$businessProcessStep[0]->image,type: 'product') : theme_asset('public/assets/front-end/img/media/step1.png')}}" alt="" class="dark-support">
                <h4 class="text-absolute-white text-capitalize">{{isset($businessProcessStep[0]) ? ($businessProcessStep[0]?->title ?? translate('get_registered')) : translate('get_registered')  }}</h4>
                <p class="text-absolute-white max-w-320">{{isset($businessProcessStep[0]) ? $businessProcessStep[0]?->description : ''}}</p>
            </div>
            <div class="middle-step-of-steps d-flex flex-column gap-3 align-items-center text-center">
                <img width="100 " src="{{isset($businessProcessStep[1]) && !empty($businessProcessStep[1]->image) ? getValidImage(path:'storage/app/public/vendor-registration-setting/'.$businessProcessStep[1]->image,type: 'product') : theme_asset('public/assets/front-end/img/media/step2.png')}}" alt="" class="dark-support">
                <h4 class="text-absolute-white text-capitalize">{{isset($businessProcessStep[1]) ? ($businessProcessStep[1]?->title ?? translate('upload_products')) : translate('upload_products')}}</h4>
                <p class="text-absolute-white max-w-320">{{isset($businessProcessStep[1]) ? $businessProcessStep[1]?->description : ''}}</p>
            </div>
            <div class="d-flex flex-column gap-3 align-items-center text-center">
                <img width="100 " src="{{isset($businessProcessStep[2]) && !empty($businessProcessStep[2]->image) ? getValidImage(path:'storage/app/public/vendor-registration-setting/'.$businessProcessStep[2]->image,type: 'product') : theme_asset('public/assets/front-end/img/media/step3.png')}}" alt="" class="dark-support">
                <h4 class="text-absolute-white text-capitalize">{{isset($businessProcessStep[2]) ? ($businessProcessStep[2]?->title ?? translate('start_selling')) : translate('start_selling')}}</h4>
                <p class="text-absolute-white max-w-320">{{isset($businessProcessStep[2]) ? $businessProcessStep[2]?->description : ''}}</p>
            </div>
        </div>
    </div>
</section>
