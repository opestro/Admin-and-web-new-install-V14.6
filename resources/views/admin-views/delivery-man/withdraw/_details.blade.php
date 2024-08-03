<div class="withdraw-info-sidebar-overlay"></div>
<div class="withdraw-info-sidebar d-flex justify-content-between flex-column">
    <div class="withdraw-details">
        <div class="d-flex pb-3">
            <span class="circle bg-light withdraw-info-hide cursor-pointer">
                <i class="tio-clear"></i>
            </span>
        </div>
        <div class="d-flex flex-column align-items-center gap-1 mb-3">
            <h3 class="mb-3 text-capitalize">{{translate('withdraw_information')}}</h3>
            <div class="d-flex gap-2 align-items-center mb-1 flex-wrap">
                <span class="text-capitalize">{{translate('withdraw_Amount').' : '}}</span>
                <span class="font-semibold">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $details['amount']), currencyCode: getCurrencyCode())}}</span>
                <label class="badge {{$details['approved'] == 1 ? 'badge-success-2' : ($details['approved'] == 0 ? 'badge--primary-2':'badge--danger-2')}} mb-0">{{translate($details['approved'] == 1 ? 'approved' : ($details['approved'] == 0 ? 'pending':'denied'))}}</label>
            </div>
            <div class="d-flex gap-2 align-items-center fs-12">
                <span class="text-capitalize">{{translate('request_time').' : '}}</span>
                <span>{{ date_format( $details['created_at'], 'd-M-Y, h:i:s A') }}</span>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 font-medium text-capitalize font-weight-bold">{{translate('bank_info')}}</h6>
            </div>
            <div class="card-body">
                <div class="key-val-list d-flex flex-column gap-2 min-width--60px">
                    <div class="key-val-list-item d-flex gap-3">
                        <span class="text-capitalize">{{translate('bank_name')}}</span>:
                        <span>{{$details?->deliveryMan?->bank_name ?? translate('no_data_found')}}</span>
                    </div>
                    <div class="key-val-list-item d-flex gap-3">
                        <span>{{translate('branch')}}</span>:
                        <span>{{$details?->deliveryMan?->branch ?? translate('no_data_found') }}</span>
                    </div>
                    <div class="key-val-list-item d-flex gap-3">
                        <span class="text-capitalize">{{translate('holder_name')}} </span>:
                        <span>{{$details?->deliveryMan?->holder_name ?? translate('no_data_found') }}</span>
                    </div>
                    <div class="key-val-list-item d-flex gap-3">
                        <span class="text-capitalize">{{translate('account_no')}}</span>:
                        <span>{{$details?->deliveryMan?->account_no ??  translate('no_data_found') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 font-medium text-capitalize font-weight-bold">{{translate('deliveryman_info')}}</h6>
            </div>
            <div class="card-body">
                <div class="key-val-list d-flex flex-column gap-2 min-width--60px">
                    <div class="key-val-list-item d-flex gap-3">
                        <span>{{translate('name')}}</span>:
                        <span>{{$details?->deliveryMan?->f_name.' '.$details?->deliveryMan?->l_name}}</span>
                    </div>
                    <div class="key-val-list-item d-flex gap-3">
                        <span>{{translate('email')}}</span>:
                        <a href="mailto:{{$details?->deliveryMan?->email}}" class="text-dark">{{$details?->deliveryMan?->email}}</a>
                    </div>
                    <div class="key-val-list-item d-flex gap-3">
                        <span>{{translate('phone')}}</span>:
                        <a href="tel:{{$details?->deliveryMan?->phone}}" class="text-dark">{{$details?->deliveryMan?->phone}}</a>
                    </div>
                </div>
            </div>
        </div>
        @if($details['transaction_note'])
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0 font-medium text-capitalize font-weight-bold">{{translate(($details['approved'] == 0 ? 'pending':($details['approved'] ==1 ? 'approved' : 'denied' )).'_'.'Note')}}</h6>
                </div>
                <div class="card-body">
                    <div class="key-val-list d-flex flex-column gap-2 min-width--60px">
                        <div class="key-val-list-item d-flex gap-3">
                            <span>{{$details['transaction_note']}}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="d-none note-section">
        <div class="d-flex pb-3">
            <span class="circle bg-light withdraw-info-hide cursor-pointer">
                <i class="tio-clear"></i>
            </span>
        </div>
        <form action="{{route('admin.delivery-man.withdraw-update-status',[$details['id']])}}" method="POST" id="approval-note-form">
            @csrf
            <input name="approved" value="1" hidden="">
            <div class="mt-5 d-none note-area" id="approval-note" >
                <h5 class="font-semibold text-center mb-3">{{translate('approval_note')}} </h5>
                <textarea name="note" class="form-control" rows="6" placeholder="{{translate('type_a_note_about_request_approval').'.'}}"></textarea>
            </div>
        </form>
        <form action="{{route('admin.delivery-man.withdraw-update-status',[$details['id']])}}" method="POST" id="denial-note-form">
            @csrf
            <input name="approved" value="2" hidden="">
            <div class="mt-5 d-none note-area" id="denial-note">
                <h5 class="font-semibold text-center mb-3">{{translate('denial_note')}}</h5>
                <textarea name="note" id="" class="form-control" rows="6" placeholder="{{translate('type_a_note_about_request_denial').'.'}}"></textarea>
            </div>
        </form>
    </div>
    <div class="mt-4 d-flex justify-content-center gap-3">
        <div class="withdraw-details">
            @if ($details['approved'] == 0)
                <button type="button" class="btn btn-soft-danger min-w-100px open-note mx-2" data-id="denial-note" data-message="{{translate('want_to_deny_this_withdraw_request').'?'}}">{{translate('deny')}}</button>
                <button type="button" class="btn btn-success min-w-100px open-note" data-id="approval-note" data-message="{{translate('want_to_approve_this_withdraw_request').'?'}}">{{translate('approve')}}</button>
            @endif
        </div>
        <div class="d-none note-section">
            <button type="button" class="btn btn-soft-secondary min-w-100px back-to-details mx-2"> {{translate('back')}}</button>
            <button type="button" class="btn btn-primary min-w-100px form-submit" data-form-id="" data-message="">{{translate('complete')}}</button>
        </div>
    </div>
</div>
