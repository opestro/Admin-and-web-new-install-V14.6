@extends('layouts.back-end.app')

@section('title', translate('sub_Category'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/brand-setup.png') }}" alt="">
                {{ translate('sub_Category_Setup') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-start">
                        <form action="{{ route('admin.sub-category.store') }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($languages as $lang)
                                    <li class="nav-item">
                                        <span
                                            class="nav-link form-system-language-tab cursor-pointer {{ $lang == $defaultLanguage? 'active':''}}"
                                            id="{{ $lang}}-link">
                                            {{ucfirst(getLanguageName($lang)).'('.strtoupper($lang).')'}}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="{{ theme_root_path() == 'theme_aster'?'col-lg-6':'col-lg-12' }}">

                                    <div class="{{ theme_root_path() == 'theme_aster'?'w-100':'row' }}">
                                        <div class="{{ theme_root_path() == 'theme_aster'?'w-100':'col-md-6 col-lg-4' }}">
                                            @foreach($languages as $lang)
                                                <div
                                                    class="form-group {{ $lang != $defaultLanguage ? 'd-none':''}} form-system-language-form"
                                                    id="{{ $lang}}-form">
                                                    <label class="title-color" for="exampleFormControlInput1">
                                                        {{ translate('sub_category_name') }}
                                                        <span class="text-danger">*</span>
                                                        ({{strtoupper($lang) }})
                                                    </label>
                                                    <input type="text" name="name[]" class="form-control"
                                                           placeholder="{{ translate('new_Sub_Category') }}" {{ $lang == $defaultLanguage? 'required':''}}>
                                                </div>
                                                <input type="hidden" name="lang[]" value="{{ $lang}}">
                                            @endforeach
                                            <input name="position" value="1" class="d-none">
                                        </div>
                                        <div class="form-group {{ theme_root_path() == 'theme_aster'?'w-100':'col-md-6 col-lg-4' }}">
                                            <label class="title-color"
                                                   for="exampleFormControlSelect1">{{ translate('main_Category') }}
                                                <span class="text-danger">*</span></label>
                                            <select id="exampleFormControlSelect1" name="parent_id"
                                                    class="form-control" required>
                                                <option value="" selected disabled>
                                                    {{ translate('select_main_category') }}
                                                </option>
                                                @foreach($parentCategories as $category)
                                                    <option value="{{ $category['id']}}">
                                                        {{ $category['defaultname']}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group {{ theme_root_path() == 'theme_aster'?'w-100':'col-md-6 col-lg-4' }}">
                                            <label class="title-color" for="priority">{{ translate('priority') }}
                                                <span>
                                                <i class="tio-info-outined"  data-toggle="tooltip" data-placement="top"
                                                   title="{{ translate('the_lowest_number_will_get_the_highest_priority') }}"></i>
                                            </span>
                                            </label>
                                            <select class="form-control" name="priority" id="" required>
                                                <option disabled selected>{{ translate('set_Priority') }}</option>
                                                @for ($i = 0; $i <= 10; $i++)
                                                    <option value="{{ $i}}">{{ $i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    @if (theme_root_path() == 'theme_aster')
                                        <div class="from_part_2">
                                            <label class="title-color">{{ translate('sub_category_Logo') }}</label>
                                            <span class="text-info">
                                                {{ THEME_RATIO[theme_root_path()]['Category Image'] }}
                                            </span>
                                            <div class="custom-file text-left">
                                                <input type="file" name="image" id="category-image"
                                                       class="custom-file-input image-preview-before-upload"
                                                       data-preview="#viewer"
                                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label" for="category-image">
                                                    {{ translate('choose_File') }}
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if (theme_root_path() == 'theme_aster')
                                    <div class="col-lg-6 mt-4 mt-lg-0 from_part_2">
                                        <div class="form-group">
                                            <div class="mx-auto text-center">
                                                <img class="upload-img-view" id="viewer"
                                                     src="{{ dynamicAsset(path: 'public/assets/back-end/img/900x400/img1.jpg') }}"
                                                     alt="">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <button type="reset" class="btn btn-secondary">{{ translate('reset') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20" id="cate-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center">
                            <div class="">
                                <h5 class="text-capitalize d-flex gap-1">
                                    {{ translate('sub_category_list') }}
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{ $categories->total() }}</span>
                                </h5>
                            </div>
                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="" type="search" name="searchValue" class="form-control"
                                               placeholder="{{ translate('search_by_sub_category_name') }}"
                                               value="{{ request('searchValue') }}">
                                        <button type="submit" class="btn btn--primary">{{ translate('search') }}</button>
                                    </div>
                                </form>
                                <div>
                                    <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                            data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{translate('export')}}
                                        <i class="tio-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route('admin.sub-category.export',['searchValue'=>request('searchValue')]) }}">
                                                <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}"
                                                     alt="">
                                                {{translate('excel')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('ID') }}</th>
                                @if (theme_root_path() == 'theme_aster')
                                    <th class="text-center">{{ translate('sub_category_Image') }}</th>
                                @endif
                                <th>{{ translate('sub_category_name') }}</th>
                                <th>{{ translate('category_name') }}</th>
                                <th class="text-center">{{ translate('priority') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $key=>$category)
                                <tr>
                                    <td>{{ $category['id']}}</td>
                                    @if (theme_root_path() == 'theme_aster')
                                        <td class="text-center">
                                            <img class="rounded" width="64" alt=""
                                                 src="{{ getValidImage(path: 'storage/app/public/category/'. $category['icon'] , type: 'backend-basic') }}">
                                        </td>
                                    @endif
                                    <td>{{($category['defaultname']) }}</td>
                                    <td>{{$category?->parent?->defaultname ?? translate('category_not_found') }}</td>
                                    <td class="text-center">{{ $category['priority']}}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline-info btn-sm square-btn"
                                               title="{{ translate('edit') }}"
                                               href="{{ route('admin.sub-category.update',[$category['id']]) }}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm square-btn category-delete-button"
                                               title="{{ translate('delete') }}"
                                               id="{{ $category['id']}}">
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
                        <div class="d-flex justify-content-lg-end">
                            {{ $categories->links() }}
                        </div>
                    </div>

                    @if(count($categories)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_sub_category_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>

    <span id="route-admin-category-delete" data-url="{{ route('admin.sub-category.delete') }}"></span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/products-management.js') }}"></script>
@endpush
