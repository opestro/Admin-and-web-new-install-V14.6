@extends('payment.layouts.master')

@section('content')

    @if(isset($config))
        <div>
            <h1 class="text-center">{{ "Please do not refresh this page..." }}</h1>
        </div>

        <div class="col-md-6 mb-4 cursor-pointer">
            <div class="card">
                <div class="card-body" style="height: 70px">
                    @php($secretkey = $config->secret_key)
                    @php($data = new \stdClass())
                    @php($data->merchantId = $config->merchant_id)
                    @php($data->amount = $payment_data->payment_amount)
                    @php($data->name = $payer->name??'')
                    @php($data->email = $payer->email ??'')
                    @php($data->phone = $payer->phone ??'')
                    @php($data->hashed_string = md5($secretkey . urldecode($data->amount) ))

                    <form id="form" method="post"
                          action="https://{{env('APP_MODE')=='live'?'app.senangpay.my':'sandbox.senangpay.my'}}/payment/{{$config->merchant_id}}">
                        <input type="hidden" name="amount" value="{{ $data->amount }}">
                        <input type="hidden" name="name" value="{{ $data->name }}">
                        <input type="hidden" name="email" value="{{ $data->email }}">
                        <input type="hidden" name="phone" value="{{ $data->phone }}">
                        <input type="hidden" name="hash" value="{{ $data->hashed_string }}">
                    </form>

                </div>
            </div>
        </div>
    @endif

    <script type="text/javascript">
        "use strict";
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("form").submit();
        });
    </script>
@endsection
