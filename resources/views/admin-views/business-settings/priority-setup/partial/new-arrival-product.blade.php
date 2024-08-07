<div class="card mt-2 new-arrival-product">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="">
                    <h3 class="mb-3 text-capitalize">{{ translate('new_arrival_products') }}</h3>
                    <p class="max-w-400">{{ translate('these_new_arrival_products_are_items_recently_added_to_the_list_within_a_specific_time_frame_and_have_positive_reviews_&_ratings') }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="{{ route('admin.business-settings.priority-setup.index', ['type'=>'new_arrival_product_list']) }}" method="post">
                    @csrf
                    <div class="border rounded p-3 d-flex gap-4 flex-column">
                        <div class="d-flex gap-2 justify-content-between pb-3 border-bottom">
                            <div class="d-flex flex-column">
                                <h5 class="text-capitalize">{{ translate('use_default_sorting_list') }}</h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                    <span class="text-dark fz-12">{{translate('currently_sorting_this_section_based_on_latest_add')}}</span>
                                </div>
                            </div>
                            <label class="switcher">
                                <input type="checkbox" class="switcher_input switcher-input-js" data-parent-class="new-arrival-product" data-from="default-sorting"
                                       {{ $newArrivalProductListPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                        <div class="">
                            <div class="d-flex gap-2 justify-content-between">
                                <div class="d-flex flex-column">
                                    <h5 class="text-capitalize">{{ translate('use_custom_sorting_list') }}</h5>
                                    <div class="d-flex gap-2 align-items-center">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                        <span class="text-dark fz-12">
                                            {{ translate('you_can_sorting_this_section_by_others_way') }}
                                        </span>
                                    </div>
                                </div>
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input switcher-input-js" name="custom_sorting_status" value="1" data-parent-class="new-arrival-product" data-from="custom-sorting"
                                        {{isset($newArrivalProductListPriority?->custom_sorting_status) && $newArrivalProductListPriority?->custom_sorting_status == 1 ? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                            <div class="custom-sorting-radio-list {{isset($newArrivalProductListPriority?->custom_sorting_status) && $newArrivalProductListPriority?->custom_sorting_status == 1 ? '' : 'd--none'}}">

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">
                                    <h6 class="mb-0 text-capitalize">{{ translate('set_duration') }}</h6>
                                    <p>
                                        {{ translate('products_are_considered_as') }} <span class="font-weight-bold text-capitalize">{{ translate('new_arrival') }}</span>,
                                        {{ translate('if_it_is_added_with_in').' X '.translate('Days').'/'.translate('months)') }}
                                    </p>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="duration" min="1" placeholder="{{ translate('ex').': 5' }}" value="{{ isset($newArrivalProductListPriority?->duration) ? $newArrivalProductListPriority->duration : 1 }}" required>
                                        <div class="input-group-append">
                                            <select class="form-control outline-0 px-5 border-radius-end-top-bottom" name="duration_type">
                                                <option value="days" {{isset($newArrivalProductListPriority?->duration_type) && $newArrivalProductListPriority?->duration_type == 'days' ? 'selected' : ''}}>{{ translate('Days') }}</option>
                                                <option value="month" {{isset($newArrivalProductListPriority?->duration_type) && $newArrivalProductListPriority?->duration_type == 'month' ? 'selected' : ''}}>{{ translate('Month') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="latest_created" id="new-arrival-product-sort-by-latest-created"
                                            {{isset($newArrivalProductListPriority?->sort_by) && $newArrivalProductListPriority?->sort_by == 'latest_created' ? 'checked' : ''}}>
                                        <label class="mb-0" for="new-arrival-product-sort-by-latest-created">
                                            {{ translate('sort_by_latest_created') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="reviews_count" id="new-arrival-product-sort-by-reviews-count"
                                            {{isset($newArrivalProductListPriority?->sort_by) && $newArrivalProductListPriority?->sort_by == 'reviews_count' ? 'checked' : ''}}>
                                        <label class="mb-0" for="new-arrival-product-sort-by-reviews-count">
                                            {{ translate('sort_by_reviews_count') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="rating" id="new-arrival-product-sort-by-ratings"
                                            {{isset($newArrivalProductListPriority?->sort_by) && $newArrivalProductListPriority?->sort_by == 'rating' ? 'checked' : ''}}>
                                        <label class="mb-0" for="new-arrival-product-sort-by-ratings">
                                            {{ translate('sort_by_average_ratings') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="a_to_z" id="new-arrival-product-alphabetic-order"
                                            {{isset($newArrivalProductListPriority?->sort_by) && $newArrivalProductListPriority?->sort_by == 'a_to_z' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer text-capitalize" for="new-arrival-product-alphabetic-order">
                                            {{ translate('sort_by_Alphabetical') }} ({{'A '.translate('to').' Z' }})
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="z_to_a" id="new-arrival-product-alphabetic-order-reverse"
                                            {{isset($newArrivalProductListPriority?->sort_by) && $newArrivalProductListPriority?->sort_by == 'z_to_a' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer text-capitalize" for="new-arrival-product-alphabetic-order-reverse">
                                            {{ translate('sort_by_Alphabetical') }} ({{'Z '.translate('to').' A' }})
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="desc" data-parent-class="new-arrival-product" id="new-arrival-product-stock-out-remove"
                                            {{isset($newArrivalProductListPriority?->out_of_stock_product) && $newArrivalProductListPriority?->out_of_stock_product == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0" for="new-arrival-product-stock-out-remove">
                                            {{ translate('show_stock_out_products_in_the_last') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="hide" data-parent-class="new-arrival-product" id="new-arrival-product-stock-out-last"
                                            {{isset($newArrivalProductListPriority?->out_of_stock_product) && $newArrivalProductListPriority?->out_of_stock_product == 'hide' ? 'checked' : ''}}>
                                        <label class="mb-0" for="new-arrival-product-stock-out-last">
                                            {{ translate('remove_stock_out_products_from_the_list') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="default" data-parent-class="new-arrival-product" id="new-arrival-product-stock-out-default"
                                            {{isset($newArrivalProductListPriority?->out_of_stock_product) ? ($newArrivalProductListPriority?->out_of_stock_product == 'default' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0" for="new-arrival-product-stock-out-default">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="desc" data-parent-class="new-arrival-product" id="new-arrival-product-temporary-close-last"
                                            {{isset($newArrivalProductListPriority?->temporary_close_sorting) && $newArrivalProductListPriority?->temporary_close_sorting == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="new-arrival-product-temporary-close-last">
                                            {{ translate('show_product_in_the_last_if_store_is_temporarily_off') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="hide" data-parent-class="new-arrival-product" id="new-arrival-product-temporary-close-remove"
                                            {{isset($newArrivalProductListPriority?->temporary_close_sorting) ? ($newArrivalProductListPriority?->temporary_close_sorting == 'hide' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="new-arrival-product-temporary-close-remove">
                                            {{ translate('remove_product_from_the_list_if_store_is_temporarily_off') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="default" data-parent-class="new-arrival-product" id="new-arrival-product-temporary-close-default"
                                            {{isset($newArrivalProductListPriority?->temporary_close_sorting) ?($newArrivalProductListPriority?->temporary_close_sorting == 'default' ? 'checked' : '' ) : 'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="new-arrival-product-temporary-close-default">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            {{ translate('save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
