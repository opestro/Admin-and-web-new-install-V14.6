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
            <div class="card-header">
                <div class="w-100">
                    <h4 class="title m-0">{{translate('Site_Map')}}</h4>
                    <p class="m-0">{{ translate("Organized_for_navigation_and_search_engine_optimization.") }}</p>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-20px">
                    <div class="d-flex align-items-center gap-2 fs-12 p-3 rounded badge--info">
                        <img src="{{ dynamicAsset('public/assets/back-end/img/idea.png')}}" alt="">
                        <div class="w-0 flex-grow">
                            {{ translate('a_sitemap_is_an_xml_file_that_contains_all_the_web_pages_of_a_website.') }}
                            {{ translate('here_we_list_and_organize_all_the_default_pages_in_a_hierarchical_structure_of_your_website_through_xml_sitemap.') }}
                            {{ translate('it_allows_search_engines_to_find_and_display_your_products_and_services_in_search_results.')}}
                        </div>
                    </div>
                </div>
                <div class="text-center py-3">
                    <h4 class="fs-16 mb-3">
                        {{ translate('Download_Generate_Sitemap') }}
                    </h4>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <button id="{{env('APP_MODE')!='demo'? 'generateAndDownloadSitemap' : '' }}" data-route="{{ route('admin.seo-settings.sitemap-generate-download') }}"
                           class="btn btn--primary px-5 d-flex gap-2 align-items-center {{env('APP_MODE')!='demo'? '' : 'call-demo' }}">
                            <span class="spinner-border extra-small-spinner-border d--none" role="status" id="{{env('APP_MODE')!='demo'? 'generateAndDownloadSitemapSpinner' : '' }}">
                                <span class="sr-only">{{translate('loading').'...'}}</span>
                            </span>
                            <span>{{ translate('Generate_&_Download') }}</span>
                        </button>
                        <a href="{{ env('APP_MODE')!='demo'? route('admin.seo-settings.sitemap-generate-upload') : 'javascript:' }}"
                           class="btn btn-outline--primary px-5 {{env('APP_MODE')!='demo'? '' : 'call-demo' }}">
                            {{ translate('Generate_&_Upload_to_Server') }}
                        </a>
                        <button class="btn btn-outline--primary px-5" data-toggle="modal" data-target="#sitemap-upload-modal" type="button">
                            {{ translate('Upload_Sitemap') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-none mt-3">
            <div class="card-body p-0">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="w-95px border-0">
                                {{ translate('SL') }}
                            </th>
                            <th class="w-45px border-0">{{ translate('name') }}</th>
                            <th class="w-200px text-center border-0">{{ translate('file_Size') }}</th>
                            <th class="w-200px text-center border-0">{{ translate('date') }}</th>
                            <th class="text-center w-60px border-0">{{ translate('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siteMapList as $siteMapIndex => $siteMap)
                            <tr>
                                <td>
                                    <span class="font-weight-semibold text-title">
                                        {{ $siteMapList->firstItem() + $siteMapIndex }}
                                    </span>
                                </td>
                                <td>
                                    <span>{{ $siteMap['name'] }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center">
                                        {{ $siteMap['size'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <span>{{ $siteMap['created_at']->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="{{env('APP_MODE')!='demo'? '' : 'call-demo' }}" href="{{env('APP_MODE')!='demo'? route('admin.seo-settings.sitemap-download', ['path' => base64_encode($siteMap['name'])]) : 'javascript:'}}">
                                            <img src="{{ dynamicAsset('public/assets/back-end/img/download.png')}}" alt="" width="30">
                                        </a>

                                        <a class="{{env('APP_MODE')!='demo'? '' : 'call-demo' }}" href="{{ env('APP_MODE')!='demo'? route('admin.seo-settings.sitemap-delete', ['path' => base64_encode($siteMap['name'])]) : 'javascript:'}}">
                                            <img src="{{ dynamicAsset('public/assets/back-end/img/delete-outlined.png')}}" alt="" width="30">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="page-area px-4 pt-4">
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                            {{ $siteMapList->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("admin-views.business-settings.seo-settings._sitemap-upload-modal")
@endsection

@push('script')

@endpush
