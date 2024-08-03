@php
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app')

@section('title', translate('language_Translate'))

@push('css_or_js')
    <link href="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    @php($direction = Session::get('direction') === "rtl" ? 'right' : 'left')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb" class="w-100"
             style="text-align: {{$direction}};">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{route('admin.dashboard.index')}}">{{translate('dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{translate('language')}}</li>
            </ol>
        </nav>

        <div class="row __mt-20">
            <div class="col-md-12">
                <div class="card" style="text-align: {{$direction}};">
                    <div class="card-header">
                        <h5>{{translate('language_content_table')}}</h5>
                        <a href="{{route('admin.business-settings.language.index')}}"
                           class="btn btn-sm btn-danger btn-icon-split float-right">
                            <span class="text text-capitalize">{{translate('back')}}</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead class="bg-white">
                                <tr>
                                    <th class="max-width-100px">{{translate('SL')}}</th>
                                    <th class="width-400px">{{translate('key')}}</th>
                                    <th class="max-width-300px">{{translate('value')}}</th>
                                    <th class="max-width-150px">{{translate('auto_translate')}}</th>
                                    <th class="max-width-150px">{{translate('update')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <i class="fa fa-spinner fa-spin"></i> {{ translate('Loading') }}...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="get-auto-translate-route-and-text" data-route="{{route('admin.business-settings.language.auto-translate',[$lang])}}"
          data-success-text="{{translate('key_translated_successfully')}}">
    </span>
    <span id="get-translate-route-and-text" data-route="{{route('admin.business-settings.language.translate-submit',[$lang])}}"
          data-success-text="{{translate('text_updated_successfully')}}">
    </span>
    <span id="get-data-table-route-and-text"
          data-route="{{ route('admin.business-settings.language.translate.list', ['lang'=>$lang]) }}"
          data-page-length="{{ getWebConfig(name:'pagination_limit') }}"
          data-info="{{ translate('showing').' '.'_START_'.' '.translate('To').' '.'_END_'.' '.translate('of').' '.'_TOTAL_'.' '.translate('entries') }}"
          data-info-empty="{{ translate('showing').' '. 0 .translate('To').' '. 0 .' ' .translate('of').' '. 0 .' '.translate('entries') }}"
          data-info-filtered="{{ translate('filtered').' '.'_MAX_'.' '.translate('total_entries') }}"
          data-empty-table="{{  translate('no_data_found') }}"
          data-zero-records="{{ translate('no_matching_data_found') }}"
          data-search="{{ translate('search').':' }}"
          data-length-menu="{{ translate('show').'_MENU_'.translate('entries')  }}"
          data-paginate-first="{{translate('first')}}"
          data-paginate-last="{{translate('last')}}"
          data-paginate-next="{{translate('next')}}"
          data-paginate-previous="{{translate('previous')}}"
    ></span>
@endsection

@push('script')

    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/translate.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
@endpush
