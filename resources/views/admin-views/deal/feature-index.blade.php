@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app')
@section('title', translate('feature_Deal'))

@section('content')
    @php($direction = Session::get('direction'))
    <div class="content container-fluid">
        <div class="d-flex justify-content-between gap-2 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/featured_deal.png') }}" alt="">
                {{ translate('feature_deal') }}
            </h2>
            <button class="btn btn-primary" data-toggle="modal" data-target="#prioritySetModal" >
                <span data-toggle="tooltip" title="Now you can set priority of products.">{{ translate('product_priority_Setup') }}</span>
            </button>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.deal.flash') }}"
                              class="text-start onsubmit-disable-action-button"
                              method="post">
                            @csrf
                            @php($language = getWebConfig(name:'pnc_language'))
                            @php($defaultLanguage = 'en')

                            @php($defaultLanguage = $language[0])
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($language as $lang)
                                    <li class="nav-item text-capitalize">
                                        <a class="nav-link lang-link {{$lang == $defaultLanguage? 'active':'' }}"
                                           href="javascript:"
                                           id="{{$lang}}-link">{{getLanguageName($lang).'('.strtoupper($lang).')' }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="form-group">
                                <div class="row">
                                    <input type="text" name="deal_type" value="feature_deal" class="d-none">
                                    @foreach($language as $lang)
                                        <div class="col-md-12 {{$lang != $defaultLanguage ? 'd-none':'' }} lang-form"
                                             id="{{$lang}}-form">
                                            <label for="name"
                                                   class="title-color text-capitalize">{{ translate('title') }}
                                                ({{strtoupper($lang)}})</label>
                                            <input type="text" name="title[]" class="form-control" id="title"
                                                   placeholder="{{ translate('ex').':'.translate('LUX') }}" {{$lang == $defaultLanguage? 'required':'' }}>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{$lang}}" id="lang">
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label for="name"
                                               class="title-color text-capitalize">{{ translate('start_date') }}</label>
                                        <input type="date" name="start_date" id="start-date-time" required class="form-control">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="name"
                                               class="title-color text-capitalize">{{ translate('end_date') }}</label>
                                        <input type="date" name="end_date" id="end-date-time" required class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" id="reset"
                                        class="btn btn-secondary">{{ translate('reset') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('submit') }}</button>
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
                                <h5 class="mb-0 text-capitalize align-items-center d-flex gap-2">
                                    {{ translate('feature_deal_table') }}
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
                                               placeholder="{{ translate('search_by_title') }}" aria-label="Search orders"
                                               value="{{ request('searchValue') }}" required>
                                        <button type="submit" class="btn btn--primary">{{ translate('search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable"
                               style="text-align: {{$direction === "rtl" ? 'right' : 'left' }};"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('SL') }}</th>
                                <th>{{ translate('title') }}</th>
                                <th>{{ translate('start_Date') }}</th>
                                <th>{{ translate('end_Date') }}</th>
                                <th>{{ translate('active') }} / {{ translate('expired') }}</th>
                                <th class="text-center">{{ translate('status') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($flashDeals as $key => $deal)
                                <tr>
                                    <th>{{$key+1}}</th>
                                    <td>{{$deal['title']}}</td>
                                    <td>{{date('d-M-y',strtotime($deal['start_date']))}}</td>
                                    <td>{{date('d-M-y',strtotime($deal['end_date']))}}</td>
                                    <td>
                                        @if(Carbon::parse($deal['end_date'])->endOfDay()->isPast())
                                            <span class="badge badge-soft-danger"> {{ translate('expired') }} </span>
                                        @else
                                            <span class="badge badge-soft-success"> {{ translate('active') }} </span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{route('admin.deal.feature-status') }}" method="post"
                                              id="feature-status{{$deal['id']}}-form" data-from="deal">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$deal['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                       id="feature-status{{$deal['id']}}" name="status" value="1"
                                                       {{ $deal['status'] == 1 ? 'checked':'' }}
                                                       data-modal-id = "toggle-status-modal"
                                                       data-toggle-id = "feature-status{{$deal['id']}}"
                                                       data-on-image = "feature-status-on.png"
                                                       data-off-image = "feature-status-off.png"
                                                       data-on-title = "{{ translate('Want_to_Turn_ON_Featured_Deal_Status').'?' }}"
                                                       data-off-title = "{{ translate('Want_to_Turn_OFF_Featured_Deal_Status').'?' }}"
                                                       data-on-message = "<p>{{ translate('if_enabled_this_featured_deal_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                       data-off-message = "<p>{{ translate('if_disabled_this_featured_deal_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center gap-10">
                                            <a class="h-30 d-flex gap-2 align-items-center btn btn-soft-info btn-sm border-info"
                                               href="{{route('admin.deal.add-product',[$deal['id']])}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9"
                                                     viewBox="0 0 9 9" fill="none" class="svg replaced-svg">
                                                    <path
                                                        d="M9 3.9375H5.0625V0H3.9375V3.9375H0V5.0625H3.9375V9H5.0625V5.0625H9V3.9375Z"
                                                        fill="#00A3AD"></path>
                                                </svg>
                                                {{ translate('add_product') }}
                                            </a>
                                            <a title="{{ trans ('edit') }}"
                                               href="{{route('admin.deal.edit',[$deal['id']])}}"
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
                <form action="{{route('admin.business-settings.priority-setup.index',['type'=>'feature_deal'])}}" method="post">
                    @csrf
                    <div class="modal-body px-sm-4 mb-sm-3">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="modal-title flex-grow-1 text-center text-capitalize" id="prioritySetModalLabel">{{ translate('priority_settings') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="d-flex gap-4 flex-column feature-deal">
                            <div class="d-flex gap-2 justify-content-between pb-3 border-bottom">
                                <div class="d-flex flex-column">
                                    <h5 class="text-capitalize">{{ translate('use_default_sorting_list') }}</h5>
                                    <div class="d-flex gap-2 align-items-center">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                        <span class="text-dark fz-12">{{translate('currently_sorting_this_section_based_on_latest_add')}}</span>
                                    </div>
                                </div>
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input switcher-input-js" data-parent-class="feature-deal" data-from="default-sorting"
                                        {{$featureDealPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                            <div class="">
                                <div class="d-flex gap-2 justify-content-between">
                                    <div class="d-flex flex-column">
                                        <h5 class="text-capitalize">{{ translate('use_custom_sorting_list') }}</h5>
                                        <div class="d-flex gap-2 align-items-center">
                                            <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg') }}" alt="">
                                            <span class="text-dark fz-12">{{ translate('you_can_sorting_this_section_by_others_way') }}</span>
                                        </div>
                                    </div>
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input switcher-input-js" name="custom_sorting_status" value="1" data-parent-class="feature-deal" data-from="custom-sorting"
                                            {{ isset($featureDealPriority?->custom_sorting_status) && $featureDealPriority?->custom_sorting_status == 1 ? 'checked' : '' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                                <div class="custom-sorting-radio-list {{ isset($featureDealPriority?->custom_sorting_status) && $featureDealPriority?->custom_sorting_status == 1 ? '' : 'd--none' }}">
                                    <div class="border rounded p-3 d-flex flex-column gap-2 mt-4">
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" value="latest_created" id="feature-deal-sort-by-latest-created"
                                                {{ isset($featureDealPriority?->sort_by) && $featureDealPriority?->sort_by == 'latest_created' ? 'checked' : '' }}>
                                            <label class="mb-0 cursor-pointer" for="feature-deal-sort-by-latest-created">
                                                {{ translate('sort_by_latest_created') }}
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" value="first_created" id="feature-deal-sort-by-first-created"
                                                {{ isset($featureDealPriority?->sort_by) && $featureDealPriority?->sort_by == 'first_created' ? 'checked' : '' }}>
                                            <label class="mb-0 cursor-pointer" for="feature-deal-sort-by-first-created">
                                                {{ translate('sort_by_first_created') }}
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" value="most_order" id="feature-deal-sort-by-most-order"
                                                {{ isset($featureDealPriority?->sort_by) ? ($featureDealPriority?->sort_by == 'most_order' ? 'checked' : '') : 'checked' }}>
                                            <label class="mb-0 cursor-pointer" for="feature-deal-sort-by-most-order">
                                                {{ translate('sort_by_most_order') }}
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" id="feature-deal-sort-by-reviews-count" value="reviews_count"
                                                {{ isset($featureDealPriority?->sort_by) && $featureDealPriority?->sort_by == 'reviews_count' ? 'checked' : '' }}>
                                            <label class="mb-0 cursor-pointer" for="feature-deal-sort-by-reviews-count">
                                                {{ translate('sort_by_reviews_count') }}
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" id="feature-deal-sort-by-ratings" value="rating"
                                                {{ isset($featureDealPriority?->sort_by) && $featureDealPriority?->sort_by == 'rating' ? 'checked' : '' }}>
                                            <label class="mb-0 cursor-pointer" for="feature-deal-sort-by-ratings">
                                                {{ translate('sort_by_average_ratings') }}
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" value="a_to_z" id="feature-deal-sort-alphabetic-order"
                                                {{ isset($featureDealPriority?->sort_by) && $featureDealPriority?->sort_by == 'a_to_z' ? 'checked' : '' }}>
                                            <label class="mb-0 cursor-pointer text-capitalize" for="feature-deal-sort-alphabetic-order">
                                                {{ translate('sort_by_Alphabetical') }} ({{'A '.translate('to').' Z' }})
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" class="show" name="sort_by" value="z_to_a" id="feature-deal-sort-alphabetic-order-reverse"
                                                {{ isset($featureDealPriority?->sort_by) && $featureDealPriority?->sort_by == 'z_to_a' ? 'checked' : '' }}>
                                            <label class="mb-0 cursor-pointer text-capitalize" for="feature-deal-sort-alphabetic-order-reverse">
                                                {{ translate('sort_by_Alphabetical') }} ({{'Z '.translate('to').' A' }})
                                            </label>
                                        </div>
                                    </div>

                                    <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="out_of_stock_product" value="desc" class="check-box" data-parent-class="feature-deal" id="show-in-last"
                                                {{ isset($featureDealPriority?->out_of_stock_product) && $featureDealPriority?->out_of_stock_product == 'desc' ? 'checked' : '' }}>
                                            <label class="mb-0 cursor-pointer" for="show-in-last">
                                                {{ translate('show_stock_out_products_in_the_last') }}
                                            </label>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="out_of_stock_product" value="hide" class="check-box" data-parent-class="feature-deal" id="remove-product"
                                                {{ isset($featureDealPriority?->out_of_stock_product) && $featureDealPriority?->out_of_stock_product == 'hide' ? 'checked' : '' }}>
                                            <label class="mb-0 cursor-pointer" for="remove-product">
                                                {{ translate('remove_stock_out_products_from_the_list') }}
                                            </label>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="out_of_stock_product" value="default" data-parent-class="feature-deal" id="default"
                                                {{ isset($featureDealPriority?->out_of_stock_product) ? ($featureDealPriority?->out_of_stock_product == 'default' ? 'checked' : '') :'checked' }}>
                                            <label class="mb-0 cursor-pointer" for="default">
                                                {{ translate('none') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="border rounded p-3 d-flex flex-column gap-2 mt-3">
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="temporary_close_sorting" value="desc" data-parent-class="feature-deal" id="feature-deal-temporary-close-last"
                                                {{ isset($featureDealPriority?->temporary_close_sorting) && $featureDealPriority?->temporary_close_sorting == 'desc' ? 'checked' : '' }}>
                                            <label class="mb-0 cursor-pointer" for="feature-deal-temporary-close-last">
                                                {{ translate('show_product_in_the_last_is_store_is_temporarily_off') }}
                                            </label>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="temporary_close_sorting" value="hide" data-parent-class="feature-deal" id="feature-deal-temporary-close-remove"
                                                {{ isset($featureDealPriority?->temporary_close_sorting) ? ($featureDealPriority?->temporary_close_sorting == 'hide' ? 'checked' : '') :'checked' }}>
                                            <label class="mb-0 cursor-pointer" for="feature-deal-temporary-close-remove">
                                                {{ translate('remove_product_from_the_list_if_store_is_temporarily_off') }}
                                            </label>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="radio" name="temporary_close_sorting" value="default" data-parent-class="feature-deal" id="feature-deal-temporary-close-default"
                                                {{ isset($featureDealPriority?->temporary_close_sorting) ?($featureDealPriority?->temporary_close_sorting == 'default' ? 'checked' : '' ) : 'checked' }}>
                                            <label class="mb-0 cursor-pointer" for="feature-deal-temporary-close-default">
                                                {{ translate('none') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                {{ translate('save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/deal.js') }}"></script>
@endpush
