<div class="modal fade" id="getInformationModal" data-backdrop="static" tabindex="-1" aria-labelledby="getInformationModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                        class="tio-clear"></i></button>
            </div>
            <div class="modal-body pt-0">
                <div class="swiper instruction-carousel pb-3">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide px-3">
                            <div class="d-flex flex-column align-items-center gap-2">
                                <img width="80" class="mb-3"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/firebase-console.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md mb-3 text-capitalize">{{translate('go_to_firebase')}}</h4>
                                <p class="text-center">{{translate('go_to_Firebase_and_create_a_project').', '.translate('_there_are_three_steps_to_create_a_project').'. '.translate('after_completing_the_project').','. translate('_you_can_proceed_further.').'.'}}</p>
                            </div>
                        </div>
                        <div class="swiper-slide px-3">
                            <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                <img width="80" class="mb-3"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/firebase-settings.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md mb-3 text-capitalize">{{translate('check_settings')}}</h4>
                                <p>{{translate('after_completing_the_project').', '.translate('_you’ll_see_the_project_settings').'. '. translate('in_the_project_settings').', '.translate('_please_ensure_that_cloud_messaging_is_enabled').' .'}}</p>
                            </div>
                        </div>
                        <div class="swiper-slide px-3">
                            <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                <img width="80" class="mb-3"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/json-file.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md mb-3 text-capitalize">{{translate('how_to_get_JSON_file')}}</h4>
                                <p class="text-center">{{translate('in_project_ settings').', '.translate('_click_on_"generate_new_private_key"').'. '.translate('then_you_will_get_a_JSON_file').', '.translate('copy_the_JSON_file_and_paste_it_into_the_Firebase_configuration_field').', '.translate('_then_submit.').'.'}}</p>
                                <button class="btn btn-primary w-100 max-w-250 mx-auto mt-3 text-capitalize"
                                        data-dismiss="modal">{{ translate('got_it') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                <div class="instruction-pagination-custom mb-2"></div>
                <div class="swiper-pagination instruction-pagination"></div>
            </div>
        </div>
    </div>
</div>
