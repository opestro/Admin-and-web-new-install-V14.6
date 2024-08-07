<div class="row g-4">
    <div class="col-lg-6 col-xl-5">
        <div class="robots-meta-checkbox-card d-flex flex-wrap gap-2 justify-content-between h-100">
            <div class="item">
                <label class="checkbox--item">
                    <input type="radio" name="meta_index" value="index" checked>
                    <img class="unchecked"
                         src="{{ dynamicAsset('public/assets/back-end/img/uncheck-radio-icon.svg') }}"
                         alt="">
                    <img class="checked"
                         src="{{ dynamicAsset('public/assets/back-end/img/check-radio-icon.svg') }}"
                         alt="">
                    <span class="user-select-none">{{ translate('Index') }}</span>
                    <span data-toggle="tooltip" title="{{ translate('allow_search_engines_to_put_this_web_page_on_their_list_or_index_and_show_it_on_search_results.') }}">
                        <img src="{{ dynamicAsset('public/assets/back-end/img/query.png') }}" alt="">
                    </span>
                </label>
                <label class="checkbox--item">
                    <input type="checkbox" name="meta_no_follow" value="1" class="input-no-index-sub-element">
                    <img class="unchecked" src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg') }}" alt="">
                    <img class="checked" src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg') }}" alt="">
                    <span class="user-select-none">{{ translate('No_Follow') }}</span>
                    <span data-toggle="tooltip" title="{{ translate('instruct_search_engines_not_to_follow_links_from_this_web_page.') }}">
                        <img src="{{ dynamicAsset('public/assets/back-end/img/query.png') }}" alt="">
                    </span>
                </label>
                <label class="checkbox--item">
                    <input type="checkbox" name="meta_no_image_index" value="1" class="input-no-index-sub-element">
                    <img class="unchecked" src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg') }}" alt="">
                    <img class="checked" src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg') }}" alt="">
                    <span class="user-select-none">{{ translate('No_Image_Index') }}</span>
                    <span data-toggle="tooltip" title="{{ translate('prevents_images_from_being_listed_or_indexed_by_search_engines') }}">
                        <img src="{{ dynamicAsset('public/assets/back-end/img/query.png') }}" alt="">
                    </span>
                </label>
            </div>
            <div class="item">
                <label class="checkbox--item">
                    <input type="radio" name="meta_index" value="noindex" class="action-input-no-index-event">
                    <img class="unchecked"
                         src="{{ dynamicAsset('public/assets/back-end/img/uncheck-radio-icon.svg') }}"
                         alt="">
                    <img class="checked"
                         src="{{ dynamicAsset('public/assets/back-end/img/check-radio-icon.svg') }}"
                         alt="">
                    <span class="user-select-none">{{ translate('no_index') }}</span>
                    <span data-toggle="tooltip" title="{{ translate('disallow_search_engines_to_put_this_web_page_on_their_list_or_index_and_do_not_show_it_on_search_results.') }}">
                        <img src="{{ dynamicAsset('public/assets/back-end/img/query.png') }}" alt="">
                    </span>
                </label>
                <label class="checkbox--item">
                    <input type="checkbox" name="meta_no_archive" value="1" class="input-no-index-sub-element">
                    <img class="unchecked" src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg') }}" alt="">
                    <img class="checked" src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg') }}" alt="">
                    <span class="user-select-none">{{ translate('No_Archive') }}</span>
                    <span data-toggle="tooltip" title="{{ translate('instruct_search_engines_not_to_display_this_webpages_cached_or_saved_version.') }}">
                        <img src="{{ dynamicAsset('public/assets/back-end/img/query.png') }}" alt="">
                    </span>
                </label>
                <label class="checkbox--item">
                    <input type="checkbox" name="meta_no_snippet" value="1" class="input-no-index-sub-element">
                    <img class="unchecked" src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg') }}" alt="">
                    <img class="checked" src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg') }}" alt="">
                    <span class="user-select-none">
                        {{ translate('No_Snippet') }}
                    </span>
                    <span data-toggle="tooltip" title="{{ translate('instruct_search_engines_not_to_show_a_summary_or_snippet_of_this_webpages_content_in_search_results.') }}">
                        <img src="{{ dynamicAsset('public/assets/back-end/img/query.png') }}" alt="">
                    </span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-5">
        <div class="robots-meta-checkbox-card d-flex flex-column gap-2 h-100">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <div class="item">
                    <label class="checkbox--item m-0">
                        <input type="checkbox" name="meta_max_snippet" value="1">
                        <img class="unchecked" src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg') }}" alt="">
                        <img class="checked" src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg') }}" alt="">
                        <span class="user-select-none">
                            {{ translate('max_Snippet') }}
                        </span>
                        <span data-toggle="tooltip" title="{{ translate('determine_the_maximum_length_of_a_snippet_or_preview_text_of_the_webpage.') }}">
                            <img src="{{ dynamicAsset('public/assets/back-end/img/query.png') }}" alt="">
                        </span>
                    </label>
                </div>
                <div class="item w-120px flex-grow-0">
                    <input type="number" placeholder="-1" class="form-control h-30 py-0" name="meta_max_snippet_value" value="-1">
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <div class="item">
                    <label class="checkbox--item m-0">
                        <input type="checkbox" name="meta_max_video_preview" value="1">
                        <img class="unchecked" src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg') }}" alt="">
                        <img class="checked" src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg') }}" alt="">
                        <span class="user-select-none">
                            {{ translate('max_Video_Preview') }}
                        </span>
                        <span data-toggle="tooltip" title="{{ translate('determine_the_maximum_duration_of_a_video_preview_that_search_engines_will_display') }}">
                            <img src="{{ dynamicAsset('public/assets/back-end/img/query.png') }}" alt="">
                        </span>
                    </label>
                </div>
                <div class="item w-120px flex-grow-0">
                    <input type="number" placeholder="-1" class="form-control h-30 py-0" name="meta_max_video_preview_value" value="-1">
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <div class="item">
                    <label class="checkbox--item m-0">
                        <input type="checkbox" name="meta_max_image_preview" value="1">
                        <img class="unchecked" src="{{ dynamicAsset('public/assets/back-end/img/uncheck-icon.svg') }}" alt="">
                        <img class="checked" src="{{ dynamicAsset('public/assets/back-end/img/check-icon.svg') }}" alt="">
                        <span class="user-select-none">{{ translate('max_Image_Preview') }}</span>
                        <span data-toggle="tooltip" title="{{ translate('determine_the_maximum_size_or_dimensions_of_an_image_preview_that_search_engines_will_display.') }}">
                            <img src="{{ dynamicAsset('public/assets/back-end/img/query.png') }}" alt="">
                        </span>
                    </label>
                </div>
                <div class="item w-120px flex-grow-0">
                    <select class="form-control h-30 py-0" name="meta_max_image_preview_value">
                        <option value="large">{{ translate('large') }}</option>
                        <option value="medium">{{ translate('medium') }}</option>
                        <option value="small">{{ translate('small') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
