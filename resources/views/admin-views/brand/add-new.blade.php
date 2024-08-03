@extends('layouts.back-end.app')

@section('title', translate('brand_Add'))

@section('content')
    <div class="content container-fluid">

        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/brand.png') }}" alt="">
                {{ translate('brand_Setup') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-body text-start">
                        <form action="{{ route('admin.brand.add-new') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($language as $lang)
                                    <li class="nav-item">
                                        <span class="nav-link form-system-language-tab cursor-pointer {{$lang == $defaultLanguage ? 'active':''}}"
                                           id="{{$lang}}-link">
                                            {{ ucfirst(getLanguageName($lang)).'('.strtoupper($lang).')' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col-md-6">
                                    @foreach($language as $lang)
                                        <div
                                            class="form-group {{$lang != $defaultLanguage ? 'd-none':''}} form-system-language-form"
                                            id="{{$lang}}-form">
                                            <label for="name" class="title-color">
                                                {{ translate('brand_Name') }}
                                                <span class="text-danger">*</span>
                                                ({{strtoupper($lang) }})
                                            </label>
                                            <input type="text" name="name[]" class="form-control" id="name" value=""
                                                   placeholder="{{ translate('ex') }} : {{translate('LUX') }}" {{$lang == $defaultLanguage? 'required':''}}>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                    @endforeach
                                    <div class="form-group">
                                        <label for="name" class="title-color">
                                            {{ translate('brand_Logo') }}<span class="text-danger">*</span>
                                        </label>
                                        <span class="ml-1 text-info">
                                        {{ THEME_RATIO[theme_root_path()]['Brand Image'] }}
                                    </span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="brand-image"
                                                   class="custom-file-input image-preview-before-upload"
                                                   data-preview="#viewer" required
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="brand-image">
                                                {{translate('choose_file') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="text-center">
                                        <img class="upload-img-view" id="viewer"
                                             src="{{dynamicAsset(path: 'public\assets\back-end\img\400x400\img2.jpg') }}" alt="">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-3 justify-content-end">
                                <button type="reset" id="reset"
                                        class="btn btn-secondary px-4">{{ translate('reset') }}</button>
                                <button type="submit" class="btn btn--primary px-4">{{ translate('submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/products-management.js') }}"></script>
@endpush
