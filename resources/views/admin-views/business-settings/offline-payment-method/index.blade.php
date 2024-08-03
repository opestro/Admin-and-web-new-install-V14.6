@extends('layouts.back-end.app')

@section('title', translate('offline_Payment_Method'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('3rd_party')}}
            </h2>
        </div>
        @include('admin-views.business-settings.third-party-payment-method-menu')
        <nav>
            <div class="nav nav-tabs mb-3 border-0" role="tablist">
              <a class="nav-link {{ !request()->has('status') ? 'active':'' }}" href="{{route('admin.business-settings.offline-payment-method.index')}}">{{ translate('all') }}</a>
              <a class="nav-link {{ request('status') == 'active' ? 'active':'' }}" href="{{route('admin.business-settings.offline-payment-method.index')}}?status=active">{{ translate('active') }}</a>
              <a class="nav-link {{ request('status') == 'inactive' ? 'active':'' }}" href="{{route('admin.business-settings.offline-payment-method.index')}}?status=inactive">{{ translate('inactive') }}</a>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row g-2 flex-grow-1">
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="{{ route('admin.business-settings.offline-payment-method.index') }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control" placeholder="{{ translate('search_by_payment_method_name') }}" value="{{ request('searchValue') }}" required="">
                                        <button type="submit" class="btn btn--primary input-group-text">{{ translate('search') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                                <a href="{{route('admin.business-settings.offline-payment-method.add')}}" class="btn btn--primary"><i class="tio-add"></i> {{ translate('add_New_Method') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{ translate('SL') }}</th>
                                        <th>{{ translate('payment_Method_Name') }}</th>
                                        <th>{{ translate('payment_Info') }}</th>
                                        <th>{{ translate('required_Info_From_Customer') }}</th>
                                        <th class="text-center">{{ translate('status') }}</th>
                                        <th class="text-center">{{ translate('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($methods as $key=>$method)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{ $method->method_name }}</td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach ($method->method_fields as $item)
                                                        <div>{{ ucwords(str_replace('_',' ',$item['input_name'])) }} : {{ $item['input_data'] }}</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach ($method->method_informations as $item)
                                                    <div>
                                                        {{ ucwords(str_replace('_',' ',$item['customer_input'])) }}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <form action="{{route('admin.business-settings.offline-payment-method.update-status')}}" method="post" id="method-status{{$method['id']}}-form" class="method-status-form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$method['id']}}">
                                                    <label class="switcher mx-auto">
                                                        <input type="checkbox" class="switcher_input toggle-switch-message" id="method-status{{$method['id']}}" name="status" {{ $method->status == 1 ? 'checked':'' }}
                                                               data-modal-id = "toggle-status-modal"
                                                               data-toggle-id = "method-status{{$method['id']}}"
                                                               data-on-image = "offline-payment-on.png"
                                                               data-off-image = "offline-payment-off.png"
                                                               data-on-title = "{{translate('want_to_Turn_ON_Offline_Payment_Methods').'?'}}"
                                                               data-off-title = "{{translate('want_to_Turn_OFF_Offline_Payment_Methods').'?'}}"
                                                               data-on-message = "<p>{{translate('if_enabled_customers_can_pay_through_different_payment_methods_outside_your_system')}}</p>"
                                                               data-off-message = "<p>{{translate('if_disabled_customers_can_only_pay_through_the_system_supported_payment_methods')}}</p>">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </form>

                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-outline-info btn-sm square-btn" title="Edit" href="{{route('admin.business-settings.offline-payment-method.update', ['id'=>$method->id])}}">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <button class="btn btn-outline-danger btn-sm delete square-btn delete-data" title="{{translate('delete')}}" data-id="delete-method-name-{{ $method->id }}">
                                                        <i class="tio-delete"></i>
                                                    </button>

                                                    <form action="{{route('admin.business-settings.offline-payment-method.delete')}}" method="post" id="delete-method-name-{{ $method->id }}">
                                                        @csrf
                                                        <input type="hidden" value="{{ $method->id }}" name="id" required>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($methods->count() > 0)
                                <div class="p-3 d-flex justify-content-end">
                                    @php
                                        if (request()->has('status')) {
                                            $paginationLinks = $methods->links();
                                            $modifiedLinks = preg_replace('/href="([^"]*)"/', 'href="$1&status='.request('status').'"', $paginationLinks);
                                        } else {
                                            $modifiedLinks = $methods->links();
                                        }
                                    @endphp
                                    {!! $modifiedLinks !!}
                                </div>
                            @endif
                        </div>
                        @if ($methods->count() <= 0)
                            @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{asset('public/assets/back-end/js/admin/business-setting/offline-payment.js')}}"></script>
@endpush
