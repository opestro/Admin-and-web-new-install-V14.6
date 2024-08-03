<div class="card mt-2 searched-product-list">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="">
                    <h3 class="mb-3 text-capitalize">
                        {{ translate('products_list') }} ({{ translate('Search_Bar') }})
                    </h3>
                    <p class="max-w-400">{{ translate('the_product_list_(Search_Bar)_is_the_list_of_those_products_which_appear_during_search_based_on_product_availability') }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="{{route('admin.business-settings.priority-setup.index',['type'=>'searched_product_list'])}}" method="post">
                    @csrf
                    <div class="border rounded p-3 d-flex gap-4 flex-column">
                        <div class="d-flex gap-2 justify-content-between pb-3 border-bottom">
                            <div class="d-flex flex-column">
                                <h5 class="text-capitalize">{{ translate('use_default_sorting_list') }}</h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                    <span class="text-dark fz-12">{{translate('currently_sorting_this_section_by_keyword_wise')}}</span>
                                </div>
                            </div>
                            <label class="switcher">
                                <input type="checkbox" class="switcher_input switcher-input-js" data-parent-class="searched-product-list" data-from="default-sorting"
                                    {{ $searchedProductListPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                        <div class="">
                            <div class="d-flex gap-2 justify-content-between">
                                <div class="d-flex flex-column">
                                    <h5 class="text-capitalize">{{ translate('use_custom_sorting_list') }}</h5>
                                    <div class="d-flex gap-2 align-items-center">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                        <span class="text-dark fz-12 text-capitalize">
                                            {{ translate('you_can_sorting_this_section_by_others_way') }}
                                        </span>
                                    </div>
                                </div>
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input switcher-input-js" name="custom_sorting_status" value="1" data-parent-class="searched-product-list" data-from="custom-sorting"
                                        {{isset($searchedProductListPriority?->custom_sorting_status) && $searchedProductListPriority?->custom_sorting_status == 1 ? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                            <div class="custom-sorting-radio-list {{isset($searchedProductListPriority?->custom_sorting_status) && $searchedProductListPriority?->custom_sorting_status == 1 ? '' : 'd--none'}}">

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="desc" class="check-box" data-parent-class="searched-product-list" id="show-in-last"
                                            {{isset($searchedProductListPriority?->out_of_stock_product) && $searchedProductListPriority?->out_of_stock_product == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="show-in-last">
                                            {{ translate('show_stock_out_products_in_the_last') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="hide" class="check-box" data-parent-class="searched-product-list" id="remove-product"
                                            {{isset($searchedProductListPriority?->out_of_stock_product) && $searchedProductListPriority?->out_of_stock_product == 'hide' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="remove-product">
                                            {{ translate('remove_stock_out_products_from_the_list') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="out_of_stock_product" value="default" data-parent-class="searched-product-list" id="default"
                                            {{isset($searchedProductListPriority?->out_of_stock_product) ? ($searchedProductListPriority?->out_of_stock_product == 'default' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="default">
                                            {{ translate('none') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="desc" data-parent-class="searched-product-list" id="searched-product-list-temporary-close-last"
                                            {{isset($searchedProductListPriority?->temporary_close_sorting) && $searchedProductListPriority?->temporary_close_sorting == 'desc' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="searched-product-list-temporary-close-last">
                                            {{ translate('show_product_in_the_last_is_store_is_temporarily_off') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="hide" data-parent-class="searched-product-list" id="searched-product-list-temporary-close-remove"
                                            {{isset($searchedProductListPriority?->temporary_close_sorting) ? ($searchedProductListPriority?->temporary_close_sorting == 'hide' ? 'checked' : '') :'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="searched-product-list-temporary-close-remove">
                                            {{ translate('remove_product_from_the_list_if_store_is_temporarily_off') }}
                                        </label>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" name="temporary_close_sorting" value="default" data-parent-class="searched-product-list" id="searched-product-list-temporary-close-default"
                                            {{isset($searchedProductListPriority?->temporary_close_sorting) ?( $searchedProductListPriority?->temporary_close_sorting == 'default' ? 'checked' : '' ) : 'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="searched-product-list-temporary-close-default">
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
