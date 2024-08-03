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
            <div class="card-header flex-wrap gap-2">
                <div class="">
                    <h4 class="title m-0">{{translate('404 Logs')}}</h4>
                    <p class="m-0">
                        {{ translate('track_instances_of_page_not_found_errors_faced_by_users_on_your_website') }}
                        <a href="{{ 'https://6amtech.com/blog/404-logs/' }}" target="_blank" class="text-primary text-underline font-weight-semibold">
                            {{translate('Learn_more')}}
                        </a>
                    </p>
                </div>
                <div>
                    <button type="button"  class="btn bg-soft-danger text-danger border border-danger text-capitalize delete-data d-none {{env('APP_MODE')!='demo'? '' : 'call-demo'}}" id="clear-all-log" data-id="delete-selected-error-logs">{{translate('clear_all_log')}}</button>
                    <form action="{{ env('APP_MODE') !='demo' ? route('admin.error-logs.delete-selected-error-logs') : 'javascript:'}}"
                          method="post" id="delete-selected-error-logs" >
                        @csrf @method('delete')
                        <div id="selected-ids"></div>
                    </form>
                </div>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer table-hover d-lg-table">
                        <thead class="thead-light">
                        <tr>
                            <th class="w-95px">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="check-item-2">
                                        <input type="checkbox" id="master-check-box">
                                    </span>
                                    <span>{{translate('URL')}}</span>
                                </div>
                            </th>
                            <th class="w-45px text-center">{{translate('hits')}}</th>
                            <th class="w-200px text-center text-capitalize">{{translate('last_hit_date')}}</th>
                            <th class="w-200px text-center text-capitalize">{{translate('redirection_link')}}</th>
                            <th class="text-center w-60px">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($errorLogs as $key=>$errorLog)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2 overflow-hidden text-wrap">
                                    <span class="check-item-2">
                                        <input type="checkbox" class="row-check-box" value="{{$errorLog['id']}}">
                                    </span>
                                        <a href="javascript:" class="text-primary text-underline">{{$errorLog['url']}}</a>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="font-weight-semibold text-title">{{$errorLog['hit_counts']}}</span>
                                </td>
                                <td class="text-center">
                                    <span>{{date('M y', $errorLog['update_at'])}} <small>{{date('h:i A',$errorLog['update_at'])}}</small></span>
                                </td>
                                <td>
                                    @if($errorLog['redirect_url'])
                                        <div class="d-flex flex-wrap justify-content-center">
                                            <button class="btn edit-content-btn text-capitalize edit-redirect-link" data-id="{{$errorLog['id']}}"
                                                data-redirect-url="{{$errorLog['redirect_url']}}"
                                                data-redirect-status="{{$errorLog['redirect_status']}}"
                                            >
                                                <i class="tio-edit"></i> {{translate('edit_link')}}
                                            </button>
                                        </div>
                                    @else
                                        <div class="d-flex flex-wrap justify-content-center">
                                            <button class="btn add-content-btn text-capitalize add-redirect-link" data-id="{{$errorLog['id']}}">
                                                <i class="tio-add"></i> {{translate('add_link')}}
                                            </button>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center ">
                                        <a class="{{env('APP_MODE')!='demo'? 'delete-data' : 'call-demo'}}" href="javascript:" data-id="error-logs-{{ $errorLog['id']}}">
                                            <img src="{{dynamicAsset('public/assets/back-end/img/delete-outlined.png')}}" alt="" width="30">
                                        </a>
                                    </div>
                                    <form action="{{ route('admin.error-logs.index',['id'=>$errorLog['id']]) }}"
                                          method="post" id="error-logs-{{ $errorLog['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="page-area px-4 pt-4">
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                            {{ $errorLogs->links() }}
                        </div>
                    </div>
                </div>
                @if(count($errorLogs) == 0)
                    @include('layouts.back-end._empty-state',['text'=>'no_error_logs_data_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-edit-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('admin.error-logs.index')}}" method="post">
                    @csrf
                    <input name="id" hidden>
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">{{translate('redirection_link')}}</h3>
                        <button type="button" class="position-absolute right-0 top-2 bg-transparent border-0 p-3 opacity--40 fz-16" data-dismiss="modal">
                            <i class="tio-clear-circle"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">{{translate('redirection_link')}}</label>
                        <input type="text" class="form-control" name="redirect_url" placeholder="{{translate('enter_you_link')}}">

                        <label class="form-label mt-3">{{translate('Status')}}</label>
                        <div class="form-group d-flex gap-4 align-items-center">
                            <div class="custom-control custom-radio d-flex align-items-center">
                                <input type="radio" class="custom-control-input" value="301"
                                       name="redirect_status"
                                       id="logs_redirect_status_301">
                                <label class="custom-control-label"
                                       for="logs_redirect_status_301">
                                    {{ '301' }}
                                </label>
                            </div>
                            <div class="custom-control custom-radio d-flex align-items-center">
                                <input type="radio" class="custom-control-input" value="302"
                                       name="redirect_status"
                                       id="logs_redirect_status_302">
                                <label class="custom-control-label"
                                       for="logs_redirect_status_302">
                                    {{ '302' }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="{{ env('APP_MODE') == 'demo' ? 'button' : 'submit' }}" class="btn btn--primary submit-button {{env('APP_MODE')!='demo'? '' : 'call-demo'}}">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/error-logs.js')}}"></script>
@endpush
