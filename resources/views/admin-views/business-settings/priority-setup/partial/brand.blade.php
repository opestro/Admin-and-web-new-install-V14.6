<div class="card mt-2 brand">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="">
                    <h3 class="mb-3 text-capitalize">{{ translate('brand') }}</h3>
                    <p class="max-w-400">{{ translate('brands_are_lists_of_the_specific_products').', '. translate('_organize_by_putting_the_newest_ones_at_the_top_and_arranging_everything_alphabetically').'.'}}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="{{route('admin.business-settings.priority-setup.index',['type'=>'brand'])}}" method="post">
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
                                <input type="checkbox" class="switcher_input switcher-input-js" data-parent-class="brand" data-from="default-sorting"
                                    {{ $brandPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
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
                                    <input type="checkbox" class="switcher_input switcher-input-js" name="custom_sorting_status" value="1" data-parent-class="brand" data-from="custom-sorting"
                                        {{isset($brandPriority?->custom_sorting_status) && $brandPriority?->custom_sorting_status == 1 ? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                            <div class="custom-sorting-radio-list {{isset($brandPriority?->custom_sorting_status) && $brandPriority?->custom_sorting_status == 1 ? '' : 'd--none'}}">
                                <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="latest_created" id="brand-sort-by-latest-created"
                                            {{isset($brandPriority?->sort_by) && $brandPriority?->sort_by == 'latest_created' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="brand-sort-by-latest-created">
                                            {{ translate('sort_by_latest_created') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="first_created" id="brand-sort-by-first-created"
                                            {{isset($brandPriority?->sort_by) && $brandPriority?->sort_by == 'first_created' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="brand-sort-by-first-created">
                                            {{ translate('sort_by_first_created') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="most_order" id="brand-sort-by-most-order"
                                            {{isset($brandPriority?->sort_by) ? ($brandPriority?->sort_by == 'most_order' ? 'checked' : '') : 'checked'}}>
                                        <label class="mb-0 cursor-pointer" for="brand-sort-by-most-order">
                                            {{ translate('sort_by_most_order') }}
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="a_to_z" id="brand-alphabetic-order"
                                            {{isset($brandPriority?->sort_by) && $brandPriority?->sort_by == 'a_to_z' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="brand-alphabetic-order">
                                            {{ translate('sort_by_Alphabetical') }} ({{'A '.translate('to').' Z' }})
                                        </label>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="radio" class="show" name="sort_by" value="z_to_a" id="brand-alphabetic-order-reverse"
                                            {{isset($brandPriority?->sort_by) && $brandPriority?->sort_by == 'z_to_a' ? 'checked' : ''}}>
                                        <label class="mb-0 cursor-pointer" for="brand-alphabetic-order-reverse">
                                            {{ translate('sort_by_Alphabetical') }} ({{'Z '.translate('to').' A' }})
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
