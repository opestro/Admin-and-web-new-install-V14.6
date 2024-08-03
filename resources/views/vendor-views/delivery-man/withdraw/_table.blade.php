<div class="table-responsive">
    <table id="datatable"
           class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
        <thead class="thead-light thead-50 text-capitalize">
        <tr>
            <th>{{translate('SL')}}</th>
            <th>{{translate('amount')}}</th>
            <th>{{translate('Name') }}</th>
            <th>{{translate('request_time')}}</th>
            <th class="text-center">{{translate('status')}}</th>
            <th class="text-center">{{translate('action')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($withdrawRequests as $key=>$withdrawRequest)
            <tr>
                <td>{{$withdrawRequests->firstItem()+$key}}</td>
                <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $withdrawRequest['amount']), currencyCode: getCurrencyCode(type: 'default'))}}</td>

                <td>
                    @if (isset($withdrawRequest->deliveryMan))
                        <span class="title-color hover-c1">{{ $withdrawRequest->deliveryMan->f_name . ' ' . $withdrawRequest->deliveryMan->l_name }}</span>
                    @else
                        <span>{{translate('not_found')}}</span>
                    @endif
                </td>
                <td>{{ date_format( $withdrawRequest->created_at, 'd-M-Y, h:i:s A') }}</td>
                <td class="text-center">
                    @if($withdrawRequest->approved==0)
                        <label class="badge badge-soft-primary">{{translate('pending')}}</label>
                    @elseif($withdrawRequest->approved==1)
                        <label class="badge badge-soft-success">{{translate('approved')}}</label>
                    @else
                        <label class="badge badge-soft-danger">{{translate('denied')}}</label>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        @if (isset($withdrawRequest->deliveryMan))
                            <button
                               class="btn btn-outline-info btn-sm square-btn withdraw-info-show"
                               data-action="{{route('vendor.delivery-man.withdraw.details',[$withdrawRequest['id']])}}"
                               title="{{translate('view')}}">
                                <i class="tio-invisible"></i>
                            </button>
                        @else
                            <a class="btn btn-outline-info btn-sm square-btn disabled" href="#">
                                <i class="tio-invisible"></i>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if(count($withdrawRequests)==0)
    @include('layouts.back-end._empty-state',['text'=>'no_withdraw_request_found'],['image'=>'default'])
@endif
<div class="table-responsive mt-4">
    <div class="px-4 d-flex justify-content-center justify-content-md-end">
        {{$withdrawRequests->links()}}
    </div>
</div>
