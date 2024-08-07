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

        <div class="card mb-3 shadow-none">
            <div class="card-header flex-wrap gap-2 py-5">
                <div class="text-center w-100">
                    <h4 class="title mb-2">
                        {{ $defaultPageData ? translate('update_Default_Meta') : translate('Set_Default_Meta') }}
                    </h4>
                    <p class="mb-4">{{ translate("if_you_do_not_have_any_meta_content_in_any_page_it_will_automatically_use_as_meta_content_from_this_section.")}}</p>
                    <a class="btn {{ $defaultPageData ? 'edit-content-btn' : 'add-content-btn' }}"
                    href="{{ route('admin.seo-settings.robots-meta-content.page-content-view', ['page_name' => 'default']) }}">
                        <i class="tio-add"></i>
                        <span class="txt">
                            {{ $defaultPageData ? translate("edit_Content") : translate("Add_Content") }}
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow-none">
            <div class="card-header flex-wrap gap-2">
                <div class="">
                    <h4 class="title m-0">{{ translate('default_Pages_Robots_Meta_Content_Settings') }}</h4>
                    <p class="m-0">
                        {{ translate("optimize_your_Websites_performance_indexing_status_and_search_visibility.") }}
                    </p>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline--primary" data-toggle="modal" data-target="#page-add-modal" type="button">
                        <img src="{{ dynamicAsset('public/assets/back-end/img/add-btn.png')}}" alt="">
                        <span class="txt">{{ translate('Add_Page') }}</span>
                        <span data-toggle="tooltip" title="{{ translate('fetch_static_page_to_edit_the_meta_content') }}">
                            <img src="{{ dynamicAsset('public/assets/back-end/img/query.png')}}" alt="">
                        </span>
                    </button>
                </div>
            </div>
            <div class="card-body px-0">
                @if(count($pageListData) > 0)
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ translate('SL') }}</th>
                            <th>{{ translate('Pages') }}</th>
                            <th>{{ translate('URL') }}</th>
                            <th class="text-center">{{ translate('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pageListData as $key => $pageListSingle)
                            <tr>
                                <td>
                                    {{ $pageListData->firstItem() + $key }}
                                </td>
                                <td>
                                    <span class="font-weight-semibold text-title">
                                        {{ translate($pageListSingle['page_title']) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ $pageListSingle['page_url'] ? $pageListSingle['page_url'] : 'javascript:' }}" class="text-primary text-underline" target="_blank">
                                        {{ $pageListSingle['page_url'] }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
                                        <a href="{{ route('admin.seo-settings.robots-meta-content.page-content-view', ['page_name' => $pageListSingle['page_name']]) }}"
                                           class="btn btn-sm {{ $pageListSingle['meta_title'] ? 'btn-outline--primary' : 'btn-outline--success' }}">
                                            <i class="tio-add"></i>
                                            <span>
                                            {{ $pageListSingle['meta_title'] ? translate('edit_Content') : translate('add_Content') }}
                                        </span>
                                        </a>
                                        <a class="btn btn-outline-danger btn-sm {{env('APP_MODE')!='demo'? '' : 'call-demo' }}"
                                           href="{{env('APP_MODE')!='demo' ?route('admin.seo-settings.robots-meta-content.delete-page', ['id' => $pageListSingle['id']]) : 'javascript:' }}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="page-area mt-4">
                        <div class="d-flex align-items-center justify-content-end">
                            <div>
                                {{ $pageListData->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    @include('layouts.back-end._empty-state', ['text'=>'no_data_found'], ['image'=>'default'])
                @endif
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="page-add-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title w-100 text-center">{{translate('Add_Page')}}</h2>
                        <div type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C6.47 2 2 6.47 2 12C2 17.53 6.47 22 12 22C17.53 22 22 17.53 22 12C22 6.47 17.53 2 12 2ZM16.3 16.3C16.2075 16.3927 16.0976 16.4662 15.9766 16.5164C15.8557 16.5666 15.726 16.5924 15.595 16.5924C15.464 16.5924 15.3343 16.5666 15.2134 16.5164C15.0924 16.4662 14.9825 16.3927 14.89 16.3L12 13.41L9.11 16.3C8.92302 16.487 8.66943 16.592 8.405 16.592C8.14057 16.592 7.88698 16.487 7.7 16.3C7.51302 16.113 7.40798 15.8594 7.40798 15.595C7.40798 15.4641 7.43377 15.3344 7.48387 15.2135C7.53398 15.0925 7.60742 14.9826 7.7 14.89L10.59 12L7.7 9.11C7.51302 8.92302 7.40798 8.66943 7.40798 8.405C7.40798 8.14057 7.51302 7.88698 7.7 7.7C7.88698 7.51302 8.14057 7.40798 8.405 7.40798C8.66943 7.40798 8.92302 7.51302 9.11 7.7L12 10.59L14.89 7.7C14.9826 7.60742 15.0925 7.53398 15.2135 7.48387C15.3344 7.43377 15.4641 7.40798 15.595 7.40798C15.7259 7.40798 15.8556 7.43377 15.9765 7.48387C16.0975 7.53398 16.2074 7.60742 16.3 7.7C16.3926 7.79258 16.466 7.90249 16.5161 8.02346C16.5662 8.14442 16.592 8.27407 16.592 8.405C16.592 8.53593 16.5662 8.66558 16.5161 8.78654C16.466 8.90751 16.3926 9.01742 16.3 9.11L13.41 12L16.3 14.89C16.68 15.27 16.68 15.91 16.3 16.3Z" fill="#BFBFBF"/>
                            </svg>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.seo-settings.robots-meta-content.add-page') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">{{translate('Page Name')}}</label>
                                <select class="form-control" name="page_name" required id="robotsMetaContentPageSelect">
                                    @foreach($pageList as $pageRoute => $pageInfo)
                                        <option value="{{ $pageRoute }}">{{ translate($pageInfo['title']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{translate('Page_URL')}}</label>
                                <input type="url" class="form-control" name="page_url"
                                       id="robotsMetaContentPageUrl" placeholder="{{ translate('Enter_URL') }}"
                                       value="{{ $pageList[array_key_first($pageList)]['route'] }}"
                                       readonly>
                            </div>
                            <div class="mb-3 btn--container justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'? 'submit' : 'button' }}" class="btn btn--primary {{env('APP_MODE')!='demo'? '' : 'call-demo' }}">
                                    {{ translate('save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="btn--container"></div>
                </div>
            </div>
        </div>
    </div>

    <span id="robotsMetaContentPageURoutes"
    @foreach($pageList as $pageRoute => $pageInfo)
        data-{{ strtolower($pageRoute) }}="{{ $pageInfo['route'] ?? '' }}"
    @endforeach
    ></span>

@endsection

@push('script')

@endpush
