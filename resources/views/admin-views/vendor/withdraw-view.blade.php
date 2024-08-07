@extends('layouts.back-end.app')

@section('title', translate('Withdraw information View'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                {{translate('withdraw')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body text-start">
                        <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-2 mb-4">
                            <h3 class="text-capitalize">
                                {{translate('vendor_withdraw_information')}}
                            </h3>
                            <i class="tio-wallet-outlined fz-30"></i>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="flex-start flex-wrap">
                                    <div><h5 class="text-capitalize">{{translate('amount').':'.' '}}</h5></div>
                                    <div class="mx-1"><h5>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $withdrawRequest->amount), currencyCode: getCurrencyCode(type: 'default'))}}</h5></div>
                                </div>
                                <div class="flex-start flex-wrap">
                                    <div><h5>{{translate('request_time').':'.' '}} </h5></div>
                                    <div class="mx-1">{{$withdrawRequest->created_at}}</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="flex-start">
                                    <div class="title-color">{{translate('note').':'.' '}}</div>
                                    <div class="mx-1">{{$withdrawRequest->transaction_note}}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @if ($withdrawRequest->approved == 0)
                                    <button type="button" class="btn btn-success float-end" data-toggle="modal"
                                            data-target="#exampleModal">{{translate('proceed')}}
                                        <i class="tio-arrow-forward"></i>
                                    </button>
                                @else
                                    <div class="text-center float-end">
                                        <label class="badge badge-{{$withdrawRequest?->approved == 1 ? 'success' : 'danger'}} p-2 rounded-bottom">
                                            {{translate($withdrawRequest?->approved == 1 ? 'approved' : 'denied')}}
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($withdrawalMethod)
                @php($withdrawalMethod = is_array($withdrawalMethod) ? $withdrawalMethod : (is_object($withdrawalMethod) ? (array) $withdrawalMethod : json_decode($withdrawalMethod, true)))
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-3 mb-4">
                                <h3 class="h3 mb-0">{{ $withdrawalMethod['method_name'].' '.' '.translate('info')}} </h3>
                                <i class="tio tio-dollar-outlined"></i>
                            </div>
                            <div class="mt-2">
                                @foreach($withdrawalMethod as $key => $method)
                                    <div class="flex-start">
                                        <div><h5>{{ ucwords(str_replace('_',' ',$key)).' '.':' }}</h5></div>
                                        <div class="mx-1"><h5>{{ $method }}</h5></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-start">
                            <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-3 mb-4">
                                <h3 class="h3 mb-0">{{translate('my_bank_info')}} </h3>
                                <i class="tio tio-dollar-outlined"></i>
                            </div>

                            <div class="mt-2">
                                <div class="flex-start">
                                    <div><h4>{{translate('bank_name').' '.':'.' '}}</h4></div>
                                    <div class="mx-1"><h4>{{$withdrawRequest?->seller->bank_name ?? translate('no_data_found')}}</h4></div>
                                </div>
                                <div class="flex-start">
                                    <div><h6>{{translate('branch').' '.':'.' '}}</h6></div>
                                    <div class="mx-1"><h6>{{$withdrawRequest?->seller->branch ?? translate('no_data_found')}}</h6></div>
                                </div>
                                <div class="flex-start">
                                    <div><h6>{{translate('holder_name').' '.':'.' '}}</h6></div>
                                    <div class="mx-1"><h6>{{$withdrawRequest?->seller->holder_name ?? translate('no_data_found')}}</h6></div>
                                </div>
                                <div class="flex-start">
                                    <div><h6>{{translate('account_no').' '.':'.' '}} </h6></div>
                                    <div class="mx-1"><h6>{{$withdrawRequest?->seller->account_no ?? translate('no_data_found')}}</h6></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if($withdrawRequest->seller->shop)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-start">

                            <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-3 mb-4">
                                <h3 class="h3 mb-0">{{translate('shop_info')}} </h3>
                                <i class="tio tio-shop-outlined"></i>
                            </div>

                            <div class="flex-start">
                                <div><h5>{{translate('vendor').' '.':'.' '}} </h5></div>
                                <div class="mx-1"><h5>{{$withdrawRequest->seller->shop->name}}</h5></div>
                            </div>
                            <div class="flex-start">
                                <div><h5>{{translate('phone').' '.':'.' '}}</h5></div>
                                <div class="mx-1"><h5>{{$withdrawRequest->seller->shop->contact}}</h5></div>
                            </div>
                            <div class="flex-start">
                                <div><h5>{{translate('address').' '.':'.' '}}</h5></div>
                                <div class="mx-1"><h5>{{$withdrawRequest->seller->shop->address}}</h5></div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-start">
                        <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-3 mb-4">
                            <h3 class="h3 mb-0">{{translate('vendor_info')}} </h3>
                            <i class="tio tio-user-big-outlined"></i>
                        </div>
                        <div class="flex-start">
                            <div><h5>{{translate('name').' '.':'}}</h5></div>
                            <div class="mx-1"><h5>{{$withdrawRequest->seller->f_name.' '.$withdrawRequest->seller->l_name}}</h5></div>
                        </div>
                        <div class="flex-start">
                            <div><h5>{{translate('email').' '.':'}}</h5></div>
                            <div class="mx-1"><h5>{{$withdrawRequest->seller->email}}</h5></div>
                        </div>
                        <div class="flex-start">
                            <div><h5>{{translate('phone').' '.':'}}</h5></div>
                            <div class="mx-1"><h5>{{$withdrawRequest->seller->phone}}</h5></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{translate('withdraw_request_process')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('admin.vendors.withdraw_status',[$withdrawRequest['id']])}}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">{{translate('request')}}:</label>
                                    <select name="approved" class="custom-select" id="inputGroupSelect02">
                                        <option value="1">{{translate('approve')}}</option>
                                        <option value="2">{{translate('deny')}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">{{translate('note_about_transaction_or_request')}}:</label>
                                    <textarea class="form-control" name="note" id="message-text"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
