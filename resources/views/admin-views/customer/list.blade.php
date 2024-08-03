@php use Illuminate\Support\Str; @endphp
@extends('layouts.back-end.app')

@section('title', translate('customer_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/customer.png')}}" alt="">
                {{translate('customer_list')}}
                <span class="badge badge-soft-dark radius-50">{{$customers->total()}}</span>
            </h2>
        </div>
        <div class="card">
            <div class="px-3 py-4">
                <div class="row gy-2 align-items-center">
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                       placeholder="{{translate('search_by_Name_or_Email_or_Phone')}}"
                                       aria-label="Search orders" value="{{ request('searchValue') }}">
                                <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                        <div class="d-flex justify-content-sm-end">
                            <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{translate('export')}}
                                <i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a class="dropdown-item"
                                       href="{{route('admin.customer.export',['searchValue'=>request('searchValue')])}}">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                        {{translate('excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive datatable-custom">
                <table
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('customer_name')}}</th>
                        <th>{{translate('contact_info')}}</th>
                        <th>{{translate('total_Order')}} </th>
                        <th class="text-center">{{translate('block')}} / {{translate('unblock')}}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $key=>$customer)
                        <tr>
                            <td>
                                {{$customers->firstItem()+$key}}
                            </td>
                            <td>
                                <a href="{{route('admin.customer.view',[$customer['id']])}}"
                                   class="title-color hover-c1 d-flex align-items-center gap-10">
                                    <img src="{{getValidImage(path: 'storage/app/public/profile/'.$customer->image,type:'backend-profile')}}"
                                         class="avatar rounded-circle " alt="" width="40">
                                    {{Str::limit($customer['f_name']." ".$customer['l_name'],20)}}
                                </a>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <strong><a class="title-color hover-c1"
                                               href="mailto:{{$customer->email}}">{{$customer->email}}</a></strong>

                                </div>
                                <a class="title-color hover-c1" href="tel:{{$customer->phone}}">{{$customer->phone}}</a>

                            </td>
                            <td>
                                <label class="btn text-info bg-soft-info font-weight-bold px-3 py-1 mb-0 fz-12">
                                    {{$customer->orders_count}}
                                </label>
                            </td>
                            <td>
                                @if($customer['email'] == 'walking@customer.com')
                                    <div class="text-center">
                                        <div class="badge badge-soft-version">{{ translate('default') }}</div>
                                    </div>
                                @else
                                    <form action="{{route('admin.customer.status-update')}}" method="post"
                                          id="customer-status{{$customer['id']}}-form" class="customer-status-form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$customer['id']}}">
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input toggle-switch-message"
                                                   id="customer-status{{$customer['id']}}" name="status" value="1"
                                                   {{ $customer['is_active'] == 1 ? 'checked':'' }}
                                                   data-modal-id = "toggle-status-modal"
                                                   data-toggle-id = "customer-status{{$customer['id']}}"
                                                   data-on-image = "customer-block-on.png"
                                                   data-off-image = "customer-block-off.png"
                                                   data-on-title = "{{translate('want_to_unblock').' '.$customer['f_name'].' '.$customer['l_name'].'?'}}"
                                                   data-off-title = "{{translate('want_to_block').' '.$customer['f_name'].' '.$customer['l_name'].'?'}}"
                                                   data-on-message = "<p>{{translate('if_enabled_this_customer_will_be_unblocked_and_can_log_in_to_this_system_again')}}</p>"
                                                   data-off-message = "<p>{{translate('if_disabled_this_customer_will_be_blocked_and_cannot_log_in_to_this_system')}}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a title="{{translate('view')}}"
                                       class="btn btn-outline-info btn-sm square-btn"
                                       href="{{route('admin.customer.view',[$customer['id']])}}">
                                        <i class="tio-invisible"></i>
                                    </a>
                                    @if($customer['id'] != '0')
                                        <a title="{{translate('delete')}}"
                                           class="btn btn-outline-danger btn-sm delete square-btn delete-data" href="javascript:"
                                           data-id="customer-{{$customer['id']}}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    @endif
                                </div>
                                <form action="{{route('admin.customer.delete',[$customer['id']])}}"
                                      method="post" id="customer-{{$customer['id']}}">
                                    @csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {!! $customers->links() !!}
                </div>
            </div>
            @if(count($customers)==0)
                @include('layouts.back-end._empty-state',['text'=>'no_customer_found'],['image'=>'default'])
            @endif
        </div>
    </div>
@endsection
