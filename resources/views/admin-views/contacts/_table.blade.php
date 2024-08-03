<div class="table-responsive">
    <table id="datatable"
           style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
           class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
        <thead class="thead-light thead-50 text-capitalize">
        <tr>
            <th>{{translate('SL')}}</th>
            <th>{{translate('customer_Name')}}</th>
            <th>{{translate('contact_Info')}}</th>
            <th>{{translate('subject')}}</th>
            <th>{{translate('time_&_Date')}}</th>
            <th class="text-center">{{translate('reply_status')}}</th>
            <th class="text-center">{{translate('action')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($contacts as $key => $contact)
            <tr>
                <td>{{$contacts->firstItem()+$key}}</td>
                <td>{{$contact['name']}}</td>
                <td>
                    <div>
                        <div>{{$contact['mobile_number']}}</div>
                        <div>{{$contact['email']}}</div>
                    </div>
                </td>
                <td class="text-wrap">{{$contact['subject']}}</td>
                <td class="text-wrap" title="{{date('d M,Y h:i A',strtotime($contact['created_at']))}}" data-toggle="tooltip" data-custom-class="darker-tooltip">
                    <div class="line--limit-2 max-w-200">
                        {{date('d M,Y h:i A',strtotime($contact['created_at']))}}
                    </div>
                </td>
                <td class="text-center">
                    @if(empty($contact['reply']))
                        <span class="badge badge-secondary badge-secondary-2">{{translate('No')}} <i class="tio-all-done"></i></span>
                    @else
                        <span class="badge badge-success badge-success-2">{{translate('Yes')}} <i class="tio-all-done"></i></span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-10 justify-content-center">
                        <a title="{{translate('view')}}"
                           class="btn btn-outline-info btn-sm square-btn"
                           href="{{route('admin.contact.view',$contact->id)}}">
                            <i class="tio-invisible"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-sm delete delete-data-without-form"
                           data-id="{{$contact['id']}}"
                           data-action="{{route('admin.contact.delete')}}"
                           title="{{ translate('delete')}}">
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
        {{$contacts->links()}}
    </div>
</div>
@if(count($contacts)==0)
    <div class="text-center p-4">
        <img class="mb-3 w-160"
             src="{{dynamicAsset(path: 'public/assets/back-end/svg/illustrations/sorry.svg')}}"
             alt="{{translate('image_description')}}">
        <p class="mb-0">{{translate('no_data_to_show')}}</p>
    </div>
@endif
