@extends('layouts.back-end.app-seller')
@section('title',translate('deliveryman_List'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/deliveryman.png')}}" alt="">
                {{translate('deliveryman_List')}}
                <span class="badge badge-soft-dark radius-50 fz-14">{{ $deliveryMen->total() }}</span>
            </h2>
        </div>
        <div class="card">
            <div class="px-3 py-4">
                <div class="d-flex justify-content-between gap-10 flex-wrap align-items-center">
                    <div class="">
                        <form action="{{url()->current()}}" method="GET">
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                       placeholder="{{translate('search_by_name').','.translate('_contact_info')}}" aria-label="Search" value="{{ request('search') }}" required>
                                <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <div class="dropdown text-nowrap">
                            <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{translate('export')}}
                                <i class="tio-chevron-down"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a type="submit" class="dropdown-item d-flex align-items-center gap-2 " href="{{route('vendor.delivery-man.export',['search' => request('search')])}}">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                        {{translate('excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <a href="{{route('vendor.delivery-man.index')}}" class="btn btn--primary text-nowrap">
                            <i class="tio-add"></i>
                            {{translate('add_Delivery_Man')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table">
                    <thead class="thead-light thead-50 text-capitalize table-nowrap">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('name')}}</th>
                        <th>{{translate('contact_Info')}}</th>
                        <th>{{translate('total_Orders')}}</th>
                        <th>{{translate('rating')}}</th>
                        <th>{{translate('status')}}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                    </thead>
                    <tbody id="set-rows">
                    @foreach($deliveryMen as $key=>$deliveryMan)
                        <tr>
                            <td>{{$deliveryMen->firstitem()+$key}}</td>
                            <td>
                                <div class="media align-items-center gap-10">
                                    <img class="avatar avatar-lg rounded-circle" alt=""
                                            src="{{getValidImage('storage/app/public/delivery-man/'.$deliveryMan['image'],type:'backend-profile')}}">
                                    <div class="media-body">
                                        <a title="Earning Statement"
                                           class="title-color hover-c1"
                                           href="{{ route('vendor.delivery-man.wallet.index', ['id' => $deliveryMan['id']]) }}">
                                            {{$deliveryMan['f_name'].' '.$deliveryMan['l_name']}}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div><a class="title-color hover-c1" href="mailto:{{$deliveryMan['email']}}"><strong>{{$deliveryMan['email']}}</strong></a></div>
                                    <a class="title-color hover-c1" href="tel:{{$deliveryMan['country_code']}}{{$deliveryMan['phone']}}">{{$deliveryMan['country_code']. ' ' .$deliveryMan['phone']}}</a>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('vendor.orders.list', ['all', 'delivery_man_id' => $deliveryMan['id']]) }}" class="badge fz-14 badge-soft--primary">
                                    <span>{{ $deliveryMan->orders_count }}</span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('vendor.delivery-man.rating', ['id' => $deliveryMan['id']]) }}" class="badge fz-14 badge-soft-info">
                                    <span>{{ isset($deliveryMan->rating[0]->average) ? number_format($deliveryMan->rating[0]->average, 2, '.', ' ') : 0 }} <i class="tio-star"></i> </span>
                                </a>
                            </td>
                            <td>

                                <form action="{{route('vendor.delivery-man.update-status',[$deliveryMan['id']])}}" method="post" id="deliveryman_status{{$deliveryMan['id']}}-form" class="deliveryman_status_form">
                                    @csrf
                                    <label class="switcher mx-auto">
                                        <input type="checkbox" class="switcher_input toggle-switch-message" id="deliveryman_status{{$deliveryMan['id']}}" name="status" value="1" {{ $deliveryMan->is_active == 1 ? 'checked':'' }}
                                           data-modal-id = "toggle-status-modal"
                                           data-toggle-id = "deliveryman_status{{$deliveryMan['id']}}"
                                           data-on-image = "deliveryman-status-on.png"
                                           data-off-image = "deliveryman-status-off.png"
                                           data-on-title = "{{translate('Want_to_Turn_ON_Deliveryman_Status').'?'}}"
                                           data-off-title = "{{translate('Want_to_Turn_OFF_Deliveryman_Status').'?'}}"
                                           data-on-message = "<p>{{translate('if_enabled_this_deliveryman_can_log_in_to_the_system_and_deliver_products')}}</p>"
                                           data-off-message = "<p>{{translate('if_disabled_this_deliveryman_cannot_log_in_to_the_system_and_deliver_any_products')}}</p>"
                                        >
                                        <span class="switcher_control"></span>
                                    </label>
                                </form>

                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a  class="btn btn-outline--primary btn-sm square-btn" href="{{route('vendor.delivery-man.update',[$deliveryMan['id']])}}"
                                        title="{{translate('edit')}}">
                                        <i class="tio-edit"></i>
                                    </a>
                                    <a title="Earning Statement"
                                       class="btn btn-outline-info btn-sm square-btn"
                                       href="{{ route('vendor.delivery-man.wallet.index', ['id' => $deliveryMan['id']]) }}">
                                        <i class="tio-money"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm square-btn delete-data"
                                       data-id="delivery-man-{{$deliveryMan['id']}}"
                                        title="{{translate('delete')}}"
                                        href="javascript:"
                                    >
                                        <i class="tio-delete"></i>
                                    </a>
                                    <form action="{{route('vendor.delivery-man.delete',[$deliveryMan['id']])}}"
                                            method="post" id="delivery-man-{{$deliveryMan['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($deliveryMen)==0)
                @include('layouts.back-end._empty-state',['text'=>'no_delivery_man_found'],['image'=>'default'])
            @endif
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {!! $deliveryMen->links() !!}
                </div>
            </div>
        </div>
    </div>
    <span id="deliveryman-status-message" data-text="{{translate("status_updated_successfully")}}"></span>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/deliveryman.js')}}"></script>
@endpush
