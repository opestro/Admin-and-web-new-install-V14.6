<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLongTitle">
        {{ translate('history_of_Order_No:') }} {{ $histories[0]->order_id?? '##' }}
        <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $histories->count() }}</span>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="timeline-wrapper">
        <div class="timeline-steps">
            @forelse($histories as $history)
            <div class="timeline-step {{ $history->status == 'returned' || $history->status == 'failed' || $history->status == 'canceled'? 'failed' : 'completed' }}">
                <div class="timeline-number">
                    <svg viewBox="0 0 512 512" width="100">
                        <path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path>
                    </svg>
                </div>
                <div class="timeline-info">
                    <p class="timeline-title">
                        @if($history->status == 'pending')
                            {{translate('pending')}}
                        @elseif($history->status == 'confirmed')
                            {{translate('confirmed')}}
                        @elseif($history->status == 'processing')
                            {{translate('packaging')}}
                        @elseif($history->status == 'out_for_delivery')
                            {{translate('out_for_Delivery')}}
                        @elseif($history->status == 'delivered')
                            {{translate('delivered')}}
                        @elseif($history->status == 'returned')
                            {{translate('returned')}}
                        @elseif($history->status == 'failed')
                            {{translate('failed_to_Deliver')}}
                        @elseif($history->status == 'canceled')
                            {{translate('canceled')}}
                        @endif
                    </p>
                    <p class="timeline-text">{{$history->created_at->format('d/m/y')}} {{$history->created_at->format('h:i A')}}</p>
                    <p class="timeline-text">{{ translate('service_Time') }} : {{$history->created_at->format('d/m/y')}} {{$history->created_at->format('h:i A')}}</p>
                </div>
            </div>
            @empty
            <div class="timeline-step failed">
                <div class="timeline-number">
                    <svg viewBox="0 0 512 512" width="100">
                        <path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path>
                    </svg>
                </div>
                <div class="timeline-info">
                    <p class="timeline-title">{{ translate('no_history_for_this_order') }}</p>
                </div>
            </div>
            @endforelse

        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('close') }}</button>
</div>
