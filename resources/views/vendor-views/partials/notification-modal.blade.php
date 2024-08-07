<div class="modal-header border-0 pb-0 d-flex justify-content-end">
    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
            class="tio-clear"></i></button>
</div>
<div class="modal-body px-4 px-sm-5 text-center">
    <div class="mb-3 text-center">
        <img width="75" src="{{dynamicAsset(path: 'public/assets/back-end/img/shift.png')}}" alt="">
    </div>

    <h3>{{ $companyName }} {{ translate('switched') }} {{ $data->title }}</h3>
    <p class="text-muted">{{ translate('at') }} {{ $data->created_at->diffforHumans() }}</p>
    <p class="p-3">
        {{ translate('hello') }} {{ translate('sir') }}/{{ translate('mam') }}
        {{ translate('we_have_updated_our_website_theme') }}! {{ translate('please_take_a_moment_to_review_the_changes_in_your_shop.') }}{{ translate('if_you_come_across_any_image_size_issues_kindly_resize_and_upload_them_to_ensure_a_refreshed_look_for_your_shop.') }}
    </p>

    <p class="mb-5 px-5"><span class="text-danger">{{ translate('note') }} :</span>
        {{ translate('your_attention_to_detail_and_effort_in_maintaining_the_visual_integrity_of_our_website_are_invaluable.') }}
    </p>

    <div class="d-flex flex-column gap-2 justify-content-center align-items-center" id="notify_all_the_sellers_area">
        <a class="fs-16 btn btn--primary px-sm-5 w-fit-content" target="_blank" href="{{ route('shopView',['id'=>$shop->id]) }}">{{ translate('visit_store') }}</a>
    </div>
</div>
