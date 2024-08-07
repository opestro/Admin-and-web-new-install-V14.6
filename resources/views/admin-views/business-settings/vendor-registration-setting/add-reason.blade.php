<div class="card mt-3">
    <div class="px-3 py-4">
        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center">
            <div class="">
                <h5 class="text-capitalize d-flex gap-1 mb-0 ">
                    {{translate('reason_list')}}
                    <span class="badge badge-soft-dark radius-50 fz-12">{{$vendorRegistrationReasons->total()}}</span>
                </h5>
            </div>
            <button type="button" class="btn btn--primary btn-sm" data-toggle="modal" data-target="#reasonModal">
                <i class="tio-add"></i>
                {{translate('add_Reason')}}
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
            <thead class="thead-light thead-50 text-capitalize">
            <tr>
                <th>{{translate('SL')}}</th>
                <th>{{translate('title')}}</th>
                <th>{{translate('description')}}</th>
                <th class="text-center">{{translate('priority')}}</th>
                <th class="text-center">{{translate('status')}}</th>
                <th class="text-center">{{translate('action')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($vendorRegistrationReasons as $key=>$reason)
                <tr>
                    <td>{{$vendorRegistrationReasons->firstItem()+$key}}</td>
                    <td>
                        <h5>{{$reason['title']}}</h5>
                    </td>
                    <td>
                        <div class="text-wrap max-w-500">{{$reason['description']}}</div>
                    </td>
                    <td class="text-center">{{$reason['priority']}}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <form action="{{route('admin.business-settings.vendor-registration-reason.update-status')}}" method="post" id="update-reason-status{{$reason['id']}}-form">
                                @csrf
                                <input name="id" value="{{$reason['id']}}" hidden>
                                <label class="switcher" for="update-reason-status{{$reason->id}}">
                                    <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                           id="update-reason-status{{$reason['id']}}" value="1" {{$reason['status'] == 1 ? 'checked' : ''}}
                                           data-modal-id = "toggle-status-modal"
                                           data-toggle-id = "update-reason-status{{$reason->id}}"
                                           data-on-image = ""
                                           data-off-image = ""
                                           data-on-title = "{{translate('want_to_Turn_ON_the_this_status').'?'}}"
                                           data-off-title = "{{translate('want_to_Turn_OFF_the_this_status').'?'}}"
                                           data-on-message = ""
                                           data-off-message = "">
                                    <span class="switcher_control"></span>
                                </label>
                            </form>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-10">
                            <a class="btn btn-outline-info btn-sm square-btn vendor-registration-reason-update-view" title="{{translate('edit')}}"
                               data-action="{{route('admin.business-settings.vendor-registration-reason.update',['id'=>$reason['id']])}}">
                                <i class="tio-edit"></i>
                            </a>
                            <a href="javascript:" class="btn btn-outline-danger btn-sm square-btn delete-data-without-form"
                               data-action="{{route('admin.business-settings.vendor-registration-reason.delete')}}"
                               data-id="{{$reason['id']}}" title="{{translate('delete')}}">
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
            {{ $vendorRegistrationReasons->links() }}
        </div>
    </div>
    @if(count($vendorRegistrationReasons)==0)
        @include('layouts.back-end._empty-state',['text'=>'no_contact_found'],['image'=>'default'])
    @endif
</div>
@include('admin-views.business-settings.vendor-registration-setting.partial.add-reason-modal')
<div class="vendor-registration-reason-update-modal"></div>
