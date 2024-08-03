@extends('layouts.back-end.app-seller')

@section('title',translate('cash_Withdraw'))

@section('content')

    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/earning_statictics.png')}}" alt="">
                {{translate('collect_Cash')}}
            </h2>
        </div>
        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <form action="{{ route('vendor.delivery-man.wallet.cash-collect', ['id' => $deliveryMan->id]) }}" method="post">
                        @csrf
                    <div class="card-body">
                        <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                            <i class="tio-money"></i>
                            {{translate('cash_Withdraw')}}
                        </h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="d-flex flex-wrap gap-2 mt-3 title-color" id="chosen_price_div">
                                    <div class="product-description-label">{{translate('total_Cash_In_Hand')}}: </div>
                                    <div class="product-price">
                                        <strong>{{ $deliveryMan->wallet ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $deliveryMan->wallet->cash_in_hand), currencyCode: getCurrencyCode()) : 0  }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="number" name="amount" class="form-control" placeholder="{{translate('enter_withdraw_amount')}}"
                                           required>
                                    @if($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <span class="text-danger">{{ $error }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 justify-content-end">
                            <button type="submit" class="btn btn--primary px-4">{{translate('receive')}}</button>
                        </div>

                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 mb-3">
                <div class="card">
                    <div class="table-responsive datatable-custom">
                        <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table {{ Session::get('direction') === 'rtl' ? 'text-right' : 'text-left' }}">
                            <thead class="thead-light thead-50 text-capitalize table-nowrap">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('name')}}</th>
                                <th>{{translate('amount')}}</th>
                                <th>{{translate('transaction_date')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $deliveryMan->f_name. ' ' .$deliveryMan->l_name  }}
                                    </td>
                                    <td>
                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['credit']), currencyCode: getCurrencyCode()) }}
                                    </td>
                                    <td>
                                        {{ date_format( $transaction['created_at'], 'd-M-Y, h:i:s A') }}
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {!! $transactions->links() !!}
                        </div>
                    </div>
                    @if(count($transactions)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
