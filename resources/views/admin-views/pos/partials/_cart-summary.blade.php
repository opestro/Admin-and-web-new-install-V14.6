@php($currentCustomerData = $summaryData['currentCustomerData'] ?? null)
@php($cartNames = $summaryData['cartNames'] ?? [])
@if ($summaryData['currentCustomer'] != 'Walking Customer')
    <div class="pos-home-delivery mb-4">
        <div class="d-flex justify-content-between gap-2 mb-3">
            <div class="d-flex gap-2">
                <i class="tio-user-big"></i>
                <h4 class="card-title">{{ translate('customer_Information') }} </h4>
            </div>
        </div>
        <div class="row gy-2">
            <div class="col-sm-12">
                <div class="pair-list">
                    <div>
                        <span class="key custom-flex-basis">{{ translate('name') }}</span>
                        <span>:</span>
                        <span class="value">{{ $currentCustomerData?->f_name.' '.$currentCustomerData?->l_name }}</span>
                    </div>
                    <div>
                        <span class="key custom-flex-basis">{{ translate('contact') }}</span>
                        <span>:</span>
                        <a href="tel:{{ $currentCustomerData?->phone }}"
                            class="value text-dark">{{ $currentCustomerData?->phone }}</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif
<div class="d-flex gap-2 flex-wrap mb-3">
    <div class="dropdown flex-grow-1" id="dropdown-order-select">
        <button class="form-control text-start dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false" id="cart_id_primary">
            {{ session('current_user') }}
        </button>
        <div class="dropdown-menu px-2">
            @foreach ($cartNames as $cartName)
                <button class="dropdown-item border rounded mb-1 action-cart-change" data-cart="{{ $cartName }}">{{ $cartName }}</button>
            @endforeach
            <button class="dropdown-item border rounded mt-2 action-view-all-hold-orders">
                <span class="d-flex align-items-center gap-2">
                    <i class="tio-pause"></i>
                    {{translate('view_all_hold_orders')}}
                    <span class="badge badge-danger rounded-circle">{{ $summaryData['totalHoldOrders'] }}</span>
                </span>
            </button>
        </div>
    </div>
    <a class="btn btn-secondary rounded text-nowrap action-clear-cart">
        {{ translate('clear_Cart')}}
    </a>
    <a class="btn btn--primary rounded text-nowrap action-new-order">
        {{ translate('new_Order')}}
    </a>
</div>

@include('admin-views.pos.partials._cart')
