@extends('layouts.back-end.app')

@section('title', translate('banner'))

@section('content')
    <div class="content container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <div>
                <h2 class="h1 mb-1 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/banner.png') }}" alt="">
                    {{ translate('banner_update_form') }}
                </h2>
            </div>
            <div>
                <a class="btn btn--primary text-white" href="{{ route('admin.banner.list') }}">
                    <i class="tio-chevron-left"></i> {{ translate('back') }}</a>
            </div>
        </div>

        <div class="row text-start">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.banner.update', [$banner['id']]) }}" method="post" enctype="multipart/form-data"
                              class="banner_form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="id" name="id">
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="title-color text-capitalize">{{ translate('banner_type') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="banner_type" required id="banner_type_select">
                                            @foreach($bannerTypes as $key => $singleBanner)
                                                <option value="{{ $key }}" {{ $banner['banner_type'] == $key ? 'selected':''}}>{{ $singleBanner }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="name" class="title-color text-capitalize">{{ translate('banner_URL') }}</label>
                                        <input type="url" name="url" class="form-control" id="url" required placeholder="{{ translate('enter_url') }}" value="{{$banner['url']}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="resource_id" class="title-color text-capitalize">{{ translate('resource_type') }}</label>
                                        <select class="js-example-responsive form-control w-100 action-display-data" name="resource_type" required>
                                            <option value="product" {{$banner['resource_type']=='product'?'selected':''}}>{{ translate('product') }}</option>
                                            <option value="category" {{$banner['resource_type']=='category'?'selected':''}}>{{ translate('category') }}</option>
                                            <option value="shop" {{$banner['resource_type']=='shop'?'selected':''}}>{{ translate('shop') }}</option>
                                            <option value="brand" {{$banner['resource_type']=='brand'?'selected':''}}>{{ translate('brand') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-0 {{$banner['resource_type']=='product'?'d--block':'d--none'}}" id="resource-product">
                                        <label for="product_id" class="title-color text-capitalize">{{ translate('product') }}</label>
                                        <select class="js-example-responsive form-control w-100"
                                                name="product_id">
                                            @foreach($products as $product)
                                                <option value="{{$product['id']}}" {{$banner['resource_id']==$product['id']?'selected':''}}>{{$product['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-0 {{ $banner['resource_type']=='category'?'d--block':'d--none' }}" id="resource-category">
                                        <label for="name" class="title-color text-capitalize">{{ translate('category') }}</label>
                                        <select class="js-example-responsive form-control w-100"
                                                name="category_id">
                                            @foreach($categories as $category)
                                                <option value="{{$category['id']}}" {{$banner['resource_id']==$category['id']?'selected':''}}>{{$category['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-0 {{ $banner['resource_type']=='shop'?'d--block':'d--none' }}" id="resource-shop">
                                        <label for="shop_id" class="title-color text-capitalize">{{ translate('shop') }}</label>
                                        <select class="js-example-responsive form-control w-100"
                                                name="shop_id">
                                            @foreach($shops as $shop)
                                                <option value="{{$shop['id']}}" {{$banner['resource_id']==$shop['id']?'selected':''}}>{{$shop['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-0 {{$banner['resource_type']=='brand'?'d--block':'d--none'}}" id="resource-brand">
                                        <label for="brand_id" class="title-color text-capitalize">{{ translate('brand') }}</label>
                                        <select class="js-example-responsive form-control w-100"
                                                name="brand_id">
                                            @foreach($brands as $brand)
                                                <option value="{{$brand['id']}}" {{$banner['resource_id']==$brand['id']?'selected':''}}>{{$brand['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if(theme_root_path() == 'theme_fashion')
                                    <div class="form-group mt-4 input-field-for-main-banner {{$banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                        <label for="button_text" class="title-color text-capitalize">{{ translate('Button_Text') }}</label>
                                        <input type="text" name="button_text" class="form-control" id="button_text" placeholder="{{ translate('Enter_button_text') }}" value="{{$banner['button_text']}}">
                                    </div>
                                    <div class="form-group mt-4 mb-0 input-field-for-main-banner {{$banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                        <label for="background_color" class="title-color text-capitalize">{{ translate('background_color') }}</label>
                                        <input type="color" name="background_color" class="form-control form-control_color w-100" id="background_color" value="{{$banner['background_color']}}">
                                    </div>
                                    @endif

                                </div>
                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                    <div>
                                        <div class="mx-auto text-center">
                                            <div class="uploadDnD">
                                                <div class="form-group inputDnD input_image input_image_edit"
                                                     data-bg-img="{{ dynamicStorage(path: 'storage/app/public/banner') }}/{{$banner['photo']}}"
                                                     data-title="{{ file_exists('storage/app/public/banner/'.$banner['photo']) ? '': 'Drag and drop file or Browse file'}}">
                                                    <input type="file" name="image" class="form-control-file text--primary font-weight-bold" id="banner"  accept=".jpg, .png, .jpeg, .gif, .bmp, .webp |image/*">
                                                </div>
                                            </div>
                                        </div>
                                        <label for="name" class="title-color text-capitalize">
                                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="" data-original-title="{{ translate('banner_image_ratio_is_not_same_for_all_sections_in_website').' '.translate('Please_review_the_ratio_before_upload') }}">
                                                <img alt="" width="16" src={{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }} alt="" class="m-1">
                                            </span>
                                            {{ translate('banner_image') }}
                                        </label>
                                        <span class="text-info" id="theme_ratio">( {{ translate('ratio') }} {{ "4:1" }} )</span>
                                        <p>{{ translate('banner_Image_ratio_is_not_same_for_all_sections_in_website') }}. {{ translate('please_review_the_ratio_before_upload') }}</p>

                                         @if(theme_root_path() == 'theme_fashion')
                                         <div class="form-group mt-4 input-field-for-main-banner {{$banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                             <label for="title" class="title-color text-capitalize">{{ translate('Title') }}</label>
                                             <input type="text" name="title" class="form-control" id="title" placeholder="{{ translate('Enter_banner_title') }}" value="{{$banner['title']}}">
                                         </div>
                                         <div class="form-group mb-0 input-field-for-main-banner {{$banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                             <label for="sub_title" class="title-color text-capitalize">{{ translate('Sub_Title') }}</label>
                                             <input type="text" name="sub_title" class="form-control" id="sub_title" placeholder="{{ translate('Enter_banner_sub_title') }}" value="{{$banner['sub_title']}}">
                                         </div>
                                         @endif
                                    </div>
                                </div>

                                <div class="col-md-12 d-flex justify-content-end gap-3">
                                    <button type="reset" class="btn btn-secondary px-4">{{ translate('reset') }}</button>
                                    <button type="submit" class="btn btn--primary px-4">{{ translate('update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/banner.js') }}"></script>
    <script>
        "use strict";
        $(document).on('ready', function () {
            getThemeWiseRatio();
        });
        let elementBannerTypeSelect = $('#banner_type_select');
        elementBannerTypeSelect.on('change',function(){
            getThemeWiseRatio();
        });
        function getThemeWiseRatio(){
            let bannerType = elementBannerTypeSelect.val();
            let theme = '{{ theme_root_path() }}';
            let themeRatio = {!! json_encode(THEME_RATIO) !!};
            let getRatio = themeRatio[theme][bannerType];
            $('#theme_ratio').text(getRatio);
        }
    </script>
@endpush
