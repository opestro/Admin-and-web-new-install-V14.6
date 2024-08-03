@extends('layouts.back-end.app')

@section('title', translate('all_Pages_Banner '))

@section('content')

    <div class="content container-fluid">

        <div class="pb-2 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/business-setup.png') }}" alt="">
                {{ translate('All_Pages_Banner') }}
            </h2>
        </div>

        <div class="row pb-4 d--none text-start" id="main-banner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize">{{ translate('banner_form') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.all-pages-banner-store') }}" method="post" enctype="multipart/form-data"
                              class="banner_form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="id" name="id">
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="title-color text-capitalize">{{ translate('banner_type') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="type" required>

                                            @if (theme_root_path() == 'theme_fashion')
                                                <option value="banner_product_list_page">{{ translate('Product_List_Page') }}</option>
                                            @endif

                                            <option value="banner_privacy_policy">{{ translate('Privacy_Policy') }}</option>
                                            <option value="banner_refund_policy">{{ translate('Refund_Policy') }}</option>
                                            <option value="banner_return_policy">{{ translate('Return_Policy') }}</option>
                                            <option value="banner_about_us">{{ translate('About_us') }}</option>
                                            <option value="banner_faq_page">{{ translate('FAQ') }}</option>
                                            <option value="banner_terms_conditions">{{ translate('Terms_and_Conditions') }}</option>
                                            <option value="banner_cancellation_policy">{{ translate('Cancellation_Policy') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="title-color text-capitalize">
                                            {{ translate('Image') }}
                                        </label>
                                        <span class="text-info">( {{ translate('ratio') }} 6:1 )</span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="banner-image" data-preview="#banner-image-view"
                                                class="custom-file-input image-preview-before-upload"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label title-color" for="banner-image">
                                                {{ translate('choose') }} {{ translate('file') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex flex-column justify-content-end">
                                    <div>
                                        <div class="mb-30 mx-auto">
                                            <img class="ratio-6:1" id="banner-image-view"
                                                src="{{ dynamicAsset(path: 'public/assets/front-end/img/placeholder.png') }}"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                                    <button class="btn btn-secondary cancel px-4" type="reset">
                                        {{ translate('reset') }}
                                    </button>
                                    <button id="add" type="submit"
                                            class="btn btn--primary px-4">{{ translate('save') }}</button>
                                    <button id="update" class="btn btn--primary d--none text-white">
                                        {{ translate('update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="banner-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 col-lg-6 mb-2 mb-md-0">
                                <h5 class="mb-0 text-capitalize d-flex gap-2">
                                    {{ translate('banner_table') }}
                                    <span class="badge badge-soft-dark radius-50 fz-12">
                                        {{ $pageBanners->total() }}
                                    </span>
                                </h5>
                            </div>
                            <div class="col-md-8 col-lg-6">
                                <div class="d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap gap-2">

                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-custom">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="searchValue"
                                                   class="form-control"
                                                   placeholder="{{ translate('Search_by_Banner_Type') }}"
                                                   aria-label="Search orders" value="{{ request('searchValue') }}">
                                            <button type="submit" class="btn btn--primary">
                                                {{ translate('Search') }}
                                            </button>
                                        </div>
                                    </form>

                                    <div id="banner-btn">
                                        <button id="main-banner-add" class="btn btn--primary text-nowrap">
                                            <i class="tio-add"></i>
                                            {{ translate('add_banner') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="columnSearchDatatable"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th class="pl-xl-5">{{ translate('SL') }}</th>
                                <th>{{ translate('image') }}</th>
                                <th>{{ translate('banner_type') }}</th>
                                <th>{{ translate('published') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            @foreach($pageBanners as $key=>$banner)
                                <tbody>
                                <tr id="data-{{ $banner->id}}">
                                    <td class="pl-xl-5">{{ $pageBanners->firstItem()+$key}}</td>
                                    <td>
                                        <img class="ratio-4:1" width="80" alt=""
                                             src="{{ getValidImage(path:'storage/app/public/banner/'.json_decode($banner['value'])->image,type: 'backend-banner')}}">
                                    </td>
                                    <td>{{ translate(ucwords(str_replace('_',' ',$banner->type))) }}</td>
                                    <td>
                                        <form action="{{route('admin.business-settings.all-pages-banner-status') }}" method="post" id="banner-status{{ $banner['id']}}-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $banner['id']}}">
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                       id="banner-status{{ $banner['id'] }}" value="1" {{ json_decode($banner['value'])->status == 1 ? 'checked' : '' }}
                                                       data-modal-id="toggle-status-modal"
                                                       data-toggle-id="banner-status{{ $banner['id'] }}"
                                                       data-on-image="banner-status-on.png"
                                                       data-off-image="banner-status-off.png"
                                                       data-on-title="{{ translate('Want_to_Turn_ON').' '.translate(str_replace('_',' ',$banner->banner_type)).' '.translate('status') }}"
                                                       data-off-title="{{ translate('Want_to_Turn_OFF').' '.translate(str_replace('_',' ',$banner->banner_type)).' '.translate('status') }}"
                                                       data-on-message="<p>{{ translate('if_enabled_this_banner_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                       data-off-message="<p>{{ translate('if_disabled_this_banner_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-10 justify-content-center">
                                            <a class="btn btn-outline--primary btn-sm cursor-pointer edit"
                                               title="{{ translate('Edit') }}"
                                               href="{{ route('admin.business-settings.all-pages-banner-edit', [$banner['id']]) }}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm cursor-pointer banner-delete-button"
                                               title="{{ translate('Delete') }}"
                                               id="{{ $banner['id']}}">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {{ $pageBanners->links() }}
                        </div>
                    </div>

                    @if(count($pageBanners)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>

    <span id="route-admin-banner-store" data-url="{{ route('admin.business-settings.all-pages-banner-store') }}"></span>
    <span id="route-admin-banner-delete" data-url="{{ route('admin.business-settings.all-pages-banner-delete') }}"></span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/banner.js') }}"></script>
@endpush
