<div class="card mt-2 feature-product">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="">
                    <h3 class="mb-3 text-capitalize">{{ translate('vendor_product_list') }}</h3>
                    <p class="max-w-400">{{ translate('the_vendor_product_list_is_for_displaying_the_products_which_are_mostly_ordered').', '.translate('_have_good_reviews_&_sorted_alphabetically') }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="{{route('admin.business-settings.priority-setup.index', ['type'=>'vendor_product_list'])}}" method="post">
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
                                <input type="checkbox" class="switcher_input switcher-input-js" data-parent-class="feature-product" data-from="default-sorting"
                                       {{ $vendorProductListPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
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
                                    <input type="checkbox" class="switcher_input switcher-input-js" name="custom_sorting_status" value="1" data-parent-class="feature-product" data-from="custom-sorting"
                                        {{isset($vendorProductListPriority?->custom_sorting_status) && $vendorProductListPriority?->custom_sorting_status == 1 ? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                            <div class="custom-sorting-radio-list {{isset($vendorProductListPriority?->custom_sorting_status) && $vendorProductListPriority?->custom_sorting_status == 1 ? '' : 'd--none'}}">
                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="most_order" id="feature-product-sort-by-most-order"
                                            {{isset($vendorProductListPriority?->sort_by) ? ($vendorProductListPriority?->sort_by == 'most_order' ? 'checked' : '') : 'checked'}}>
                                        <label class="mb-0" for="feature-product-sort-by-most-order">
                                            {{ translate('sort_by_most_order') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="reviews_count" id="feature-product-sort-by-reviews-count"
                                            {{isset($vendorProductListPriority?->sort_by) && $vendorProductListPriority?->sort_by == 'reviews_count' ? 'checked' : ''}}>
                                        <label class="mb-0" for="feature-product-sort-by-reviews-count">
                                            {{ translate('sort_by_reviews_count') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="rating" id="feature-product-sort-by-ratings"
                                            {{isset($vendorProductListPriority?->sort_by) && $vendorProductListPriority?->sort_by == 'rating' ? 'checked' : ''}}>
                                        <label class="mb-0" for="feature-product-sort-by-ratings">
                                            {{ translate('sort_by_average_ratings') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="a_to_z" id="feature-product-alphabetic-order"
                                            {{isset($vendorProductListPriority?->sort_by) && $vendorProductListPriority?->sort_by == 'a_to_z' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer text-capitalize" for="feature-product-alphabetic-order">
                                            {{ translate('sort_by_Alphabetical') }} ({{'A '.translate('to').' Z' }})
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="z_to_a" id="feature-product-alphabetic-order-reverse"
                                            {{isset($vendorProductListPriority?->sort_by) && $vendorProductListPriority?->sort_by == 'z_to_a' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer text-capitalize" for="feature-product-alphabetic-order-reverse">
                                            {{ translate('sort_by_Alphabetical') }} ({{'Z '.translate('to').' A' }})
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="desc" data-parent-class="feature-product" id="feature-product-stock-out-remove"
                                            {{isset($vendorProductListPriority?->out_of_stock_product) && $vendorProductListPriority?->out_of_stock_product == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0" for="feature-product-stock-out-remove">
                                            {{ translate('show_stock_out_products_in_the_last') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="hide" data-parent-class="feature-product" id="feature-product-stock-out-last"
                                            {{isset($vendorProductListPriority?->out_of_stock_product) && $vendorProductListPriority?->out_of_stock_product == 'hide' ? 'checked' : ''}}>
                                        <label class="mb-0" for="feature-product-stock-out-last">
                                            {{ translate('remove_stock_out_products_from_the_list') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="default" data-parent-class="feature-product" id="feature-product-stock-out-default"
                                            {{isset($vendorProductListPriority?->out_of_stock_product) ? ($vendorProductListPriority?->out_of_stock_product == 'default' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0" for="feature-product-stock-out-default">
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
