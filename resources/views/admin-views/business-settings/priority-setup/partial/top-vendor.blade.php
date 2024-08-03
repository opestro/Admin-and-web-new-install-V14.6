<div class="card mt-2 top-vendor">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="">
                    <h3 class="mb-3 text-capitalize">{{ translate('top_vendor') }}</h3>
                    <p class="max-w-400">{{ translate('top_vendor_list_refers_to_displaying_a_list_based_on_most_ordered_items_of_that_vendor_and_highly_rated').'.'}}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="{{route('admin.business-settings.priority-setup.index',['type'=>'top_vendor'])}}" method="post">
                    @csrf
                    <div class="border rounded p-3 d-flex gap-4 flex-column">
                        <div class="d-flex gap-2 justify-content-between pb-3 border-bottom">
                            <div class="d-flex flex-column">
                                <h5 class="text-capitalize">{{ translate('use_default_sorting_list') }}</h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                    <span class="text-dark fz-12">
                                        {{ translate('currently_sorting_this_section_based_on_first_created') }}
                                    </span>
                                </div>
                            </div>
                            <label class="switcher">
                                <input type="checkbox" class="switcher_input switcher-input-js" data-parent-class="top-vendor" data-from="default-sorting"
                                    {{ $topVendorPriority?->custom_sorting_status == 1 ? '' : 'checked'}}>
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                        <div class="">
                            <div class="d-flex gap-2 justify-content-between">
                                <div class="d-flex flex-column">
                                    <h5 class="text-capitalize">{{ translate('use_custom_sorting_list') }}</h5>
                                    <div class="d-flex gap-2 align-items-center">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                        <span class="text-dark fz-12">{{ translate('you_can_sorting_this_section_by_others_way') }}</span>
                                    </div>
                                </div>
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input switcher-input-js" name="custom_sorting_status" value="1" data-parent-class="top-vendor" data-from="custom-sorting"
                                        {{isset($topVendorPriority?->custom_sorting_status) && $topVendorPriority?->custom_sorting_status == 1 ? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                            <div class="custom-sorting-radio-list {{isset($topVendorPriority?->custom_sorting_status) && $topVendorPriority?->custom_sorting_status == 1 ? '' : 'd--none'}}">
                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="minimum_rating_point" value="4" id="top-vendor-minimum-rating-4"
                                            {{ isset($topVendorPriority?->minimum_rating_point) ? ($topVendorPriority?->minimum_rating_point == '4' ? 'checked' : '') : ''}}>
                                        <label class="mb-0" for="top-vendor-minimum-rating-4">
                                            {{ translate('show_4+_rated_sellers') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="minimum_rating_point" value="3.5" id="top-vendor-minimum-rating-3-5"
                                            {{isset($topVendorPriority?->minimum_rating_point) && $topVendorPriority?->minimum_rating_point == '3.5' ? 'checked' : ''}}>
                                        <label class="mb-0" for="top-vendor-minimum-rating-3-5">
                                            {{ translate('show_3.5+_rated_sellers') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="minimum_rating_point" id="top-vendor-minimum-rating-2" value="2"
                                            {{isset($topVendorPriority?->minimum_rating_point) && $topVendorPriority?->minimum_rating_point == '2' ? 'checked' : ''}}>
                                        <label class="mb-0" for="top-vendor-minimum-rating-2">
                                            {{ translate('show_2+_rated_sellers') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="minimum_rating_point" id="top-vendor-minimum-rating-0" value="default"
                                        {{ isset($topVendorPriority?->minimum_rating_point) ? ($topVendorPriority?->minimum_rating_point == 'default' ? 'checked' : '') : 'checked' }}>
                                        <label class="mb-0" for="top-vendor-minimum-rating-0">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="order" id="top-vendor-sort-by-order"
                                            {{isset($topVendorPriority?->sort_by) ? ($topVendorPriority?->sort_by == 'order' ? 'checked' : '') : 'checked'}}>
                                        <label class="mb-0" for="top-vendor-sort-by-order">
                                            {{ translate('sort_by_order') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="reviews_count" id="top-vendor-sort-by-reviews-count"
                                            {{isset($topVendorPriority?->sort_by) && $topVendorPriority?->sort_by == 'reviews_count' ? 'checked' : ''}}>
                                        <label class="mb-0" for="top-vendor-sort-by-reviews-count">
                                            {{ translate('sort_by_reviews_count') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" id="top-vendor-sort-by-ratings" value="default"
                                            {{isset($topVendorPriority?->sort_by) && $topVendorPriority?->sort_by == 'rating' ? 'checked' : ''}}>
                                        <label class="mb-0" for="top-vendor-sort-by-ratings">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="vacation_mode_sorting" value="desc" data-parent-class="top-vendor" id="top-vendor-vacation-mode-last"
                                            {{isset($topVendorPriority?->vacation_mode_sorting) && $topVendorPriority?->vacation_mode_sorting == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="top-vendor-vacation-mode-last">
                                            {{ translate('show_currently_closed_stores_in_the_last') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="vacation_mode_sorting" value="hide" data-parent-class="top-vendor" id="top-vendor-vacation-mode-remove"
                                            {{isset($topVendorPriority?->vacation_mode_sorting) ? ($topVendorPriority?->vacation_mode_sorting == 'hide' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="top-vendor-vacation-mode-remove">
                                            {{ translate('remove_currently_closed_stores_from_the_list') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="vacation_mode_sorting" value="default" data-parent-class="top-vendor" id="top-vendor-vacation-mode-default"
                                            {{isset($topVendorPriority?->vacation_mode_sorting) ?( $topVendorPriority?->vacation_mode_sorting == 'default' ? 'checked' : '' ) : 'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="top-vendor-vacation-mode-default">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="desc" data-parent-class="top-vendor" id="top-vendor-temporary-close-last"
                                            {{isset($topVendorPriority?->temporary_close_sorting) && $topVendorPriority?->temporary_close_sorting == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="top-vendor-temporary-close-last">
                                            {{ translate('show_temporarily_off_stores_in_the_last') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="hide" data-parent-class="top-vendor" id="top-vendor-temporary-close-remove"
                                            {{isset($topVendorPriority?->temporary_close_sorting) ? ($topVendorPriority?->temporary_close_sorting == 'hide' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="top-vendor-temporary-close-remove">
                                            {{ translate('remove_temporarily_off_stores_from_the_list') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="default" data-parent-class="top-vendor" id="top-vendor-temporary-close-default"
                                            {{isset($topVendorPriority?->temporary_close_sorting) ?( $topVendorPriority?->temporary_close_sorting == 'default' ? 'checked' : '' ) : 'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="top-vendor-temporary-close-default">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-5">{{ translate('save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
