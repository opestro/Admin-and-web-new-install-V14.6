<div class="card mt-2 category-wise-product">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="">
                    <h3 class="mb-3 text-capitalize">{{ translate('Category_wise_product_list') }}</h3>
                    <p class="max-w-400">{{ translate('category_or_subcategory_wise_product_list_is_for_displaying_the_products_which_are_mostly_ordered').', '.translate('_have_positive_reviews_&_ratings_and_in_alphabetical_order') }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="{{ route('admin.business-settings.priority-setup.index', ['type'=>'category_wise_product_list']) }}" method="post">
                    @csrf
                    <div class="border rounded p-3 d-flex gap-4 flex-column">
                        <div class="d-flex gap-2 justify-content-between pb-3 border-bottom">
                            <div class="d-flex flex-column">
                                <h5 class="text-capitalize">{{ translate('use_default_sorting_list') }}</h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                    <span class="text-dark fz-12">{{translate('currently_sorting_this_section_based_on_orders')}}</span>
                                </div>
                            </div>
                            <label class="switcher">
                                <input type="checkbox" class="switcher_input switcher-input-js" data-parent-class="category-wise-product" data-from="default-sorting"
                                       {{ $categoryWiseProductListPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
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
                                    <input type="checkbox" class="switcher_input switcher-input-js" name="custom_sorting_status" value="1" data-parent-class="category-wise-product" data-from="custom-sorting"
                                        {{isset($categoryWiseProductListPriority?->custom_sorting_status) && $categoryWiseProductListPriority?->custom_sorting_status == 1 ? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                            <div class="custom-sorting-radio-list {{isset($categoryWiseProductListPriority?->custom_sorting_status) && $categoryWiseProductListPriority?->custom_sorting_status == 1 ? '' : 'd--none'}}">
                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="most_order" id="category-wise-product-sort-by-most-order"
                                            {{isset($categoryWiseProductListPriority?->sort_by) ? ($categoryWiseProductListPriority?->sort_by == 'most_order' ? 'checked' : '') : 'checked'}}>
                                        <label class="mb-0" for="category-wise-product-sort-by-most-order">
                                            {{ translate('sort_by_most_order') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="reviews_count" id="category-wise-product-sort-by-reviews-count"
                                            {{isset($categoryWiseProductListPriority?->sort_by) && $categoryWiseProductListPriority?->sort_by == 'reviews_count' ? 'checked' : ''}}>
                                        <label class="mb-0" for="category-wise-product-sort-by-reviews-count">
                                            {{ translate('sort_by_reviews_count') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="rating" id="category-wise-product-sort-by-ratings"
                                            {{isset($categoryWiseProductListPriority?->sort_by) && $categoryWiseProductListPriority?->sort_by == 'rating' ? 'checked' : ''}}>
                                        <label class="mb-0" for="category-wise-product-sort-by-ratings">
                                            {{ translate('sort_by_average_ratings') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="a_to_z" id="category-wise-product-alphabetic-order"
                                            {{isset($categoryWiseProductListPriority?->sort_by) && $categoryWiseProductListPriority?->sort_by == 'a_to_z' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer text-capitalize" for="category-wise-product-alphabetic-order">
                                            {{ translate('sort_by_Alphabetical') }} ({{'A '.translate('to').' Z' }})
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="z_to_a" id="category-wise-product-alphabetic-order-reverse"
                                            {{isset($categoryWiseProductListPriority?->sort_by) && $categoryWiseProductListPriority?->sort_by == 'z_to_a' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer text-capitalize" for="category-wise-product-alphabetic-order-reverse">
                                            {{ translate('sort_by_Alphabetical') }} ({{'Z '.translate('to').' A' }})
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="desc" data-parent-class="category-wise-product" id="category-wise-product-stock-out-remove"
                                            {{isset($categoryWiseProductListPriority?->out_of_stock_product) && $categoryWiseProductListPriority?->out_of_stock_product == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0" for="category-wise-product-stock-out-remove">
                                            {{ translate('show_stock_out_products_in_the_last') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="hide" data-parent-class="category-wise-product" id="category-wise-product-stock-out-last"
                                            {{isset($categoryWiseProductListPriority?->out_of_stock_product) && $categoryWiseProductListPriority?->out_of_stock_product == 'hide' ? 'checked' : ''}}>
                                        <label class="mb-0" for="category-wise-product-stock-out-last">
                                            {{ translate('remove_stock_out_products_from_the_list') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="default" data-parent-class="category-wise-product" id="category-wise-product-stock-out-default"
                                            {{isset($categoryWiseProductListPriority?->out_of_stock_product) ? ($categoryWiseProductListPriority?->out_of_stock_product == 'default' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0" for="category-wise-product-stock-out-default">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="desc" data-parent-class="category-wise-product" id="category-wise-product-temporary-close-last"
                                            {{isset($categoryWiseProductListPriority?->temporary_close_sorting) && $categoryWiseProductListPriority?->temporary_close_sorting == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="category-wise-product-temporary-close-last">
                                            {{ translate('show_product_in_the_last_if_store_is_temporarily_off') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="hide" data-parent-class="category-wise-product" id="category-wise-product-temporary-close-remove"
                                            {{isset($categoryWiseProductListPriority?->temporary_close_sorting) ? ($categoryWiseProductListPriority?->temporary_close_sorting == 'hide' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="category-wise-product-temporary-close-remove">
                                            {{ translate('remove_product_from_the_list_if_store_is_temporarily_off') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="default" data-parent-class="category-wise-product" id="category-wise-product-temporary-close-default"
                                            {{isset($categoryWiseProductListPriority?->temporary_close_sorting) ?($categoryWiseProductListPriority?->temporary_close_sorting == 'default' ? 'checked' : '' ) : 'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="category-wise-product-temporary-close-default">
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
