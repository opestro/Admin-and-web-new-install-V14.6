@extends('layouts.back-end.app')

@section('title',translate('deliveryman_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/deliveryman.png')}}" width="20" alt="">
                {{translate('delivery_man')}} <span class="badge badge-soft-dark radius-50 fz-12">{{ $deliveryMens->total() }}</span>
            </h2>
        </div>

        <div class="row">
            <div class="col-sm-12 mb-3">
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
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                                placeholder="{{translate('search_by_name').','.translate('_contact_info')}}" aria-label="Search" value="{{ request('searchValue') }}" required>
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
                                            <a type="submit" class="dropdown-item d-flex align-items-center gap-2 " href="{{route('admin.delivery-man.export',['searchValue' => request('searchValue')])}}">
                                                <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                                {{translate('excel')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{route('admin.delivery-man.add')}}" class="btn btn--primary text-nowrap">
                                    <i class="tio-add"></i>
                                    {{translate('add_Delivery_Man')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table {{ Session::get('direction') === 'rtl' ? 'text-right' : 'text-left' }}">
                            <thead class="thead-light thead-50 text-capitalize table-nowrap">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('name')}}</th>
                                <th>{{translate('contact info')}}</th>
                                <th>{{translate('total_Orders')}}</th>
                                <th>{{translate('rating')}}</th>
                                <th class="text-center">{{translate('status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($deliveryMens as $key => $deliveryMen)
                                <tr>
                                    <td>{{$deliveryMens->firstitem()+$key}}</td>
                                    <td>
                                        <div class="media align-items-center gap-10">
                                            <img class="rounded-circle avatar avatar-lg" alt=""
                                                 src="{{getValidImage(path: 'storage/app/public/delivery-man/'.$deliveryMen['image'],type:'backend-profile')}}">
                                            <div class="media-body">
                                                <a title="Earning Statement"
                                                   class="title-color hover-c1"
                                                   href="{{ route('admin.delivery-man.earning-statement-overview', ['id' => $deliveryMen['id']]) }}">
                                                    {{$deliveryMen['f_name'].' '.$deliveryMen['l_name']}}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <div><a class="title-color hover-c1" href="mailto:{{$deliveryMen['email']}}"><strong>{{$deliveryMen['email']}}</strong></a></div>
                                            <a class="title-color hover-c1" href="tel:{{$deliveryMen['country_code']}}{{$deliveryMen['phone']}}">{{ $deliveryMen['country_code'].' '. $deliveryMen['phone']}}</a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.list', ['all', 'delivery_man_id' => $deliveryMen['id']]) }}" class="badge fz-14 badge-soft--primary">
                                            <span>{{ $deliveryMen->orders_count }}</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.delivery-man.rating', ['id' => $deliveryMen['id']]) }}" class="badge fz-14 badge-soft-info">
                                            <span>{{ isset($deliveryMen->rating[0]->average) ? number_format($deliveryMen->rating[0]->average, 2, '.', ' ') : 0 }} <i class="tio-star"></i> </span>
                                        </a>
                                    </td>
                                    <td>

                                        <form action="{{route('admin.delivery-man.status-update')}}" method="post" id="deliveryman_status{{$deliveryMen['id']}}-form" class="deliveryman_status_form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$deliveryMen['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message" id="deliveryman_status{{$deliveryMen['id']}}" name="status" value="1" {{ $deliveryMen->is_active == 1 ? 'checked':'' }}
                                                   data-modal-id = "toggle-status-modal"
                                                   data-toggle-id = "deliveryman_status{{$deliveryMen['id']}}"
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
                                        <div class="d-flex justify-content-center align-items-center gap-10">
                                            <a  class="btn btn-outline--primary btn-sm edit"
                                                title="{{translate('edit')}}"
                                                href="{{route('admin.delivery-man.edit',[$deliveryMen['id']])}}">
                                                <i class="tio-edit"></i></a>
                                            <a title="Earning Statement"
                                               class="btn btn-outline-info btn-sm square-btn"
                                               href="{{ route('admin.delivery-man.earning-statement-overview', ['id' => $deliveryMen['id']]) }}">
                                                <i class="tio-money"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm delete delete-data" href="javascript:"
                                                data-id="delivery-man-{{$deliveryMen['id']}}"
                                                title="{{ translate('delete')}}">
                                                <i class="tio-delete"></i>
                                            </a>
                                            <form action="{{route('admin.delivery-man.delete',[$deliveryMen['id']])}}"
                                                    method="post" id="delivery-man-{{$deliveryMen['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {!! $deliveryMens->links() !!}
                        </div>
                    </div>
                    @if(count($deliveryMens)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_delivery_man_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/deliveryman.js')}}"></script>
@endpush
