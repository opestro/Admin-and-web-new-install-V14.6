@extends('layouts.back-end.app')

@section('title', translate('SEO_Settings'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/seo-settings.svg') }}" alt="">
                {{ translate('SEO_Settings') }}
            </h2>
        </div>
        @include('admin-views.business-settings.seo-settings._inline-menu')

        <div class="card shadow-none">
            <div class="card-header flex-wrap gap-2">
                <div class="">
                    <h4 class="title m-0">{{translate('Robots_Meta_Content_&_OG_Meta_Content')}}</h4>
                    <p class="m-0">
                        {{ translate("optimize_your_Websites_performance_indexing_status_and_search_visibility") }}
                        <a href="{{ 'https://6amtech.com/blog/robots-meta-content-and-og-content/' }}"
                           target="_blank"
                           class="text--primary text-underline font-weight-semibold">
                            {{ translate('Learn_more') }}
                        </a>
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.seo-settings.robots-meta-content.index') }}" class="text--primary text-underline font-weight-semibold">
                        {{ translate('Back_to_list') }}
                    </a>
                </div>
            </div>

            <div class="card-body p-xl-30">
                <form action="{{ route('admin.seo-settings.robots-meta-content.page-content-update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($pageName == 'default')
                        <input type="hidden" name="page_name" value="{{ 'default' }}">
                    @else
                        <input type="hidden" name="page_name" value="{{ $pageData['page_name'] }}">
                    @endif
                    <div class="card shadow-none">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">
                                                {{ translate('Meta_Title') }}
                                            </label>
                                            <input type="text" placeholder="{{ translate('maximum_120_characters') }} ({{ translate('ideal_60_characters') }})"
                                                   class="form-control" value="{{ $pageData['meta_title'] ?? '' }}"
                                                   name="meta_title" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{ translate('meta_description') }}</label>
                                            <textarea placeholder="{{ translate('maximum_220_characters') }} ({{ translate('ideal_160_characters') }})"
                                                  class="form-control" rows="5" name="meta_description"
                                            >{{ $pageData['meta_description'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex flex-column align-items-center gap-3">
                                        <div class="mx-auto text-center max-w-300px w-100">
                                            <label class="d-block text-center font-weight-bold">
                                                {{ translate('meta_image') }}
                                                <small class="text-danger">({{ translate('size') }}: {{ '2:1' }})</small>
                                            </label>

                                            <label class="custom_upload_input d-flex mx-2 cursor-pointer align-items-center justify-content-center aspect-ratio-3-15">
                                                <input type="file" name="meta_image" id="brand-image"
                                                       class="custom-file-input image-preview-before-upload d-none"
                                                       data-preview="#pre_img_viewer"
                                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                    @if($pageData?->meta_image_full_url['path'])
                                                           data-image="{{ $pageData?->meta_image_full_url['path'] }}"
                                                    @endif
                                                >
                                                <span
                                                    class="delete_file_input btn btn-outline-danger btn-sm square-btn d--none">
                                                    <i class="tio-delete"></i>
                                                </span>

                                                <div class="img_area_with_preview position-absolute z-index-2 p-0 align-content-center d-flex justify-content-center">
                                                    <img id="pre_img_viewer" class="h-auto aspect-1 bg-white d-none"
                                                         src="dummy" alt="">
                                                </div>
                                                <div class="placeholder-image">
                                                    <div
                                                        class="d-flex flex-column justify-content-center align-items-center aspect-1">
                                                        <img alt="" width="33"
                                                             src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg') }}">
                                                        <h3 class="text-muted fz-12">{{ translate('Upload_Image') }}</h3>
                                                    </div>
                                                </div>
                                            </label>

                                            <p class="text-muted mt-2 fz-12 m-0">
                                                {{ translate('image_format') }} : {{ "jpg, png, jpeg" }}
                                                <br>
                                                {{ translate('image_size') }} : {{ translate('max') }} {{ "2 MB" }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border rounded my-4">
                                <div class="row g-3">
                                    <div class="col-md-4 col-xl-2">
                                        <h5 class="m-0 mt-3">{{translate('canonical_URL')}}</h5>
                                    </div>
                                    <div class="col-md-8 col-xl-8">
                                        <input type="url" placeholder="{{ translate('enter_url') }}..."
                                               class="form-control" name="canonicals_url" value="{{ $pageData['canonicals_url'] ?? '' }}">
                                        <div class="mt-10px fs-12">
                                            <div>
                                                {{translate('Learn how to get it.')}}
                                                <a href="{{ 'https://6amtech.com/blog/canonical-urls/' }}"
                                                   target="_blank"
                                                   class="text--primary text-underline font-weight-semibold">
                                                    {{ translate('learn_more') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-lg-6 col-xl-5">
                                    <div
                                        class="robots-meta-checkbox-card d-flex flex-wrap gap-2 justify-content-between h-100">
                                        <div class="item">
                                            <label class="checkbox--item user-select-none">
                                                <input type="radio" name="meta_index" value="index"
                                                    {{ (isset($pageData['index']) && $pageData['index'] != 'noindex') || (isset($pageData['index']) && $pageData['index'] == null) ? 'checked' : '' }}
                                                >
                                                <img class="unchecked"
                                                     src="{{ dynamicAsset('public/assets/back-end/img/uncheck-radio-icon.svg')}}"
                                                     alt="">
                                                <img class="checked"
                                                     src="{{ dynamicAsset('public/assets/back-end/img/check-radio-icon.svg')}}"
                                                     alt="">
                                                <span>{{ translate('index') }}</span>
                                                <span data-toggle="tooltip" title="{{ translate('allow_search_engines_to_put_this_web_page_on_their_list_or_index_and_show_it_on_search_results.') }}">
                                                    <img src="{{ dynamicAsset('public/assets/back-end/img/query.png')}}"
                                                         alt="">
                                                </span>
                                            </label>
                                            <label class="checkbox--item user-select-none">
                                                <input type="checkbox" name="meta_no_follow" value="1" {{ isset($pageData['no_follow']) && $pageData['no_follow'] ? 'checked' : '' }} class="input-no-index-sub-element">
                                                <img class="unchecked"
                                                     src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg')}}"
                                                     alt="">
                                                <img class="checked"
                                                     src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg')}}"
                                                     alt="">
                                                <span>{{ translate('no_Follow') }}</span>
                                                <span data-toggle="tooltip" title="{{ translate('instruct_search_engines_not_to_follow_links_from_this_web_page.') }}">
                                                    <img src="{{ dynamicAsset('public/assets/back-end/img/query.png')}}"
                                                         alt="">
                                                </span>
                                            </label>
                                            <label class="checkbox--item user-select-none">
                                                <input type="checkbox" name="meta_no_image_index" value="1" {{ isset($pageData['no_image_index']) && $pageData['no_image_index'] ? 'checked' : '' }} class="input-no-index-sub-element">
                                                <img class="unchecked"
                                                     src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg')}}"
                                                     alt="">
                                                <img class="checked"
                                                     src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg')}}"
                                                     alt="">
                                                <span>{{ translate('No_Image_Index') }}</span>
                                                <span data-toggle="tooltip" title="{{ translate('prevents_images_from_being_listed_or_indexed_by_search_engines') }}">
                                                    <img src="{{ dynamicAsset('public/assets/back-end/img/query.png')}}"
                                                         alt="">
                                                </span>
                                            </label>
                                        </div>
                                        <div class="item">
                                            <label class="checkbox--item user-select-none">
                                                <input type="radio" name="meta_index" value="noindex" class="action-input-no-index-event"
                                                    {{ (isset($pageData['index']) && $pageData['index'] != 'noindex') || (isset($pageData['index']) && $pageData['index'] == null) ? '' : 'checked' }}
                                                >
                                                <img class="unchecked"
                                                     src="{{ dynamicAsset('public/assets/back-end/img/uncheck-radio-icon.svg')}}"
                                                     alt="">
                                                <img class="checked"
                                                     src="{{ dynamicAsset('public/assets/back-end/img/check-radio-icon.svg')}}"
                                                     alt="">
                                                <span>{{ translate('no_index') }}</span>
                                                <span data-toggle="tooltip" title="{{ translate('disallow_search_engines_to_put_this_web_page_on_their_list_or_index_and_do_not_show_it_on_search_results.') }}">
                                                    <img src="{{ dynamicAsset('public/assets/back-end/img/query.png')}}"
                                                         alt="">
                                                </span>
                                            </label>
                                            <label class="checkbox--item user-select-none">
                                                <input type="checkbox" name="meta_no_archive" value="1" {{ isset($pageData['no_archive']) && $pageData['no_archive'] ? 'checked' : '' }} class="input-no-index-sub-element">
                                                <img class="unchecked" alt=""
                                                     src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg')}}">
                                                <img class="checked" alt=""
                                                     src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg')}}">
                                                <span>{{ translate('No_Archive') }}</span>
                                                <span data-toggle="tooltip" title="{{ translate('instruct_search_engines_not_to_display_this_webpages_cached_or_saved_version.') }}">
                                                    <img src="{{ dynamicAsset('public/assets/back-end/img/query.png')}}"
                                                         alt="">
                                                </span>
                                            </label>
                                            <label class="checkbox--item user-select-none">
                                                <input type="checkbox" name="meta_no_snippet" value="1" {{ isset($pageData['no_snippet']) && $pageData['no_snippet'] ? 'checked' : '' }} class="input-no-index-sub-element">
                                                <img class="unchecked"
                                                     src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg')}}"
                                                     alt="">
                                                <img class="checked"
                                                     src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg')}}"
                                                     alt="">
                                                <span>{{ translate('No_Snippet') }}</span>
                                                <span data-toggle="tooltip" title="{{ translate('instruct_search_engines_not_to_show_a_summary_or_snippet_of_this_webpages_content_in_search_results.') }}">
                                                    <img src="{{ dynamicAsset('public/assets/back-end/img/query.png')}}"
                                                         alt="">
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-5">
                                    <div class="robots-meta-checkbox-card d-flex flex-column gap-2 h-100">
                                        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                                            <div class="item">
                                                <label class="checkbox--item m-0 user-select-none">
                                                    <input type="checkbox" name="meta_max_snippet" value="1" {{ isset($pageData['max_snippet']) && $pageData['max_snippet'] ? 'checked' : '' }}>
                                                    <img class="unchecked"
                                                         src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg')}}"
                                                         alt="">
                                                    <img class="checked"
                                                         src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg')}}"
                                                         alt="">
                                                    <span>{{ translate('max_Snippet') }}</span>
                                                    <span data-toggle="tooltip" title="{{ translate('determine_the_maximum_length_of_a_snippet_or_preview_text_of_the_webpage.') }}">
                                                        <img
                                                            src="{{ dynamicAsset('public/assets/back-end/img/query.png')}}"
                                                            alt="">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="item w-120px flex-grow-0">
                                                <input type="text" placeholder="-1" class="form-control h-30 py-0" name="meta_max_snippet_value"
                                                       value="{{ $pageData['max_snippet_value'] ?? '-1' }}">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                                            <div class="item">
                                                <label class="checkbox--item m-0 user-select-none">
                                                    <input type="checkbox" name="meta_max_video_preview" value="1" {{ isset($pageData['max_video_preview']) && $pageData['max_video_preview'] ? 'checked' : '' }}>
                                                    <img class="unchecked"
                                                         src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg')}}"
                                                         alt="">
                                                    <img class="checked"
                                                         src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg')}}"
                                                         alt="">
                                                    <span>{{ translate('max_Video_Preview') }}</span>
                                                    <span data-toggle="tooltip" title="{{ translate('determine_the_maximum_duration_of_a_video_preview_that_search_engines_will_display') }}">
                                                        <img
                                                            src="{{ dynamicAsset('public/assets/back-end/img/query.png')}}"
                                                            alt="">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="item w-120px flex-grow-0">
                                                <input type="text" placeholder="-1" class="form-control h-30 py-0" name="meta_max_video_preview_value"
                                                       value="{{ $pageData['max_video_preview_value'] ?? '-1' }}">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                                            <div class="item">
                                                <label class="checkbox--item m-0 user-select-none">
                                                    <input type="checkbox" name="meta_max_image_preview" value="1" {{ isset($pageData['max_image_preview']) && $pageData['max_image_preview'] ? 'checked' : '' }}>
                                                    <img class="unchecked" alt=""
                                                         src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg')}}">
                                                    <img class="checked" alt=""
                                                         src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg')}}">
                                                    <span>{{ translate('max_Image_Preview') }}</span>
                                                    <span data-toggle="tooltip" title="{{ translate('determine_the_maximum_size_or_dimensions_of_an_image_preview_that_search_engines_will_display.') }}">
                                                        <img alt=""
                                                            src="{{ dynamicAsset('public/assets/back-end/img/query.png')}}">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="item w-120px flex-grow-0">
                                                <select class="form-control h-30 py-0" name="meta_max_image_preview_value">
                                                    <option value="large" {{ isset($pageData['max_image_preview_value']) && $pageData['max_image_preview_value'] == 'large' ? 'selected' : '' }}>{{ translate('large') }}</option>
                                                    <option value="medium" {{ isset($pageData['max_image_preview_value']) && $pageData['max_image_preview_value'] == 'medium' ? 'selected' : '' }}>{{ translate('medium') }}</option>
                                                    <option value="small" {{ isset($pageData['max_image_preview_value']) && $pageData['max_image_preview_value'] == 'small' ? 'selected' : '' }}>{{ translate('small') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-3 mt-3">
                        <button type="reset" class="btn btn-secondary px-5">
                            {{ translate('reset') }}
                        </button>
                        <button type="{{ env('APP_MODE') == 'demo' ? 'button' : 'submit' }}" class="btn btn--primary px-5 {{env('APP_MODE')!='demo'? '' : 'call-demo'}}">
                            {{ translate('submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@push('script')

@endpush
