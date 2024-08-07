<div class="modal-header border-0 pb-0 d-flex justify-content-end">
    <button type="button" class="btn-close border-0 reload-by-onclick" data-dismiss="modal" aria-label="Close">
        <i class="tio-clear"></i></button>
</div>
<div class="modal-body px-4 px-sm-5 text-center">
    <div class="mb-3 text-center">
        <img width="75" src="{{dynamicAsset(path: 'public/assets/back-end/img/shift.png')}}" alt="">
    </div>
    <h3>
        {{ translate('you_have_switched_theme_successfully') }}
        <br>
        {{ translate('from').' '.ucwords(str_replace('_',' ', $currentTheme)).' '.translate('to').' '.ucwords(str_replace('_',' ', $themeInfo['name'])) }}
    </h3>
    <p>
        {{ translate('please_be_reminded_that_you_have_to_setup_data_for_these_section_for').' '. ucwords(str_replace('_',' ', $themeInfo['name'])).' '. translate('other_wise_these_section_data_would_not_function_properly_in_website_and_user_apps') }}
    </p>
    <div class="d-flex justify-content-center gap-3 my-5 flex-wrap">
        <?php
        $mergedArray = array_merge($currentThemeRoutes['route_list'], $themeRoutes['route_list']);
        $new_current_theme_routes = [];
        foreach ($currentThemeRoutes['route_list'] as $data) {
            if (!in_array($data['url'], array_column($themeRoutes['route_list'], 'url'))) {
                $new_current_theme_routes[] = $data;
            }
        }
        ?>
        @foreach ($new_current_theme_routes as $data)
            @if (in_array($data['url'], array_column($mergedArray, 'url')))
                <a class="card p-3 w-fit-content cursor-pointer text-dark text-nowrap d-flex flex-row gap-2 align-items-center"
                   href="javascript:">
                    {{ translate($data['name']) }} <span class="badge badge-danger rounded-circle">{{ 'x' }}</span>
                </a>
            @endif
        @endforeach

        @foreach ($themeRoutes['route_list'] as $data)
            @if (in_array($data['url'], array_column($mergedArray, 'url')))
                <a class="card p-3 w-fit-content cursor-pointer text-dark text-nowrap d-flex flex-row gap-2 align-items-center"
                   href="{{ $data['url'] }}" target="_blank">
                    {{ translate($data['name']) }} <span class="badge badge-success rounded-circle">{{ '+' }}</span>
                </a>
            @endif
        @endforeach
    </div>
    <p class="mb-5 px-5"><span class="text-danger">{{ translate('note').':' }} </span>
        {{ translate('please_do_not_forget_to_notify_your_vendors_about_these_changes').'.'.translate('so_that_they_can_also_update_their_store_banners_according_to_the_new_theme_ratio') }}
    </p>
    <div class="d-flex flex-column gap-2 justify-content-center align-items-center notify-all-the-sellers-area" >
        <button type="button" class="fs-16 btn btn--primary px-sm-5 w-fit-content text-capitalize notify-all-the-sellers" >
            {{ translate('notify_all_the_vendors') }}
        </button>
        <button type="button" class="fs-16 btn btn-secondary px-sm-5 w-fit-content reload-by-onclick" data-dismiss="modal">
            {{ translate('skip') }}
        </button>
    </div>
</div>

<script>
    notifyAllTheSellers();
    locationReload();
</script>
