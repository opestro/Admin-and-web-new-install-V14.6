@extends('layouts.back-end.app')

@section('title', translate('deal_Of_The_Day'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/deal_of_the_day.png')}}" alt="">
                {{translate('deal_of_the_day')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.deal.day')}}"
                              class="text-start onsubmit-disable-action-button"
                              method="post">
                            @csrf
                            @php($language = getWebConfig(name:'pnc_language'))
                            @php($defaultLanguage = 'en')
                            @php($defaultLanguage = $language[0])
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($language as $lang)
                                    <li class="nav-item text-capitalize">
                                        <a class="nav-link lang-link {{$lang == $defaultLanguage? 'active':''}}"
                                           href="javascript:"
                                           id="{{$lang}}-link">{{ getLanguageName($lang).'('.strtoupper($lang).')' }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="form-group">
                                @foreach($language as $lang)
                                    <div class="row {{$lang != $defaultLanguage ? 'd-none':''}} lang-form"
                                         id="{{$lang}}-form">
                                        <div class="col-md-12">
                                            <label for="name">{{ translate('title')}} ({{strtoupper($lang)}})</label>
                                            <input type="text" name="title[]" class="form-control" id="title"
                                                   placeholder="{{translate('ex').' '.':'.' '.translate('LUX')}}"
                                                {{$lang == $defaultLanguage? 'required':''}}>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}" id="lang">
                                @endforeach
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <label for="name" class="title-color">{{ translate('products')}}</label>
                                        <input type="text" class="product_id" name="product_id" hidden>
                                        <div class="dropdown select-product-search w-100">
                                            <button class="form-control text-start dropdown-toggle text-capitalize select-product-button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{translate('select_product')}}
                                            </button>
                                            <div class="dropdown-menu w-100 px-2">
                                                <div class="search-form mb-3">
                                                    <button type="button" class="btn"><i class="tio-search"></i>
                                                    </button>
                                                    <input type="text"
                                                           class="js-form-search form-control search-bar-input search-product"
                                                           placeholder="{{translate('search menu').'...'}}">
                                                </div>
                                                <div
                                                    class="d-flex flex-column gap-3 max-h-40vh overflow-y-auto overflow-x-hidden search-result-box">
                                                    @include('admin-views.partials._search-product',['products'=>$products])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" id="reset"
                                        class="btn btn-secondary px-5 reset-button">{{ translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-5">{{ translate('submit')}}</button>
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
                                <h5 class="d-flex align-items-center gap-2">
                                    {{ translate('deal_of_the_day')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{ $deals->total() }}</span>
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
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('SL')}}</th>
                                <th>{{ translate('title')}}</th>
                                <th>{{ translate('product_info')}}</th>
                                <th>{{ translate('status')}}</th>
                                <th class="text-center">{{ translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($deals as $k=>$deal)
                                <tr>
                                    <th>{{$deals->firstItem()+ $k}}</th>
                                    <td>
                                        <a href="javascript:" target="_blank"
                                           class="font-weight-semibold title-color hover-c1">{{$deal['title']}}
                                        </a>
                                    </td>
                                    <td>{{ isset($deal->product) ? $deal->product->name : translate("not_selected" )}}</td>
                                    <td>
                                        <form action="{{route('admin.deal.day-status-update')}}" method="post"
                                              id="deal-of-the-day{{$deal['id']}}-form" data-from="deal">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$deal['id']}}">
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                       id="deal-of-the-day{{$deal['id']}}" name="status" value="1"
                                                       {{ $deal['status'] == 1 ? 'checked':'' }}
                                                       data-modal-id = "toggle-status-modal"
                                                       data-toggle-id = "deal-of-the-day{{$deal['id']}}"
                                                       data-on-image = "deal-of-the-day-status-on.png"
                                                       data-off-image = "deal-of-the-day-status-off.png"
                                                       data-on-title = "{{translate('want_to_Turn_ON_Deal_of_the_Day_Status').'?'}}"
                                                       data-off-title = "{{translate('want_to_Turn_OFF_Deal_of_the_Day_Status').'?'}}"
                                                       data-on-message = "<p>{{translate('if_enabled_this_deal_of_the_day_will_be_available_on_the_website_and_customer_app')}}</p>"
                                                       data-off-message = "<p>{{translate('if_disabled_this_deal_of_the_day_will_be_hidden_from_the_website_and_customer_app')}}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-10">
                                            <a title="{{ trans ('edit')}}"
                                               href="{{route('admin.deal.day-update',[$deal['id']])}}"
                                               class="btn btn-outline--primary btn-sm edit">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a title="{{ trans ('delete')}}"
                                               class="btn btn-outline-danger btn-sm delete-data-without-form"
                                               data-action="{{route('admin.deal.day-delete')}}"
                                               data-id="{{$deal['id']}}">
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
                            {{$deals->links()}}
                        </div>
                    </div>
                    @if(count($deals)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/search-product.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/deal.js')}}"></script>
@endpush
