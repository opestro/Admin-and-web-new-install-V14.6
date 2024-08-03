<div class="card mt-2 vendor-list">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="">
                    <h3 class="mb-3 text-capitalize">{{ translate('vendor_list') }}</h3>
                    <p class="max-w-400">{{ translate('the_Vendor_list_arranges_all_vendors_based_on_the_latest_join_that_are_highly_rated_by_customer_choice_and_also_in_alphabetic_order').'.'}}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="{{route('admin.business-settings.priority-setup.index',['type'=>'vendor_list'])}}" method="post">
                    @csrf
                    <div class="border rounded p-3 d-flex gap-4 flex-column">
                        <div class="d-flex gap-2 justify-content-between pb-3 border-bottom">
                            <div class="d-flex flex-column">
                                <h5 class="text-capitalize">{{ translate('use_default_sorting_list') }}</h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                    <span class="text-dark fz-12">{{translate('currently_sorting_this_section_based_on_first_created')}}</span>
                                </div>
                            </div>
                            <label class="switcher">
                                <input type="checkbox" class="switcher_input switcher-input-js" data-parent-class="vendor-list" data-from="default-sorting"
                                    {{ $vendorListPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
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
                                    <input type="checkbox" class="switcher_input switcher-input-js" name="custom_sorting_status" value="1" data-parent-class="vendor-list" data-from="custom-sorting"
                                        {{isset($vendorListPriority?->custom_sorting_status) && $vendorListPriority?->custom_sorting_status == 1 ? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                            <div class="custom-sorting-radio-list {{isset($vendorListPriority?->custom_sorting_status) && $vendorListPriority?->custom_sorting_status == 1 ? '' : 'd--none'}}">
                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="latest_created" id="vendor-list-sort-by-latest-created"
                                            {{isset($vendorListPriority?->sort_by) && $vendorListPriority?->sort_by == 'latest_created' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-sort-by-latest-created">
                                            {{ translate('sort_by_latest_created') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="first_created" id="vendor-list-sort-by-first-created"
                                            {{isset($vendorListPriority?->sort_by) && $vendorListPriority?->sort_by == 'first_created' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-sort-by-first-created">
                                            {{ translate('sort_by_first_created') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="most_order" id="vendor-list-sort-by-most-order"
                                            {{isset($vendorListPriority?->sort_by) ? ($vendorListPriority?->sort_by == 'most_order' ? 'checked' : '') : 'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-sort-by-most-order">
                                            {{ translate('sort_by_most_order') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" id="vendor-list-sort-by-reviews-count" value="reviews_count"
                                            {{isset($vendorListPriority?->sort_by) && $vendorListPriority?->sort_by == 'reviews_count' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-sort-by-reviews-count">
                                            {{ translate('sort_by_reviews_count') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" id="vendor-list-sort-by-ratings" value="rating"
                                            {{isset($vendorListPriority?->sort_by) && $vendorListPriority?->sort_by == 'rating' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-sort-by-ratings">
                                            {{ translate('sort_by_average_ratings') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="a_to_z" id="vendor-list-alphabetic-order"
                                            {{isset($vendorListPriority?->sort_by) && $vendorListPriority?->sort_by == 'a_to_z' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer text-capitalize" for="vendor-list-alphabetic-order">
                                            {{ translate('sort_by_Alphabetical') }} ({{'A '.translate('to').' Z' }})
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="z_to_a" id="vendor-list-alphabetic-order-reverse"
                                            {{isset($vendorListPriority?->sort_by) && $vendorListPriority?->sort_by == 'z_to_a' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer text-capitalize" for="vendor-list-alphabetic-order-reverse">
                                            {{ translate('sort_by_Alphabetical') }} ({{'Z '.translate('to').' A' }})
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="vacation_mode_sorting" value="desc" data-parent-class="vendor-list" id="vendor-list-vacation-mode-last"
                                            {{isset($vendorListPriority?->vacation_mode_sorting) && $vendorListPriority?->vacation_mode_sorting == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-vacation-mode-last">
                                            {{ translate('show_currently_closed_stores_in_the_last') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="vacation_mode_sorting" value="hide" data-parent-class="vendor-list" id="vendor-list-vacation-mode-remove"
                                            {{isset($vendorListPriority?->vacation_mode_sorting) ? ($vendorListPriority?->vacation_mode_sorting == 'hide' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-vacation-mode-remove">
                                            {{ translate('remove_currently_closed_stores_from_the_list') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="vacation_mode_sorting" value="default" data-parent-class="vendor-list" id="vendor-list-vacation-mode-default"
                                            {{isset($vendorListPriority?->vacation_mode_sorting) ?( $vendorListPriority?->vacation_mode_sorting == 'default' ? 'checked' : '' ) : 'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-vacation-mode-default">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="desc" data-parent-class="vendor-list" id="vendor-list-temporary-close-last"
                                            {{isset($vendorListPriority?->temporary_close_sorting) && $vendorListPriority?->temporary_close_sorting == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-temporary-close-last">
                                            {{ translate('show_temporarily_off_stores_in_the_last') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="hide" data-parent-class="vendor-list" id="vendor-list-temporary-close-remove"
                                            {{isset($vendorListPriority?->temporary_close_sorting) ? ($vendorListPriority?->temporary_close_sorting == 'hide' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-temporary-close-remove">
                                            {{ translate('remove_temporarily_off_stores_from_the_list') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="default" data-parent-class="vendor-list" id="vendor-list-temporary-close-default"
                                            {{isset($vendorListPriority?->temporary_close_sorting) ?( $vendorListPriority?->temporary_close_sorting == 'default' ? 'checked' : '' ) : 'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="vendor-list-temporary-close-default">
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
