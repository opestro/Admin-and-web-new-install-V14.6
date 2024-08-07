@extends('payment.layouts.master')

@section('content')
    <div>
        <h1 class="text-center">{{ "Please do not refresh this page..." }}</h1>
    </div>

    <form action="{!!route('razor-pay.payment',['payment_id'=>$data->id])!!}" id="form" method="POST">
    @csrf
        <script src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="{{ config()->get('razor_config.api_key') }}"
                data-amount="{{round($data->payment_amount, 2)*100}}"
                data-buttontext="Pay {{ round($data->payment_amount, 2) . ' ' . $data->currency_code }}"
                data-name={{ $business_name }}
                data-description="{{$data->payment_amount}}"
                data-image={{ $business_logo }}
                data-prefill.name="{{$payer->name ?? ''}}"
                data-prefill.email="{{$payer->email ?? ''}}"
                data-theme.color="#ff7529">
        </script>
        <button class="btn btn-block" id="pay-button" type="submit" style="display:none"></button>
    </form>

    <script type="text/javascript">
        "use strict";
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("pay-button").click();
        });
    </script>
@endsection
