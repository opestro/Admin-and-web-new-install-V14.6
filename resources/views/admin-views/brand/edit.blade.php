@extends('layouts.back-end.app')

@section('title', translate('brand_Update'))

@section('content')
    <div class="content container-fluid">

        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0 align-items-center d-flex gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/brand.png') }}" alt="">
                {{ translate('brand_Update') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-start">
                        <form action="{{ route('admin.brand.update', [$brand['id']]) }}" method="post"
                              enctype="multipart/form-data" class="brand-setup-form">
                            @csrf

                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($language as $lang)
                                    <li class="nav-item text-capitalize">
                                        <span class="nav-link form-system-language-tab cursor-pointer {{$lang == $defaultLanguage? 'active':''}}"
                                           id="{{$lang}}-link">
                                            {{ucfirst(getLanguageName($lang)).'('.strtoupper($lang).')'}}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="row">
                                <div class="col-md-6">
                                    @foreach($language as $lang)
                                            <?php
                                            if (count($brand['translations'])) {
                                                $translate = [];
                                                foreach ($brand['translations'] as $translations) {
                                                    if ($translations->locale == $lang && $translations->key == "name") {
                                                        $translate[$lang]['name'] = $translations->value;
                                                    }
                                                }
                                            }
                                            ?>
                                        <div class="form-group {{$lang != $defaultLanguage ? 'd-none':''}} form-system-language-form"
                                             id="{{$lang}}-form">
                                            <label class="title-color" for="name">{{ translate('brand_Name') }}
                                                ({{ strtoupper($lang) }})</label>
                                            <input type="text" name="name[]"
                                                   value="{{$lang == $defaultLanguage ? $brand['name']:($translate[$lang]['name']??'') }}"
                                                   class="form-control" id="name"
                                                   placeholder="{{ translate('ex') }} : {{ translate('LUX') }}" {{$lang == $defaultLanguage? 'required':''}}>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color text-capitalize">
                                            {{ translate('image_alt_text') }}
                                        </label>
                                        <input type="text" name="image_alt_text" class="form-control" value="{{$brand['image_alt_text']}}"
                                               placeholder="{{ translate('ex').' : '.translate('apex_Brand') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4 shadow-none">
                                <div class="card-body py-5">
                                    <div class="mx-auto text-center max-w-170px">
                                        <label class="d-block text-center font-weight-bold">
                                            {{translate('image')}}  <small class="text-danger">{{'('.translate('size').': 1:1)'}}</small>
                                        </label>

                                        <label class="custom_upload_input d-block mx-2 cursor-pointer">
                                            <input data-image="{{ getStorageImages(path: $brand->image_full_url, type: 'backend-brand') }}" type="file" name="image" id="brand-image" class="custom-file-input image-preview-before-upload d-none" data-preview="#pre_img_viewer" accept="image/*">

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn d--none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2 p-0">
                                                <img id="pre_img_viewer" class="h-auto aspect-1 bg-white d-none"
                                                        src="" alt="">
                                            </div>
                                            <div class="placeholder-image">
                                                <div
                                                    class="d-flex flex-column justify-content-center align-items-center aspect-1">
                                                    <img alt="" width="33" src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg') }}">
                                                    <h3 class="text-muted fz-12">{{ translate('upload_image') }}</h3>
                                                </div>
                                            </div>
                                        </label>

                                        <p class="text-muted mt-2 fz-12 m-0">
                                            {{ translate('image_format') }} : {{ "jpg, png, jpeg, webp" }}
                                            <br>
                                            {{ translate('image_size') }} : {{ translate('max') }} {{ "2 MB" }}</p>
                                    </div>
                                </div>
                            </div>


                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" id="reset"
                                        class="btn btn-secondary px-4">{{ translate('reset') }}</button>
                                <button type="submit" class="btn btn--primary px-4">{{ translate('update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('.brand-setup-form').on('reset', function () {
            window.location.reload()
        });
    </script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/products-management.js') }}"></script>
@endpush
