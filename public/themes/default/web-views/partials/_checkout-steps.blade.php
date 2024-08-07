<div class="checkout-steps steps steps-light pt-2 pb-2">
    <a class="step-item {{$step >= 1?'active':''}} {{$step == 1?'current':''}}" href="{{route('shop-cart')}}">
        <div class="step-progress">
            <span class="step-count">
                <img src="{{theme_asset(path: 'public/assets/front-end/img/cart-icon.png')}}" class="mb-1" alt="">
            </span>
        </div>
        <div class="step-label">
            {{translate('cart')}}
        </div>
    </a>
    <a class="step-item {{ $step >= 2?'active':''}} {{$step == 2?'current':''}}" href="{{route('checkout-details')}}">
        <div class="step-progress">
            <span class="step-count"><i class="czi-package"></i></span>
        </div>
        @php($billingInputByCustomer = getWebConfig(name: 'billing_input_by_customer'))
        <div class="step-label">
            {{translate('shipping')}} {{$billingInputByCustomer == 1? translate('and_billing'):' '}}
        </div>
    </a>
    <a class="step-item {{$step >= 3?'active':''}} {{$step == 3?'current':''}}"
       href="{{$step >= 3 ? route('checkout-payment') : 'javascript:'}}">
        <div class="step-progress">
            <span class="step-count"><i class="czi-card"></i></span>
        </div>
        <div class="step-label">
            {{translate('payment')}}
        </div>
    </a>
</div>
