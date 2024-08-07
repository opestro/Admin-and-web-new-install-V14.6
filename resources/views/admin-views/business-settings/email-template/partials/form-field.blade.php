@php
    use App\Utils\Helpers;
@endphp
<form action="{{route('admin.business-settings.email-templates.update',[$template['template_name'],$template['user_type']])}}" method="POST" enctype="multipart/form-data">
@csrf
    <div class="d-flex justify-content-between gap-3 flex-wrap mb-5">
        <div class="table-responsive w-auto ovy-hidden max-width-700px">
            @php($language = $language->value ?? null)
            @php($defaultLang = 'en')
            @php($defaultLang = getDefaultLanguage())
            <ul class="nav nav-tabs w-fit-content flex-nowrap  border-0">
                @foreach (json_decode($language) as $lang)
                    <li class="nav-item text-capitalize">
                        <a class="nav-link form-system-language-tab  {{ $lang == $defaultLang ? 'active' : '' }}" href="javascript:" id="{{ $lang }}-link">
                            {{ Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder mb-2">
            {{translate('read_instructions')}}
            <div class="ripple-animation" data-toggle="modal"
                 data-target="#readInstructionModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     viewBox="0 0 18 18" fill="none" class="svg replaced-svg">
                    <path
                        d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z"
                        fill="currentColor"></path>
                </svg>
            </div>
        </div>
    </div>
    @if(!in_array('logo',$template['hide_field']))
        <div class="form-group">
            <label class="title-color">{{translate('logo')}}</label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input image-input" name="logo" id="mail-logo" data-image-id="view-mail-logo" accept="image/*">
                    <label class="custom-file-label" for="mail-logo">{{translate('choose_file')}}</label>
                </div>
            </div>
    </div>
    @endif
    @if(!in_array('icon',$template['hide_field']))
    <div class="form-group">
        <label class="title-color">{{translate('icon')}}</label>
        <div class="input-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input image-input" name="icon" id="mail-icon" data-image-id="view-mail-icon" accept="image/*">
                <label class="custom-file-label" for="mail-icon">{{translate('choose_file')}}</label>
            </div>
        </div>
    </div>
    @endif
    <div class="d-flex align-items-center gap-2 mb-3">
        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/header-content.png')}}" alt="">
        <h5 class="mb-0">{{translate('header_content')}}</h5>
    </div>
    <div class="bg-light p-3 rounded mb-3">
        @foreach (json_decode($language) as $lang)
                <?php
                $translate = [];
                if (count($template['translations'])) {
                    foreach ($template['translations'] as $translation) {
                        if ($translation->locale == $lang && $translation->key == 'title') {
                            $translate[$lang]['title'] = $translation->value;
                        }
                        if ($translation->locale == $lang && $translation->key == 'body') {
                            $translate[$lang]['body'] = $translation->value;
                        }

                    }
                }
                ?>

            <div class="{{ $lang != $defaultLang? 'd-none':''}} form-system-language-form" id="{{ $lang}}-form">
                <div class="form-group">
                    <label class="title-color text-capitalize" for="{{ $lang}}-main-title">{{ translate('title') }}
                        ({{strtoupper($lang) }})</label>
                    <input type="text" name="title[{{$lang}}]" data-id="mail-title"
                           id="{{ $lang}}-main-title"
                           value="{{ $translate[$lang]['title'] ??  ($lang == 'en' ? $template['title'] : '')}}"
                           class="form-control" placeholder="{{translate('ex').' : '.translate('title')}}">
                </div>
                <input type="hidden" name="lang[]" value="{{$lang}}">
                <div class="form-group">
                    <label class="title-color">{{ translate('mail_body') }} ({{strtoupper($lang) }})</label>
                    <textarea name="body[{{$lang}}]" data-id="mail-body" class="summernote">{!! $translate[$lang]['body'] ?? ($lang == 'en' ? $template['body'] : '') !!}</textarea>
                </div>
            </div>
        @endforeach
    </div>
    @if(!in_array('product_information',$template['hide_field']))
    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/header-content.png')}}"
                 alt="">
            <h5 class="mb-0 text-capitalize">{{translate('product_information')}}</h5>
        </div>

        <label class="switcher">
            <input type="checkbox" class="switcher_input change-status" value="1" name="product_information_status" data-id="product-information" {{$template['product_information_status'] ==1 ? 'checked' : '' }}>
            <span class="switcher_control"></span>
        </label>
    </div>
    <div class="bg-soft--primary p-3 rounded mb-3">
        <p>{{translate('product_information_will_be_automatically_bind_from_database').' '.translate('If_you_don’t_want_to_see_the_information_in_the_mail').' '.translate('just_turn_the_switch_button_off').'.'}}</p>
    </div>
    @endif
    @if(!in_array('banner_image',$template['hide_field']))
    <div class="form-group">
        <label class="title-color text-capitalize">{{translate('banner_image')}}</label>

        <div class="input-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="banner_image" id="inputGroupFile01" accept="image/*">
                <label class="custom-file-label" for="inputGroupFile01">{{translate('choose_file')}}</label>
            </div>
        </div>
    </div>
    @endif
    @if(!in_array('button_content',$template['hide_field']))
    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <img width="20"
                 src="{{dynamicAsset(path: 'public/assets/back-end/img/header-content.png')}}"
                 alt="">
            <h5 class="mb-0 text-capitalize">{{translate('button_content')}}</h5>
        </div>
        @if(!in_array('button_content_status',$template['hide_field']))
        <label class="switcher">
            <input type="checkbox" class="switcher_input change-status" value="1" data-id="button-content" name="button_content_status" {{$template['button_content_status'] ==1 ? 'checked' : '' }} >
            <span class="switcher_control"></span>
        </label>
        @endif
    </div>
    <div class="bg-light p-3 rounded mb-3">
        <div class="row g-2">
            <div class="col-lg-6">
                <div class="form-group">
                    @foreach (json_decode($language) as $lang)
                            <?php
                            if (count($template['translations'])) {
                                $translate = [];
                                foreach ($template['translations'] as $translation) {
                                    if ($translation->locale == $lang && $translation->key == 'button_name') {
                                        $translate[$lang]['button_name'] = $translation->value;
                                    }
                                }
                            }
                            ?>
                    <div class="{{ $lang != 'en'? 'd-none':''}} form-system-language-form {{ $lang}}-form">

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <label for="button_name" class="title-color mb-0 text-capitalize" for="{{ $lang}}-button-name">{{translate('button_name')}} ({{strtoupper($lang)}})</label>
                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('write_the_button_name_within_15_characters') }}">
                                <img width="16"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                     alt="">
                            </span>
                        </div>
                        <input type="text"  id="{{ $lang}}-button-name" name="button_name[{{ $lang}}]"  data-id="button-content"
                               value="{{ $translate[$lang]['button_name'] ?? ($lang == 'en' ? $template['button_name'] : '')}}"
                               placeholder="{{translate('ex').' : '.translate('submit')}}" class="form-control">
                    </div>
                    @endforeach

                </div>
            </div>
            @if(!in_array('button_url',$template['hide_field']))
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <label for="redirect_link" class="title-color mb-0 text-capitalize">{{translate('redirect_link')}}</label>
                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('link_to_your_preferred_destination_that_will_work_when_someone_clicks_on_the_Button_Name').'.'. translate('add_the_link_where_the_button_will_redirect_users').'.' }}">
                            <img width="16"
                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                 alt="">
                        </span>
                    </div>
                    <input type="text" id="redirect_link" name="button_url" data-id="button-link" value="{{$template['button_url']}}"
                           placeholder="{{translate('ex').' : '.'www.google.com'}}" class="form-control" >
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    @if(!in_array('order_information',$template['hide_field']))
    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <img width="20"
                 src="{{dynamicAsset(path: 'public/assets/back-end/img/header-content.png')}}"
                 alt="">
            <h5 class="mb-0">{{translate('order_information')}}</h5>
        </div>

        <label class="switcher">
            <input type="checkbox" class="switcher_input change-status" value="1" name="order_information_status" data-id="order-information" {{ $template['order_information_status'] ==1 ? 'checked' : '' }}>
            <span class="switcher_control"></span>
        </label>
    </div>
    <div class="bg-soft--primary p-3 rounded mb-3">
        <p class="mb-0">{{translate('order_Information_will_be_automatically_bind_from_database').'. '.translate('if_you don’t_want_to
            see_the_information_in_the_mail').'. '.translate('just_turn_the_switch_button_off').'.'}}</p>
    </div>
    @endif

    <div class="d-flex align-items-center gap-2 mb-3">
        <img width="20"
             src="{{dynamicAsset(path: 'public/assets/back-end/img/header-content.png')}}"
             alt="">
        <h5 class="mb-0 text-capitalize">{{translate('footer_content')}}</h5>
    </div>

    <div class="bg-light p-3 rounded mb-3">
        @foreach (json_decode($language) as $lang)
                <?php
                $translate = [];
                if (count($template['translations'])) {
                    foreach ($template['translations'] as $translation) {
                        if ($translation->locale == $lang && $translation->key == 'footer_text') {
                            $translate[$lang]['footer_text'] = $translation->value;
                        }
                    }
                }
                ?>
            <div class="{{ $lang != 'en'? 'd-none':''}} form-system-language-form {{ $lang}}-form">
                <div class="form-group">
                    <label class="title-color font-weight-bold text-capitalize" for="{{ $lang}}-footer-text">{{ translate('section_text') }}
                        ({{strtoupper($lang) }})</label>
                    <input type="text" name="footer_text[{{ $lang}}]" data-id="footer-text"
                           id="{{ $lang}}-footer-text"
                           value="{{ $translate[$lang]['footer_text'] ?? ($lang == 'en' ? $template['footer_text'] : '')}}"
                           class="form-control" placeholder="{{translate('ex').' : '.translate('please_contact_us_for_any_queries').','.translate('we’re_always_happy_to_help').'.'}}">
                </div>
            </div>
        @endforeach
        <div class="mb-5">
            <label class="title-color font-weight-bold">{{translate('page_links')}}</label>
            <div class="d-flex flex-wrap align-items-center gap-3 pages">
                <div class="d-flex gap-2 align-items-center">
                    <input type="checkbox" name="pages[privacy_policy]" data-from="pages" data-id="privacy-policy" id="privacy_policy" {{!empty($template['pages']) && in_array('privacy_policy',$template['pages'])? 'checked': (empty($template['pages']) ? 'checked' :'')}} >
                    <label class="mb-0 text-dark" for="privacy_policy">{{translate('privacy_policy')}}</label>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <input type="checkbox" name="pages[refund_policy]" data-from="pages" data-id="refund-policy" id="refund_policy" {{!empty($template['pages']) && in_array('refund_policy',$template['pages'])? 'checked': (empty($template['pages']) ? 'checked' :'')}}>
                    <label class="mb-0 text-dark" for="refund_policy">{{translate('refund_policy')}}</label>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <input type="checkbox" name="pages[cancellation_policy]" data-from="pages" data-id="cancellation-policy" id="cancellation_policy" {{!empty($template['pages']) && in_array('cancellation_policy',$template['pages']) ? 'checked': (empty($template['pages']) ? 'checked' :'')}}>
                    <label class="mb-0 text-dark" for="cancellation_policy">{{translate('cancellation_policy')}}</label>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <input type="checkbox" name="pages[contact_us]" data-from="pages" data-id="contact-us" id="contact_us" {{!empty($template['pages']) && in_array('contact_us',$template['pages'])? 'checked': (empty($template['pages']) ? 'checked' :'')}}>
                    <label class="mb-0 text-dark" for="contact_us">{{translate('contact_us')}}</label>
                </div>
            </div>
        </div>
        <div class="mb-5">
            <label class="title-color font-weight-bold">{{translate('social_media_links')}}</label>
            <div class="d-flex flex-wrap align-items-center gap-3">
                @foreach($socialMedia as $key=>$media)
                    <div class="d-flex gap-2 align-items-center">
                        <input type="checkbox" name="social_media[{{$media['name']}}]" data-from="social-media" data-id="{{$media['name']}}" id="{{$media['name']}}" {{!empty($template['social_media']) && in_array($media['name'],$template['social_media'])? 'checked': (empty($template['social_media']) ? 'checked' :'')}}>
                        <label class="mb-0 text-dark"
                               for="{{$media['name']}}">{{$media['name']}}</label>
                    </div>
                @endforeach
            </div>
        </div>
        @foreach (json_decode($language) as $lang)
                <?php
                if (count($template['translations'])) {
                    $translate = [];
                    foreach ($template['translations'] as $translation) {
                        if ($translation->locale == $lang && $translation->key == 'copyright_text') {
                            $translate[$lang]['copyright_text'] = $translation->value;
                        }
                    }
                }
                ?>
            <div class="{{ $lang != 'en'? 'd-none':''}} form-system-language-form {{ $lang}}-form">
                <div class="form-group">
                    <label class="title-color font-weight-bold text-capitalize" for="{{ $lang}}-copyright-text">{{ translate('copyright_content') }} ({{strtoupper($lang) }})</label>
                    <input type="text" name="copyright_text[{{ $lang}}]" data-id="copyright-text"
                           id="{{ $lang}}-copyright-text"
                           value="{{ $translate[$lang]['copyright_text'] ?? ($lang == 'en' ? $template['copyright_text'] : '')}}"
                           class="form-control" placeholder="{{translate('ex').' : '.translate('copyright').' @ '.translate('all_right_reserved')}}">
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-end gap-3">
        <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
        <button type="submit" class="btn btn--primary px-5">{{translate('save')}}</button>
    </div>
</form>
