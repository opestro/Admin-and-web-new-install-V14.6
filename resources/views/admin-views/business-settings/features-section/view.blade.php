@extends('layouts.back-end.app')

@section('title', translate('features_Section'))

@section('content')
<div class="content container-fluid">
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/Pages.png')}}" alt="">
            {{ translate('pages') }}
        </h2>
    </div>
    @include('admin-views.business-settings.pages-inline-menu')
    <form action="{{ route('admin.business-settings.features-section.submit') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            @if(theme_root_path() == 'theme_fashion')
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ translate('features_Section').' '.'-'.' '.translate('top')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-3">
                                <label for="title">{{ translate('title') }}</label>
                                <input type="text" class="form-control" name="features_section_top[title]"
                                    placeholder="{{ translate('type_your_title_text') }}"
                                    value="{{ isset($featuresSectionTop) ? json_decode($featuresSectionTop->value)->title : '' }}">
                            </div>
                            <div class="col-sm-12 col-md-6 mb-3">
                                <label for="subtitle">{{ translate('sub_Title') }}</label>
                                <input type="text" class="form-control" name="features_section_top[subtitle]"
                                    placeholder="{{ translate('type_your_subtitle_text') }}"
                                    value="{{ isset($featuresSectionTop) ? json_decode($featuresSectionTop->value)->subtitle : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h5 class="mb-0">{{ translate('features_Section').' '.'-'.' '.translate('middle') }}</h5>
                        <span id="add-this-features-card-middle" class="btn btn--primary text-capitalize"><i class="tio-add pr-2"></i>{{ translate('add_new') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row" id="features-section-middle-row">
                            @if (!empty($featuresSectionMiddle) )
                                @forelse (json_decode($featuresSectionMiddle->value) as $item)
                                <div class="col-sm-12 col-md-3 mb-4 remove-this-features-card-div">
                                    <div class="card">
                                        <div class="card-header justify-content-end">
                                            <div class="cursor-pointer remove-this-features-card">
                                                <span class="btn btn-outline-danger btn-sm square-btn">
                                                    <i class="tio-delete"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control"
                                                    name="features_section_middle[title][]"
                                                    value="{{ $item->title }}" required
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="text-capitalize" for="sub-title">{{ translate('sub_title') }}</label>
                                                <textarea class="form-control" name="features_section_middle[subtitle][]" required
                                                    placeholder="{{ translate('type_your_subtitle_text') }}">{{ $item->subtitle  }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-sm-12 col-md-3 mb-4 remove-this-features-card-div">
                                    <div class="card">
                                        <div class="card-header justify-content-end">
                                            <div class="cursor-pointer remove-this-features-card">
                                                <span class="btn btn-outline-danger btn-sm square-btn">
                                                    <i class="tio-delete"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control"
                                                    name="features_section_middle[title][]"
                                                    value="" required
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="text-capitalize" for="sub-title">{{ translate('sub_title') }}</label>
                                                <textarea class="form-control" name="features_section_middle[subtitle][]" required
                                                    placeholder="{{ translate('type_your_subtitle_text') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforelse
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h5 class="mb-0">{{ translate('features_Section').' '.'-'.' '.translate('bottom') }}</h5>
                        <span id="add-this-features-card-bottom" class="btn btn--primary text-capitalize"><i class="tio-add pr-2"></i>{{ translate('add_new') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row" id="features-Section-bottom-row">
                            @if (!empty($featuresSectionBottom) )
                                @forelse (json_decode($featuresSectionBottom->value) as $key => $item)
                                @php($card_index = rand(1111, 9999))
                                <div class="col-sm-12 col-md-3 mb-4">
                                    <div class="card">
                                        <div class="card-header align-items-center justify-content-between">
                                            <h5 class="m-0 text-muted">{{ translate('icon_box') }}</h5>
                                            <span class="cursor-pointer text-danger remove_icon_box_with_titles btn btn-outline-danger btn-sm square-btn" data-title="{{ $item->title }}" data-subtitle="{{ $item->subtitle }}">
                                                <i class="tio-delete"></i>
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control" disabled value="{{ $item->title }}"
                                                name="icontitle"
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title">Sub Title</label>
                                                <textarea class="form-control" disabled
                                                    placeholder="{{ translate('type_your_subtitle_text') }}">{{ $item->subtitle }}</textarea>
                                            </div>

                                            <div class="mb-2 d-flex">
                                                <div class="custom_img_upload aspect-ratio-3-15">
                                                    <img id="pre_img_header_logo{{ $card_index }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/banner/'.$item->icon, type: 'backend-basic') }}" class="w-100" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-sm-12 col-md-3 mb-4 remove-this-features-card-div">
                                    <div class="card">
                                        <div class="card-header align-items-center justify-content-between">
                                            <h5 class="m-0 text-muted">{{ translate('icon_box') }}</h5>
                                            <div class="cursor-pointer remove-this-features-card">
                                                <span class="btn btn-outline-danger btn-sm square-btn btn btn-outline-danger btn-sm square-btn">
                                                    <i class="tio-delete"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control"
                                                    name="features_section_bottom[title][]"
                                                    value="" required
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title">{{ translate('Sub_Title') }}</label>
                                                <textarea class="form-control" name="features_section_bottom[subtitle][]" required
                                                    placeholder="{{ translate('type_your_subtitle_text') }}"></textarea>
                                            </div>

                                            <div class="custom_upload_input">
                                                <input type="file" name="features_section_bottom_icon[]" class="custom-upload-input-file aspect-ratio-3-15 upload-color-image"
                                                       data-imgpreview="pre_img_header_logo"
                                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <span class="delete-file-input btn btn-outline-danger btn-sm square-btn d-none">
                                                    <i class="tio-delete"></i>
                                                </span>

                                                <div class="img_area_with_preview position-absolute z-index-2">
                                                    <img id="pre_img_header_logo" class="h-auto aspect-ratio-3-15 bg-white" onerror="this.classList.add('d-none')" src="" alt="">
                                                </div>
                                                <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                                        <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50" alt="">
                                                        <h3 class="text-muted text-capitalize">{{ translate('upload_icon') }}</h3>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endforelse
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end py-2">
                <button type="submit" class="btn btn--primary px-5">{{ translate('submit') }}</button>
            </div>
        </div>
    </form>
</div>
<span id="get-feature-section-append-translate-text"
      data-title="{{translate('title')}}"
      data-title-placeholder="{{translate('type_your_title_text')}}"
      data-sub-title="{{translate('sub-title')}}"
      data-sub-title-placeholder="{{translate('type_your_title_text')}}"
      data-icon-box="{{translate('icon_box')}}"
      data-upload-icon="{{translate('upload_icon')}}">
</span>
<span id="get-feature-section-icon-remove-route" data-action="{{ route('admin.business-settings.features-section.icon-remove') }}"></span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/features-and-company-reliability-section.js') }}"></script>
@endpush
