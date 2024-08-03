@php
    $companyName = getWebConfig(name: 'company_name');
    $companyLogo = getWebConfig(name: 'company_web_logo');
    $title = $template['title'] ?? null;
    $body = $template['body'] ?? null;
    $copyrightText = $template['copyright_text'] ?? null;
    $footerText = $template['footer_text'] ?? null;
    $buttonName = $template['button_name'] ?? null;
    foreach ($template?->translationCurrentLanguage ?? [] as $translate) {
       $title = $translate->key == 'title' ? $translate->value : $title;
       $body = $translate->key == 'body' ? $translate->value : $body;
       $copyrightText = $translate->key == 'copyright_text' ? $translate->value : $copyrightText;
       $footerText = $translate->key == 'footer_text' ? $translate->value : $footerText;
       $buttonName = $translate->key == 'button_name' ? $translate->value : $buttonName;
    }
@endphp
@extends('layouts.back-end.app')

@section('title', translate('email_templates'))

@push('css_or_js')
    <link rel="stylesheet"
          href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.css')}}"/>
    <link href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/summernote/summernote.min.css') }}" rel="stylesheet">

@endpush

@section('content')
    <div class="content container-fluid">
        @include('admin-views.business-settings.email-template.partials.page-title')
        @include('admin-views.business-settings.email-template.partials.'.$template['user_type'].'-mail-inline-menu')
        <div class="">
            @include('admin-views.business-settings.email-template.partials.update-status')
            <div class="card">
                <div class="card-body">
                    <div class="row gy-4 gx-xl-4">
                        <div class="col-lg-6 col-xl-5">
                            <h5 class="mb-3">{{translate('template_UI')}}</h5>
                            <div class="card">
                                @include('admin-views.business-settings.email-template.'.$template['user_type'].'-mail-template'.'.'.$template['template_design_name'])
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-7">
                            @include('admin-views.business-settings.email-template.partials.form-field')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin-views.business-settings.email-template.partials.instructions')
@endsection

@push('script_2')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/summernote/summernote.min.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/email-template.js') }}"></script>
@endpush
