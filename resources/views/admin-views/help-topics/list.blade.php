@php
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app')

@section('title', translate('FAQ'))

@push('css_or_js')
    <link href="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/Pages.png')}}" width="20" alt="">
                {{translate('pages')}}
            </h2>
        </div>
        @include('admin-views.business-settings.pages-inline-menu')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize">{{translate('help_topic_table')}} </h5>
                        <button class="btn btn--primary btn-icon-split for-addFaq" data-toggle="modal"
                                data-target="#addModal">
                            <i class="tio-add"></i>
                            <span class="text">{{translate('add_FAQ')}}  </span>
                        </button>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 text-start"
                                id="dataTable">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('question')}}</th>
                                    <th class="min-w-200">{{translate('answer')}}</th>
                                    <th class="text-center">{{translate('ranking')}}</th>
                                    <th class="text-center">{{translate('status')}} </th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($helps as $key => $help)
                                    <tr id="data-{{$help->id}}">
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $help['question'] }}</td>
                                        <td>{{ $help['answer'] }}</td>
                                        <td class="text-center">{{ $help['ranking'] }}</td>

                                        <td>
                                            <form action="{{ route('admin.helpTopic.status', ['id'=>$help['id']])}}"
                                                  method="get" id="help-topic-status{{$help['id']}}-form"
                                                  class="helpTopic_status_form">
                                                <label class="switcher mx-auto" for="help-topic-status{{$help['id']}}">
                                                    <input type="checkbox" class="switcher_input toggle-switch-message" value="1"
                                                           id="help-topic-status{{$help['id']}}" {{ $help['status'] == 1 ? 'checked':'' }}
                                                           data-modal-id = "toggle-status-modal"
                                                           data-toggle-id = "help-topic-status{{$help['id']}}"
                                                           data-on-image = "category-status-on.png"
                                                           data-off-image = "category-status-off.png"
                                                           data-on-title = "{{translate('want_to_Turn_ON_This_FAQ').'?'}}"
                                                           data-off-title = "{{translate('want_to_Turn_OFF_This_FAQ').'?'}}"
                                                           data-on-message = "<p>{{translate('if_you_enable_this_FAQ_will_be_shown_in_the_user_app_and_website')}}</p>"
                                                           data-off-message = "<p>{{translate('if_you_disable_this_FAQ_will_not_be_shown_in_the_user_app_and_website')}}</p>">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-10">
                                                <a class="btn btn-outline--primary btn-sm edit"
                                                   data-toggle="modal" data-target="#editModal"
                                                   title="{{ translate('edit')}}"
                                                   data-id="{{ route('admin.helpTopic.update', ['id'=>$help['id']]) }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm delete-data-without-form"
                                                   title="{{ translate('delete')}}"
                                                   data-action="{{route('admin.helpTopic.delete')}}"
                                                   data-id="{{$help['id']}}">
                                                    <i class="tio-delete"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="addModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{translate('add_Help_Topic')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                                aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.helpTopic.add-new') }}" method="post" id="addForm">
                        @csrf
                        <div class="modal-body text-start">
                            <div class="form-group">
                                <label>{{translate('question')}}</label>
                                <input type="text" class="form-control" name="question"
                                       placeholder="{{translate('type_Question')}}">
                            </div>


                            <div class="form-group">
                                <label>{{translate('answer')}}</label>
                                <textarea class="form-control" name="answer" cols="5"
                                          rows="5" placeholder="{{translate('type_Answer')}}"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="control-label">{{translate('status')}}</div>
                                        <label class="mt-2">
                                            <input type="checkbox" name="status" id="e_status" value="1"
                                                   class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                            <span
                                                class="custom-switch-description">{{translate('active')}}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="ranking">{{translate('ranking')}}</label>
                                    <input type="number" name="ranking" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{translate('close')}}</button>
                            <button class="btn btn--primary">{{translate('save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize">{{translate('edit_modal_help_topic')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" id="update-form-submit" class="text-start">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label>{{translate('question')}}</label>
                            <input type="text" class="form-control e_name" name="question"
                                   placeholder="{{translate('type_Question')}}"
                                   id="question-filed">
                        </div>


                        <div class="form-group">
                            <label>{{translate('answer')}}</label>
                            <textarea class="form-control" name="answer" cols="5"
                                      rows="5" placeholder="{{translate('type_Answer')}}"
                                      id="answer-field"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="ranking">{{translate('ranking')}}</label>
                                <input type="number" name="ranking" class="form-control" id="ranking-field" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{translate('close')}}</button>
                        <button class="btn btn--primary">{{translate('update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/business-setting.js')}}"></script>
@endpush
