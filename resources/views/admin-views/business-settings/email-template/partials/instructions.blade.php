<div class="modal fade" id="readInstructionModal" data-backdrop="static" tabindex="-1" aria-labelledby="readInstructionModal"
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
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/2.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md mb-3 text-capitalize">{{translate('input_a_title')}}</h4>
                                <p class="text-center">{{translate('give_email_template_a_descriptive_title_that_will_help_users_identify_what_it_for').'.'}}</p>
                            </div>
                        </div>
                        <div class="swiper-slide px-3">
                            <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                <img width="80" class="mb-3"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/3.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md mb-3">{{translate('write_a_message_in_the_email_body')}}</h4>

                                <p>{{translate('you_can_add_your_message_using_placeholders_to_include_dynamic_content').'. '. translate('here_are_some_examples_of_placeholders_you_can_use').' :'}}</p>

                                <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                    <li>{{'{userName}'.' : '.translate('the_name_of_the_user').'.'}}</li>
                                    <li>{{'{adminName}'.' : '.translate('the_name_of_the_admin').'.'}}</li>
                                    <li>{{'{deliveryManName}'.' : '.translate('the_name_of_the_delivery_person').'.'}}</li>
                                    <li>{{'{shopName}'.' : '.translate('the_name_of_the_store').'.'}}</li>
                                    <li>{{'{vendorName}'.' : '.translate('the_name_of_the_vendor').'.'}}</li>
                                    <li>{{'{orderId}'.' : '.translate('the_order_id').'.'}}</li>
                                    <li>{{'{transactionId}'.' : '.translate('the_transaction_id').'.'}}</li>
                                    <li>{{'{emailId}'.' : '.translate('the_email_id').'.'}}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="swiper-slide px-3">
                            <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                <img width="80" class="mb-3"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/4.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md mb-3 text-capitalize">{{translate('add_button_&_link')}}</h4>
                                <p class="text-center">{{translate('specify_the_text_and_URL_for_the_button_that_you_want_to_include_in_your_email').'.'}}</p>
                            </div>
                        </div>
                        <div class="swiper-slide px-3">
                            <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                <img width="80" class="mb-3"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/5.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md mb-3 text-capitalize">{{translate('choose_a_icon_/_banner_image')}}</h4>
                                <p class="text-center">{{translate('select_a_background_image_to_display_behind_your_email_content').'.'}}</p>
                            </div>
                        </div>
                        <div class="swiper-slide px-3">
                            <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                <img width="80" class="mb-3"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/6.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md mb-3 text-capitalize">{{translate('select_footer_content')}}</h4>
                                <p class="text-center">{{translate('add_footer_content').','.translate('_such_as_your_company_address_and_contact_information').'.'}}</p>
                            </div>
                        </div>
                        <div class="swiper-slide px-3">
                            <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                <img width="80" class="mb-3"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/7.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md mb-3 text-capitalize">{{translate('create_a_copyright_notice')}}</h4>
                                <p class="text-center">
                                    {{translate('include_a_copyright_notice_at_the_bottom_of_your_email_to_protect_your_content').'.'}}</p>
                            </div>
                        </div>
                        <div class="swiper-slide px-3">
                            <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                <img width="80" class="mb-3"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/8.png')}}"
                                     loading="lazy" alt="">
                                <h4 class="lh-md mb-3 text-capitalize">{{translate('save_and_publish')}}</h4>
                                <p class="text-center">{{translate('once_you’ve_set_up_all_the_elements_of_your_email_template_save_and_publish_it_for_use').'.'}}</p>
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
