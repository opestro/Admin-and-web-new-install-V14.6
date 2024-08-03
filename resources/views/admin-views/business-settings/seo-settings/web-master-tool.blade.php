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
        <div class="card">
            <div class="card-header">
                <div class="w-100">
                    <h4 class="title m-0 text-capitalize">{{translate('Webmaster_Tools')}}</h4>
                    <p class="m-0">
                        {{ translate('optimize_websites_performance,_indexing_status,_and_search_visibility.') }}
                        <a href="{{ 'https://6amtech.com/blog/webmaster-tools-verification/' }}"
                           target="_blank"
                           class="text-underline text--primary font-weight-semibold">
                            {{ translate('Learn_more.') }}
                        </a>
                    </p>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('admin.seo-settings.web-master-tool')}}" method="post">
                    @csrf
                    <div class="p-4 border rounded mb-3">
                        <div class="row g-3">
                            <div class="col-md-4 col-xl-3">
                                <img src="{{dynamicAsset('public/assets/back-end/img/google-1.png')}}" alt="" width="30">
                                <h4 class="m-0 mt-3">{{translate('google_search_console')}}</h4>
                            </div>
                            <div class="col-md-8 col-xl-9">
                                <input type="text" name="google_search_console_code"  value="{{$webMasterToolData['google_search_console_code']}}" placeholder="{{translate('enter_your_HTML_code_or_ID')}}" class="form-control">
                                <div class="mt-10px fs-12">
                                    <div>{{translate('Google_Console_verification_HTML_code_or_ID').'.'.translate('learn_how_to_get')}}
                                        <a href="{{ 'https://6amtech.com/blog/webmaster-tools-verification/' }}"
                                           target="_blank"
                                           class="text--primary text-underline font-weight-semibold text-capitalize">
                                            {{translate('search_console_verification_page')}}
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span class="badge badge-soft-danger p-2">
                                        &lt;meta name= “google-site-verification” content=”your-id” /&gt;
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border rounded mb-20px">
                        <div class="row g-3">
                            <div class="col-md-4 col-xl-3">
                                <img src="{{dynamicAsset('public/assets/back-end/img/bing-1.png')}}" alt="" width="30">
                                <h4 class="m-0 mt-3 text-capitalize">{{translate('bing_webmaster_tools')}}</h4>
                            </div>
                            <div class="col-md-8 col-xl-9">
                                <input type="text" name="bing_webmaster_code" value="{{$webMasterToolData['bing_webmaster_code']}}" placeholder="{{translate('enter_your_HTML_code_or_ID')}}" class="form-control">
                                <div class="mt-10px fs-12">
                                    <div>
                                        {{translate('Bing_Webmaster_Tools_verification_HTML_code_or_ID').'.'.translate('learn_how_to_get')}}
                                        <a href="{{ 'https://6amtech.com/blog/webmaster-tools-verification/' }}"
                                           target="_blank"
                                           class="text--primary text-underline font-weight-semibold">
                                            {{ translate('search_console_verification_page') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span class="badge badge-soft-danger p-2">
                                        &lt;meta name= “msvalidate.01” content=”your-id” /&gt;
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border rounded mb-3">
                        <div class="row g-3">
                            <div class="col-md-4 col-xl-3">
                                <img src="{{dynamicAsset('public/assets/back-end/img/baidu-1.png')}}" alt="" width="30">
                                <h4 class="m-0 mt-3 text-capitalize">{{translate('baidu_webmaster_tool')}}</h4>
                            </div>
                            <div class="col-md-8 col-xl-9">
                                <input type="text"  name="baidu_webmaster_code" value="{{$webMasterToolData['baidu_webmaster_code']}}" placeholder="{{translate('enter_your_HTML_code_or_ID')}}" class="form-control">
                                <div class="mt-10px fs-12">
                                    <div>
                                        {{translate('Baidu_Webmaster_Tools_verification_HTML_code_or_ID').'.'.translate('learn_how_to_get')}}
                                        <a href="{{ 'https://6amtech.com/blog/webmaster-tools-verification/' }}"
                                           target="_blank"
                                           class="text--primary text-underline font-weight-semibold">
                                            {{translate('search_console_verification_page')}}
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span class="badge badge-soft-danger p-2">
                                        &lt;meta name= “baidu-site-verification” content=”your-id” /&gt;
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border rounded mb-20px">
                        <div class="row g-3">
                            <div class="col-md-4 col-xl-3">
                                <img src="{{ dynamicAsset('public/assets/back-end/img/yandex-1.png') }}" alt="" width="30">
                                <h4 class="m-0 mt-3 text-capitalize">{{translate('yandex_webmaster_tool')}}</h4>
                            </div>
                            <div class="col-md-8 col-xl-9">
                                <input type="text"  name="yandex_webmaster_code" value="{{$webMasterToolData['yandex_webmaster_code']}}"  placeholder="{{translate('enter_your_HTML_code_or_ID')}}" class="form-control">
                                <div class="mt-10px fs-12">
                                    <div>
                                        {{translate('Yandex_Webmaster_Tools_verification_HTML_code_or_ID').'.'.translate('learn_how_to_get_it')}}
                                        <a href="{{ 'https://6amtech.com/blog/webmaster-tools-verification/' }}"
                                           target="_blank"
                                           class="text--primary text-underline font-weight-semibold">
                                            {{ translate('search_console_verification_page') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span class="badge badge-soft-danger p-2">
                                        &lt;meta name= “yandex-verification” content=”your-id” /&gt;
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container">
                        <button type="reset" class="btn btn-secondary">{{translate('Reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary {{env('APP_MODE')!='demo'? '' : 'call-demo'}}">{{translate('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')

@endpush
