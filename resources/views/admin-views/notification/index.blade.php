@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.back-end.app')
@section('title', translate('add_new_notification'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/push_notification.png')}}" alt="">
                {{translate('send_notification')}}
            </h2>
        </div>
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.notification.index')}}" method="post" class="text-start"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color text-capitalize"
                                               for="exampleFormControlInput1">{{translate('title')}} </label>
                                        <input type="text" name="title" class="form-control"
                                               placeholder="{{translate('new_notification')}}"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color text-capitalize"
                                               for="exampleFormControlInput1">{{translate('description')}} </label>
                                        <textarea name="description" class="form-control text-area-max-min" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-center">
                                            <img class="upload-img-view mb-4" id="viewer"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/900x400/img1.jpg')}}"
                                                 alt="{{translate('image')}}"/>
                                        </div>
                                        <label
                                            class="title-color text-capitalize">{{translate('image')}} </label>
                                        <span class="text-info">({{translate('ratio').'1:1'}})</span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" class="custom-file-input image-input"
                                                   data-image-id="viewer"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label"
                                                   for="customFileEg1">{{translate('choose_File')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{translate('reset')}} </button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        class="btn btn--primary {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('send_Notification')}}  </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                    {{ translate('push_notification_table')}}
                                    <span
                                        class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $notifications->total() }}</span>
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
                                               placeholder="{{translate('search_by_title')}}"
                                               aria-label="Search orders" value="{{ $searchValue }}" required>
                                        <button type="submit"
                                                class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}} </th>
                                <th>{{translate('title')}} </th>
                                <th>{{translate('description')}} </th>
                                <th>{{translate('image')}} </th>
                                <th>{{translate('notification_count')}} </th>
                                <th>{{translate('status')}} </th>
                                <th>{{translate('resend')}} </th>
                                <th class="text-center">{{translate('action')}} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notifications as $key=>$notification)
                                <tr>
                                    <td>{{$notifications->firstItem()+ $key}}</td>
                                    <td>
                                        <span class="d-block">
                                            {{Str::limit($notification['title'],30)}}
                                        </span>
                                    </td>
                                    <td>
                                        {{Str::limit($notification['description'],40)}}
                                    </td>
                                    <td>
                                        <img class="min-w-75" width="75" height="75"
                                             src="{{ getValidImage(path: 'storage/app/public/notification/'.$notification['image']?? '', type: 'backend-basic') }}" alt="">
                                    </td>
                                    <td id="count-{{$notification->id}}">{{ $notification['notification_count'] }}</td>
                                    <td>
                                        <form action="{{route('admin.notification.update-status')}}" method="post"
                                              id="notification-status{{$notification['id']}}-form"
                                              class="notification_status_form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$notification['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message"
                                                       id="notification-status{{$notification['id']}}" name="status" value="1"
                                                       {{ $notification['status'] == 1 ? 'checked':'' }}
                                                       data-modal-id = "toggle-status-modal"
                                                       data-toggle-id = "notification-status{{$notification['id']}}"
                                                       data-on-image = "notification-on.png"
                                                       data-off-image = "notification-off.png"
                                                       data-on-title = "{{translate('Want_to_Turn_ON_Notification_Status').'?'}}"
                                                       data-off-title = "{{translate('Want_to_Turn_OFF_Notification_Status').'?'}}"
                                                       data-on-message = "<p>{{translate('if_enabled_customers_will_receive_notifications_on_their_devices')}}</p>"
                                                       data-off-message = "<p>{{translate('if_disabled_customers_will_not_receive_notifications_on_their_devices')}}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="javascript:" class="btn btn-outline-success square-btn btn-sm resend-notification"
                                           data-id="{{ $notification->id }}">
                                            <i class="tio-refresh"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline--primary btn-sm edit square-btn"
                                               title="{{translate('edit')}}"
                                               href="{{route('admin.notification.update',[$notification['id']])}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm delete-data-without-form"
                                               title="{{translate('delete')}}"
                                               data-action="{{route('admin.notification.delete')}}"
                                               data-id="{{$notification['id']}}')">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <table class="mt-4">
                            <tfoot>
                            {!! $notifications->links() !!}
                            </tfoot>
                        </table>
                    </div>
                    @if(count($notifications) <= 0)
                        @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
    <span id="get-resend-notification-route-and-text" data-text="{{translate("resend_notification")}}" data-action="{{ route("admin.notification.resend-notification") }}"></span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/notification.js')}}"></script>
@endpush
