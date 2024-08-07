<div class="col-sm-12 mb-3">
    <div class="card">
        <div class="table-responsive datatable-custom">
            <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table text-left">
                <thead class="thead-light thead-50 text-capitalize table-nowrap">
                <tr>
                    <th>{{ translate('SL') }}</th>
                    <th>{{ translate('order_no') }}</th>
                    <th>{{ translate('earning') }}</th>
                    <th class="text-center text-capitalize">{{ translate('earning_status') }}</th>
                    <th class="text-center text-capitalize">{{ translate('payment_method') }}</th>
                    <th class="text-center">{{ translate('status') }}</th>
                </tr>
                </thead>

                <tbody id="set-rows">
                @foreach($orders as $key=>$order)
                    <tr>
                        <td>{{ $orders->firstItem()+$key }}</td>
                        <td>
                            <div class="media align-items-center gap-10 flex-wrap">
                                <a class="title-color" title="{{translate('order_details')}}"
                                   href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                    {{ $order->id }}
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <div class="media-body">{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->deliveryman_charge), currencyCode: getCurrencyCode()) }}</div>
                            </div>
                        </td>
                        <td class="text-center text-capitalize">
                            @if($order['order_status'] == 'delivered' && $order['payment_status']=='paid')
                                <span class="badge badge-success badge-success-2">
                                        {{translate('received')}}
                                </span>
                            @else
                                <span class="badge badge-soft-danger fz-12">
                                    {{translate('not_received')}}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="text-center">
                                {{translate($order['payment_method'])}}
                            </div>
                        </td>
                        <td class="text-center text-capitalize">
                            @if($order['order_status']=='pending')
                                <span class="badge badge-soft-info fz-12">
                                        {{translate($order['order_status'])}}
                                </span>

                            @elseif($order['order_status']=='out_for_delivery')
                                <span class="badge badge-soft-warning fz-12">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                            @elseif($order['order_status']=='processing')
                                <span class="badge badge-soft-secondary fz-12">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                            @elseif($order['order_status']=='confirmed')
                                <span class="badge badge-success badge-success-2">
                                                {{translate($order['order_status'])}}
                                            </span>
                            @elseif($order['order_status']=='failed')
                                <span class="badge badge-danger fz-12">
                                                {{translate('Failed_To_Deliver')}}
                                            </span>
                            @elseif($order['order_status']=='delivered')
                                <span class="badge badge-success badge-success-2">
                                                {{translate($order['order_status'])}}
                                            </span>
                            @else
                                <span class="badge badge-soft-danger fz-12">
                                                {{translate($order['order_status'])}}
                                            </span>
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-responsive mt-4">
            <div class="px-4 d-flex justify-content-lg-end">
                {{ $orders->links() }}
            </div>
        </div>
        @if(count($orders)==0)
            <div class="text-center p-4">
                <img class="mb-3 w-160"
                     src="{{dynamicAsset(path: 'public/assets/back-end/svg/illustrations/sorry.svg')}}"
                     alt="{{translate('image_description')}}">
                <p class="mb-0">{{translate('no_data_to_show')}}</p>
            </div>
        @endif
    </div>
</div>
