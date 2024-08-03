@extends('layouts.back-end.app')

@section('title', translate('FAQs'))
@section('content')
    <div class="content container-fluid">
        @include('admin-views.business-settings.vendor-registration-setting.partial.inline-menu')
        <div class="card mt-3">
            <div class="px-3 py-4">
                <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center">
                    <div class="">
                        <h5 class="text-capitalize d-flex gap-2">
                            {{translate('FAQ_list')}}
                            <span class="badge badge-soft-dark radius-50 fz-12">{{$helps->total()}}</span>
                        </h5>
                    </div>
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <form action="{{url()->current()}}" method="GET">
                            <div class="input-group input-group-custom input-group-merge">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="" type="search" name="searchValue" class="form-control" placeholder="{{translate('search_by_question_&_answer')}}" value="{{ request('searchValue') }}">
                                <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                            </div>
                        </form>
                        <button type="button" class="btn btn--primary" data-toggle="modal" data-target="#faqAddModal">
                            <i class="tio-add"></i>
                            {{translate('add_FAQ')}}
                        </button>
                    </div>

                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('question')}}</th>
                            <th>{{translate('answer')}}</th>
                            <th class="text-center">{{translate('priority')}}</th>
                            <th class="text-center">{{translate('status')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($helps as $key => $help)
                        <tr id="data-{{$help['id']}}">
                            <td>{{$helps->firstItem()+$key}}</td>
                            <td>
                                <h5 class="text-wrap max-w-500 min-w-120">{{ $help['question'] }}</h5>
                            </td>
                            <td>
                                <div class="text-wrap max-w-500 min-w-200">{{ $help['answer'] }}</div>
                            </td>
                            <td class="text-center">{{ $help['ranking'] }}</td>
                            <td class="text-center">
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
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-center justify-content-md-end">
                    {{ $helps->links() }}
                </div>
            </div>
            @if(count($helps)==0)
                @include('layouts.back-end._empty-state',['text'=>'no_contact_found'],['image'=>'default'])
            @endif
        </div>
    </div>

    <div class="modal fade" id="faqAddModal" tabindex="-1" role="dialog" aria-labelledby="faqAddModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.helpTopic.add-new') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input hidden name="type" value="vendor_registration">
                    <div class="modal-header">
                        <h5 class="modal-title flex-grow-1 text-center" id="faqAddModalLabel">{{translate('FAQs')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="title-color">{{translate('question')}}</label>
                            <input type="text" name="question" class="form-control" placeholder="{{translate('enter_question')}}" required="">
                        </div>
                        <div class="form-group">
                            <label class="title-color">{{translate('answer')}}</label>
                            <textarea class="form-control" name="answer" rows="4" placeholder="{{translate('write_answer').'....'}}"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="title-color">{{translate('priority')}}</label>
                            <select name="ranking" class="form-control">
                                @for($index = 1; $index <= 15; $index++)
                                    <option value="{{ $index }}">{{ $index }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="border rounded p-3 d-flex justify-content-between gap-2 align-items-center">
                                <div class="text-dark">{{translate('turning_status_off_will_not_show_this_FAQ_in_the_list')}}</div>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="fw-semibold text-dark">{{translate('status')}}</span>
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" name="status" value="1">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="faqAddModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="post" id="update-form-submit" enctype="multipart/form-data">
                    @csrf
                    <input hidden name="type" value="vendor_registration">
                    <div class="modal-header">
                        <h5 class="modal-title flex-grow-1 text-center" id="faqAddModalLabel">{{translate('FAQs')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="title-color">{{translate('question')}}</label>
                            <input type="text" name="question" class="form-control" id="question-filed" placeholder="{{translate('enter_question')}}" required="">
                        </div>
                        <div class="form-group">
                            <label class="title-color">{{translate('answer')}}</label>
                            <textarea class="form-control" name="answer" rows="4" id="answer-field" placeholder="{{translate('write_answer').'....'}}"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="title-color">{{translate('priority')}}</label>
                            <select name="ranking" class="form-control" id="ranking-field">
                                @for($index = 1; $index <= 15; $index++)
                                    <option value="{{ $index }}">{{ $index }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="border rounded p-3 d-flex justify-content-between gap-2 align-items-center">
                                <div class="text-dark">{{translate('turning_status_off_will_not_show_this_FAQ_in_the_list')}}</div>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="fw-semibold text-dark">{{translate('status')}}</span>
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" id="check-status" name="status" value="1" checked>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/vendor-registration-setting.js')}}"></script>
@endpush
