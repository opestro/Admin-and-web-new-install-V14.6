@extends('layouts.back-end.app')

@section('title', translate('SEO_Settings'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/seo-settings.svg') }}" alt="">
                {{ translate('SEO_Settings') }}
            </h2>
        </div>
        @include('admin-views.business-settings.seo-settings._inline-menu')
        <div class="card shadow-none">
            <div class="card-header flex-wrap">
                <div class="">
                    <h4 class="title m-0">{{ translate('Robots.txt_Editor') }}</h4>
                    <p class="m-0">{{translate('control_search_engine_crawlers_access_to_specific_pages_on_a_website').'.'}}</p>
                </div>

                @if(file_exists(base_path('robots.txt')))
                    <a class="btn btn-outline--primary" href="{{ url('/robots.txt') }}" target="_blank">
                        <span class="font-weight-semibold text-dark txt">{{translate('view_URL')}}</span>
                        <img src="{{dynamicAsset('public/assets/back-end/img/arrow-right.png')}}" alt="">
                    </a>
                @else
                    <button class="btn btn-outline--primary disabled" data-toggle="tooltip" title="{{ translate('Empty_File') }}">
                        <span class="font-weight-semibold text-dark txt">{{translate('view_URL')}}</span>
                        <img src="{{dynamicAsset('public/assets/back-end/img/arrow-right.png')}}" alt="">
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-20px">
                    <div class="d-flex align-items-center gap-2 fs-12 p-3 rounded badge--info">
                        <img src="{{dynamicAsset('public/assets/back-end/img/idea.png')}}" alt="">
                        @if(env('APP_MODE') == 'demo')
                            <div class="w-0 flex-grow">
                                {{ translate('the_robots.txt_editor_lets_you_tell_search_engines_which_parts_of_your_website_they_should_or_should_not_crawl.') }} {{ translate('please_note') }}:{{ translate('this_feature_is_disabled_for_demo.') }}
                            </div>
                        @else
                            <div class="w-0 flex-grow">
                                {{ translate('the_robots.txt_editor_lets_you_tell_search_engines_which_parts_of_your_website_they_should_or_should_not_crawl.') }} {{ translate('please_note') }}:{{ translate('the_system_will_automatically_generate_a_robot.txt_for_your_site.') }} {{ translate('you_do_not_have_to_create_it_manually.') }} {{ translate('but_you_can_edit_or_modify_this_robots.txt.') }}
                            </div>
                        @endif
                    </div>
                </div>

                <form action="{{ route('admin.seo-settings.robot-txt') }}" method="post">
                    @csrf
                    <div class="mb-20px">
                        <textarea class="form-control" name="robot_text" rows="5">{{$content}}</textarea>
                    </div>
                    <div class="btn--container">
                        <button type="reset" class="btn btn-secondary">{{ translate('reset') }}</button>
                        <button type="{{ env('APP_MODE') == 'demo' ? 'button' : 'submit' }}" class="btn btn--primary {{env('APP_MODE')!='demo'? '' : 'call-demo'}}">
                            {{ translate('submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@push('script')

@endpush
