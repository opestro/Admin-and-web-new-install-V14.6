@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Session
@endphp
@extends('layouts.back-end.app')

@section('title', translate('flash_Deal'))

@section('content')
    @php($direction = Session::get('direction'))
    <div class="content container-fluid">
        <div class="d-flex justify-content-between gap-2 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/flash_deal.png')}}" alt="">
                {{translate('flash_deals')}}
            </h2>
            <button class="btn btn-primary" data-toggle="modal" data-target="#prioritySetModal" >
                <span data-toggle="tooltip" title="Now you can set priority of products.">{{translate('product_priority_Setup')}}</span>
            </button>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.deal.flash')}}" method="post"
                              class="text-start onsubmit-disable-action-button"
                              enctype="multipart/form-data" >
                            @csrf
                            @php($language = getWebConfig(name:'pnc_language'))
                            @php($defaultLanguage = 'en')
                            @php($defaultLanguage = $language[0])
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($language as $lang)
                                    <li class="nav-item text-capitalize font-weight-medium">
                                        <a class="nav-link lang-link {{$lang == $defaultLanguage ? 'active':''}}"
                                           href="javascript:"
                                           id="{{$lang}}-link">{{getLanguageName($lang).'('.strtoupper($lang).')'}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col-lg-6">
                                    @foreach($language as $lang)
                                        <div class="{{$lang != $defaultLanguage ? 'd-none':''}} lang-form"
                                             id="{{$lang}}-form">
                                            <input type="text" name="deal_type" value="flash_deal" class="d-none">
                                            <div class="form-group">
                                                <label for="name"
                                                       class="title-color font-weight-medium text-capitalize">{{ translate('title')}}
                                                    ({{strtoupper($lang)}})</label>
                                                <input type="text" name="title[]" class="form-control" id="title"
                                                       placeholder="{{translate('ex').':'.translate('LUX')}}"
                                                    {{$lang == $defaultLanguage ? 'required':''}}>
                                            </div>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{$lang}}" id="lang">
                                    @endforeach
                                    <div class="form-group">
                                        <label for="name"
                                               class="title-color font-weight-medium text-capitalize">{{ translate('start_date')}}</label>
                                        <input type="date" name="start_date"  id="start-date-time" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="name"
                                               class="title-color font-weight-medium text-capitalize">{{ translate('end_date')}}</label>
                                        <input type="date" name="end_date" id="end-date-time" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="text-center">
                                            <img class="border radius-10 ratio-4:1 max-w-655px w-100" id="viewer"
                                                 src="{{dynamicAsset(path: 'public/assets/front-end/img/placeholder.png')}}"
                                                 alt="{{translate('banner_image')}}"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name"
                                               class="title-color font-weight-medium text-capitalize">{{translate('upload_image')}}</label>
                                        <span class="text-info ml-1">( {{translate('ratio').' '.'5:1'}} )</span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="custom-file-upload"
                                                   class="custom-file-input image-input" data-image-id="viewer"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label text-capitalize"
                                                   for="custom-file-upload">{{translate('choose_file')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" id="reset"
                                        class="btn btn-secondary px-4">{{ translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-4">{{ translate('submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="mb-0 text-capitalize d-flex gap-2">
                                    {{ translate('flash_deal_table')}}
                                    <span
                                        class="badge badge-soft-dark radius-50 fz-12">{{ $flashDeals->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue"
                                               class="form-control"
                                               placeholder="{{translate('search_by_Title')}}" aria-label="Search orders"
                                               value="{{ request('searchValue') }}" required>
                                        <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable"
                               style="text-align: {{$direction === "rtl" ? 'right' : 'left'}};"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('SL')}}</th>
                                <th>{{ translate('title')}}</th>
                                <th>{{ translate('duration')}}</th>
                                <th>{{ translate('status')}}</th>
                                <th  class="text-center">{{ translate('active_products')}}</th>
                                <th  class="text-center">{{ translate('publish')}}</th>
                                <th class="text-center">{{ translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($flashDeals as $key => $deal)
                                <tr>
                                    <td>{{$flashDeals->firstItem()+ $key }}</td>
                                    <td><span class="font-weight-semibold">{{$deal['title']}}</span></td>
                                    <td>{{date('d-M-y',strtotime($deal['start_date'])).'-'.' '}}
                                        {{date('d-M-y',strtotime($deal['end_date']))}}</td>
                                    <td>
                                        @if(Carbon::parse($deal['end_date'])->endOfDay()->isPast())
                                            <span class="badge badge-soft-danger">{{ translate('expired')}} </span>
                                        @else
                                            <span class="badge badge-soft-success"> {{ translate('active')}} </span>
                                        @endif
                                    </td>
                                    <td  class="text-center">{{ $deal->products_count }}</td>
                                    <td>
                                        <form action="{{route('admin.deal.status-update')}}" method="post"
                                              id="flash-deal-status{{$deal['id']}}-form" data-from="deal">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$deal['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                       id="flash-deal-status{{$deal['id']}}" name="status"
                                                       value="1"
                                                       {{ $deal['status'] == 1?'checked':'' }}
                                                       data-modal-id = "toggle-status-modal"
                                                       data-toggle-id = "flash-deal-status{{$deal['id']}}"
                                                       data-on-image = "flash-deal-status-on.png"
                                                       data-off-image = "flash-deal-status-off.png"
                                                       data-on-title = "{{translate('Want_to_Turn_ON_Flash_Deal_Status').'?'}}"
                                                       data-off-title = "{{translate('Want_to_Turn_OFF_Flash_Deal_Status').'?'}}"
                                                       data-on-message = "<p>{{translate('if_enabled_this_flash_sale_will_be_available_on_the_website_and_customer_app')}}</p>"
                                                       data-off-message = "<p>{{translate('if_disabled_this_flash_sale_will_be_hidden_from_the_user_website_and_customer_app')}}</p>">`)">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-10">
                                            <a class="h-30 d-flex gap-2 text-capitalize align-items-center btn btn-soft-info btn-sm border-info"
                                               href="{{route('admin.deal.add-product',[$deal['id']])}}">
                                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/plus.svg')}}" class="svg"
                                                     alt="">
                                                {{translate('add_product')}}
                                            </a>
                                            <a title="{{translate('edit')}}"
                                               href="{{route('admin.deal.update',[$deal['id']])}}"
                                               class="btn btn-outline--primary btn-sm edit">
                                                <i class="tio-edit"></i>
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
                            {{$flashDeals->links()}}
                        </div>
                    </div>

                    @if(count($flashDeals)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="prioritySetModal" tabindex="-1" aria-labelledby="prioritySetModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('admin.business-settings.priority-setup.index',['type'=>'flash_deal'])}}" method="post">
                    @csrf
                    <div class="modal-body px-sm-4 mb-sm-3">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="modal-title flex-grow-1 text-center text-capitalize" id="prioritySetModalLabel">{{translate('priority_settings')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="d-flex gap-4 flex-column flash-deal">
                            <div class="d-flex gap-2 justify-content-between pb-3 border-bottom">
                                <div class="d-flex flex-column">
                                    <h5 class="text-capitalize">{{translate('use_default_sorting_list')}}</h5>
                                    <div class="d-flex gap-2 align-items-center">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg')}}" alt="">
                                        <span class="text-dark fz-12">{{translate('currently_sorting_this_section_based_on_first_created_products')}}</span>
                                    </div>
                                </div>
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input switcher-input-js" data-parent-class="flash-deal" data-from="default-sorting"
                                        {{$flashDealPriority?->custom_sorting_status == 1 ? '' : 'checked'}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                            <div class="">
                                <div class="d-flex gap-2 justify-content-between">
                                    <div class="d-flex flex-column">
                                        <h5 class="text-capitalize">{{translate('use_custom_sorting_list')}}</h5>
                                        <div class="d-flex gap-2 align-items-center">
                                            <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg')}}" alt="">
                                            <span class="text-dark fz-12">{{translate('you_can_sorting_this_section_by_others_way')}}</span>
                                        </div>
                                    </div>
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input switcher-input-js" name="custom_sorting_status" value="1" data-parent-class="flash-deal" data-from="custom-sorting"
                                            {{isset($flashDealPriority?->custom_sorting_status) && $flashDealPriority?->custom_sorting_status == 1 ? 'checked' : ''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                                <div class="custom-sorting-radio-list {{isset($flashDealPriority?->custom_sorting_status) && $flashDealPriority?->custom_sorting_status == 1 ? '' : 'd--none'}}">
                                    <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" value="latest_created" id="flash-deal-sort-by-latest-created"
                                                {{isset($flashDealPriority?->sort_by) && $flashDealPriority?->sort_by == 'latest_created' ? 'checked' : ''}}>
                                            <label class="mb-0 cursor-pointer" for="flash-deal-sort-by-latest-created">
                                                {{translate('sort_by_latest_created')}}
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" value="first_created" id="flash-deal-sort-by-first-created"
                                                {{isset($flashDealPriority?->sort_by) && $flashDealPriority?->sort_by == 'first_created' ? 'checked' : ''}}>
                                            <label class="mb-0 cursor-pointer" for="flash-deal-sort-by-first-created">
                                                {{translate('sort_by_first_created')}}
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" value="most_order" id="flash-deal-sort-by-most-order"
                                                {{isset($flashDealPriority?->sort_by) ? ($flashDealPriority?->sort_by == 'most_order' ? 'checked' : '') : 'checked'}}>
                                            <label class="mb-0 cursor-pointer" for="flash-deal-sort-by-most-order">
                                                {{translate('sort_by_most_order')}}
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" id="flash-deal-sort-by-reviews-count" value="reviews_count"
                                                {{isset($flashDealPriority?->sort_by) && $flashDealPriority?->sort_by == 'reviews_count' ? 'checked' : ''}}>
                                            <label class="mb-0 cursor-pointer" for="flash-deal-sort-by-reviews-count">
                                                {{ translate('sort_by_reviews_count') }}
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" id="flash-deal-sort-by-ratings" value="rating"
                                                {{isset($flashDealPriority?->sort_by) && $flashDealPriority?->sort_by == 'rating' ? 'checked' : ''}}>
                                            <label class="mb-0 cursor-pointer" for="flash-deal-sort-by-ratings">
                                                {{translate('sort_by_average_ratings')}}
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" value="a_to_z" id="flash-deal-alphabetic-order"
                                                {{isset($flashDealPriority?->sort_by) && $flashDealPriority?->sort_by == 'a_to_z' ? 'checked' : ''}}>
                                            <label class="mb-0 cursor-pointer text-capitalize" for="flash-deal-alphabetic-order">
                                                {{translate('sort_by_Alphabetical')}} ({{'A '.translate('to').' Z' }})
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" value="z_to_a" id="flash-deal-alphabetic-order-reverse"
                                                {{isset($flashDealPriority?->sort_by) && $flashDealPriority?->sort_by == 'z_to_a' ? 'checked' : ''}}>
                                            <label class="mb-0 cursor-pointer text-capitalize" for="flash-deal-alphabetic-order-reverse">
                                                {{translate('sort_by_Alphabetical')}} ({{'Z '.translate('to').' A' }})
                                            </label>
                                        </div>

                                    </div>

                                    <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="out_of_stock_product" value="desc" class="check-box" data-parent-class="flash-deal" id="show-in-last"
                                                {{isset($flashDealPriority?->out_of_stock_product) && $flashDealPriority?->out_of_stock_product == 'desc' ? 'checked' : ''}}>
                                            <label class="mb-0 cursor-pointer" for="show-in-last">
                                                {{translate('show_stock_out_products_in_the_last')}}
                                            </label>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="out_of_stock_product" value="hide" class="check-box" data-parent-class="flash-deal" id="remove-product"
                                                {{isset($flashDealPriority?->out_of_stock_product) && $flashDealPriority?->out_of_stock_product == 'hide' ? 'checked' : ''}}>
                                            <label class="mb-0 cursor-pointer" for="remove-product">
                                                {{translate('remove_stock_out_products_from_the_list')}}
                                            </label>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="out_of_stock_product" value="default" data-parent-class="flash-deal" id="default"
                                                {{isset($flashDealPriority?->out_of_stock_product) ? ($flashDealPriority?->out_of_stock_product == 'default' ? 'checked' : '') :'checked'}}>
                                            <label class="mb-0 cursor-pointer" for="default">
                                                {{translate('none')}}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="temporary_close_sorting" value="desc" data-parent-class="flash-deal" id="flash-deal-temporary-close-last"
                                                {{isset($flashDealPriority?->temporary_close_sorting) && $flashDealPriority?->temporary_close_sorting == 'desc' ? 'checked' : ''}}>
                                            <label class="mb-0 cursor-pointer" for="flash-deal-temporary-close-last">
                                                {{ translate('show_product_in_the_last_is_store_is_temporarily_off') }}
                                            </label>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="temporary_close_sorting" value="hide" data-parent-class="flash-deal" id="flash-deal-temporary-close-remove"
                                                {{isset($flashDealPriority?->temporary_close_sorting) ? ($flashDealPriority?->temporary_close_sorting == 'hide' ? 'checked' : '') :'checked'}}>
                                            <label class="mb-0 cursor-pointer" for="flash-deal-temporary-close-remove">
                                                {{ translate('remove_product_from_the_list_if_store_is_temporarily_off') }}
                                            </label>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="temporary_close_sorting" value="default" data-parent-class="flash-deal" id="flash-deal-temporary-close-default"
                                                {{isset($flashDealPriority?->temporary_close_sorting) ?( $flashDealPriority?->temporary_close_sorting == 'default' ? 'checked' : '' ) : 'checked'}}>
                                            <label class="mb-0 cursor-pointer" for="flash-deal-temporary-close-default">
                                                {{ translate('none') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-5">{{translate('save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/deal.js')}}"></script>
@endpush
