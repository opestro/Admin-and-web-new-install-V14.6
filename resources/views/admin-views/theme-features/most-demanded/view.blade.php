@extends('layouts.back-end.app')

@section('title', translate('most_demanded'))

@section('content')
<div class="content container-fluid">

    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
            <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/most-demanded.png') }}" alt="">
            {{ translate('most_demanded') }}
        </h2>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.most-demanded.store') }}" method="post" class="text-start" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="name" class="title-color font-weight-medium">{{ translate('products') }}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="product_id">
                                        <option value="" disabled selected>
                                            {{ translate('select_Product') }}
                                        </option>
                                        @foreach ($products as $key => $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group md-2">
                                    <label for="name" class="title-color font-weight-medium">
                                        {{ translate('banner') }}
                                    </label>
                                    <span class="text-info ml-1">
                                        ( {{ translate('ratio') }} {{ translate('4') }}:{{ translate('1') }} )
                                    </span>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="banner-image"
                                               class="custom-file-input image-preview-before-upload" data-preview="#viewer"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label text-capitalize" for="banner-image">
                                            {{ translate('choose_File') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="text-center mt-lg-3">
                                        <img class="border radius-10 ratio-4:1 max-w-655px w-100" id="viewer"
                                            src="{{ dynamicAsset(path: 'public/assets/back-end/img/placeholder/placeholder-4-1.png') }}" alt="banner image"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" id="reset" class="btn btn-secondary px-4">{{ translate('reset') }}</button>
                            <button type="submit" class="btn btn--primary px-4">{{ translate('submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row ">
        <div class="col-md-12">
            <div class="card">
                <div class="px-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-6 mb-2 mb-md-0">
                            <h5 class="mb-0 text-capitalize d-flex gap-2">
                                {{ translate('most_demanded_table') }}
                                <span
                                    class="badge badge-soft-dark radius-50 fz-12">{{ $mostDemandedProducts->total() }}</span>
                            </h5>
                        </div>
                        <div class="col-md-8 col-lg-6">
                            <div
                                class="d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap gap-2">
                                <form action="{{route('admin.most-demanded.index') }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue"
                                               class="form-control" value="{{ request('searchValue') }}"
                                               placeholder="{{ translate('search_by_product_name') }}"
                                               aria-label="Search orders" >
                                        <button type="submit" class="btn btn--primary">
                                            {{ translate('search') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @if(count($mostDemandedProducts)>0)
                    <div class="table-responsive">
                        <table id="columnSearchDatatable"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th class="pl-xl-5">{{ translate('SL') }}</th>
                                <th>{{ translate('banner') }}</th>
                                <th>{{ translate('product') }}</th>
                                <th class="text-center">{{ translate('published') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($mostDemandedProducts as $key=>$mostDemanded)
                                <tr id="data-{{ $mostDemanded->id}}">
                                    <td class="pl-xl-5">{{ $mostDemandedProducts->firstItem()+ $key}}</td>
                                    <td>
                                        <img class="ratio-4:1" width="80" alt=""
                                             src="{{ getValidImage(path:'storage/app/public/most-demanded/'.$mostDemanded['banner'],type: 'backend-banner')}}">
                                    </td>
                                    <td>
                                        @if(isset($mostDemanded->product->name))
                                            {{ $mostDemanded->product->name }}
                                        @else
                                            {{ translate('no_product_found') }}
                                        @endif
                                    </td>
                                    <td class="d-flex justify-content-center">
                                            @if(isset($mostDemanded->product->status ) && $mostDemanded->product->status == 1)
                                            <form action="{{route('admin.most-demanded.status-update') }}" method="post" id="most-demanded{{ $mostDemanded['id']}}-form"
                                                  class="most-demanded-status-form">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $mostDemanded['id']}}">
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                           id="most-demanded{{ $mostDemanded['id'] }}" value="1" {{ $mostDemanded['status'] == 1 ? 'checked' : '' }}
                                                           data-modal-id="toggle-status-modal"
                                                           data-toggle-id="most-demanded{{ $mostDemanded['id'] }}"
                                                           data-on-image="most-demanded-on.png"
                                                           data-off-image="most-demanded-off.png"
                                                           data-on-title="{{ translate('Want_to_Turn_ON_Most_Demanded_Product_Status') }}"
                                                           data-off-title="{{ translate('Want_to_Turn_OFF_Most_Demanded_Product_Status') }}"
                                                           data-on-message="<p>{{ translate('if_enabled_this_most_demanded_product_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                           data-off-message="<p>{{ translate('if_disabled_this_most_demanded_product_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                            @else
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input status" name="status" id="{{ $mostDemanded->id}}" disabled>
                                                <span class="switcher_control opacity--40"></span>
                                            </label>
                                            @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-10 justify-content-center">
                                            <a class="btn btn-outline--primary btn-sm cursor-pointer edit"
                                            title="{{ translate('edit') }}"
                                            href="{{route('admin.most-demanded.edit',[$mostDemanded['id']]) }}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm cursor-pointer most-demanded-product-delete-button"
                                            title="{{ translate('delete') }}"
                                            id="{{ $mostDemanded['id'] }}">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        {{ $mostDemandedProducts->links() }}
                    </div>
                </div>
                @endif
                @if(count($mostDemandedProducts)==0)
                    @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
</div>

<span id="route-admin-most-demanded-delete" data-url="{{ route('admin.most-demanded.delete') }}"></span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/banner.js') }}"></script>
@endpush
