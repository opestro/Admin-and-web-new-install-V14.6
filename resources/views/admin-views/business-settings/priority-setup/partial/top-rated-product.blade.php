<div class="card mt-2 top-rated-product">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="">
                    <h3 class="mb-3 text-capitalize">{{ translate('top_rated_products') }}</h3>
                    <p class="max-w-400">{{ translate('top_rated_products_are_the_mostly_ordered_product_list_of_customer_choice_which_are_highly_rated_&_reviewed') }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="{{ route('admin.business-settings.priority-setup.index', ['type'=>'top_rated_product_list']) }}" method="post">
                    @csrf
                    <div class="border rounded p-3 d-flex gap-4 flex-column">
                        <div class="d-flex gap-2 justify-content-between pb-3 border-bottom">
                            <div class="d-flex flex-column">
                                <h5 class="text-capitalize">{{ translate('use_default_sorting_list') }}</h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                    <span class="text-dark fz-12">{{translate('currently_sorting_this_section_based_on_review_count')}}</span>
                                </div>
                            </div>
                            <label class="switcher">
                                <input type="checkbox" class="switcher_input switcher-input-js" data-parent-class="top-rated-product" data-from="default-sorting"
                                       {{ $topRatedProductListPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
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
                                    <input type="checkbox" class="switcher_input switcher-input-js" name="custom_sorting_status" value="1" data-parent-class="top-rated-product" data-from="custom-sorting"
                                        {{isset($topRatedProductListPriority?->custom_sorting_status) && $topRatedProductListPriority?->custom_sorting_status == 1 ? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                            <div class="custom-sorting-radio-list {{isset($topRatedProductListPriority?->custom_sorting_status) && $topRatedProductListPriority?->custom_sorting_status == 1 ? '' : 'd--none'}}">

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="minimum_rating_point" value="4" id="top-rated-product-minimum-rating-4"
                                            {{ isset($topRatedProductListPriority?->minimum_rating_point) ? ($topRatedProductListPriority?->minimum_rating_point == '4' ? 'checked' : '') : ''}}>
                                        <label class="mb-0" for="top-rated-product-minimum-rating-4">
                                            {{ translate('show_4+_rated_products') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="minimum_rating_point" value="3.5" id="top-rated-product-minimum-rating-3-5"
                                            {{isset($topRatedProductListPriority?->minimum_rating_point) && $topRatedProductListPriority?->minimum_rating_point == '3.5' ? 'checked' : ''}}>
                                        <label class="mb-0" for="top-rated-product-minimum-rating-3-5">
                                            {{ translate('show_3.5+_rated_products') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="minimum_rating_point" id="top-rated-product-minimum-rating-2" value="2"
                                            {{isset($topRatedProductListPriority?->minimum_rating_point) && $topRatedProductListPriority?->minimum_rating_point == '3' ? 'checked' : ''}}>
                                        <label class="mb-0" for="top-rated-product-minimum-rating-2">
                                            {{ translate('show_3+_rated_products') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="minimum_rating_point" id="top-rated-product-minimum-rating-0" value="default"
                                            {{ isset($topRatedProductListPriority?->minimum_rating_point) ? ($topRatedProductListPriority?->minimum_rating_point == 'default' ? 'checked' : '') : 'checked' }}>
                                        <label class="mb-0" for="top-rated-product-minimum-rating-0">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="most_order" id="top-rated-product-sort-by-most-order"
                                            {{isset($topRatedProductListPriority?->sort_by) ? ($topRatedProductListPriority?->sort_by == 'most_order' ? 'checked' : '') : 'checked'}}>
                                        <label class="mb-0" for="top-rated-product-sort-by-most-order">
                                            {{ translate('sort_by_most_order') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="reviews_count" id="top-rated-product-sort-by-reviews-count"
                                            {{isset($topRatedProductListPriority?->sort_by) && $topRatedProductListPriority?->sort_by == 'reviews_count' ? 'checked' : ''}}>
                                        <label class="mb-0" for="top-rated-product-sort-by-reviews-count">
                                            {{ translate('sort_by_reviews_count') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="rating" id="top-rated-product-sort-by-ratings"
                                            {{isset($topRatedProductListPriority?->sort_by) && $topRatedProductListPriority?->sort_by == 'rating' ? 'checked' : ''}}>
                                        <label class="mb-0" for="top-rated-product-sort-by-ratings">
                                            {{ translate('sort_by_average_ratings') }}
                                        </label>
                                    </div>

                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="desc" data-parent-class="top-rated-product" id="top-rated-product-stock-out-remove"
                                            {{isset($topRatedProductListPriority?->out_of_stock_product) && $topRatedProductListPriority?->out_of_stock_product == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0" for="top-rated-product-stock-out-remove">
                                            {{ translate('show_stock_out_products_in_the_last') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="hide" data-parent-class="top-rated-product" id="top-rated-product-stock-out-last"
                                            {{isset($topRatedProductListPriority?->out_of_stock_product) && $topRatedProductListPriority?->out_of_stock_product == 'hide' ? 'checked' : ''}}>
                                        <label class="mb-0" for="top-rated-product-stock-out-last">
                                            {{ translate('remove_stock_out_products_from_the_list') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="default" data-parent-class="top-rated-product" id="top-rated-product-stock-out-default"
                                            {{isset($topRatedProductListPriority?->out_of_stock_product) ? ($topRatedProductListPriority?->out_of_stock_product == 'default' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0" for="top-rated-product-stock-out-default">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="desc" data-parent-class="top-rated-product" id="top-rated-product-temporary-close-last"
                                            {{isset($topRatedProductListPriority?->temporary_close_sorting) && $topRatedProductListPriority?->temporary_close_sorting == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="top-rated-product-temporary-close-last">
                                            {{ translate('show_product_in_the_last_if_store_is_temporarily_off') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="hide" data-parent-class="top-rated-product" id="top-rated-product-temporary-close-remove"
                                            {{isset($topRatedProductListPriority?->temporary_close_sorting) ? ($topRatedProductListPriority?->temporary_close_sorting == 'hide' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="top-rated-product-temporary-close-remove">
                                            {{ translate('remove_product_from_the_list_if_store_is_temporarily_off') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="default" data-parent-class="top-rated-product" id="top-rated-product-temporary-close-default"
                                            {{isset($topRatedProductListPriority?->temporary_close_sorting) ?($topRatedProductListPriority?->temporary_close_sorting == 'default' ? 'checked' : '' ) : 'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="top-rated-product-temporary-close-default">
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
