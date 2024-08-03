@extends('payment.layouts.master')

@section('content')
    <div>
        <h1 class="text-center">{{ "Please do not refresh this page..." }}</h1>
    </div>

    <form method="post" action="<?php echo \Illuminate\Support\Facades\Config::get('paytm_config.PAYTM_TXN_URL') ?>" id="form">
        <table class="border border-1">
            <tbody>
            @foreach($paramList as $name => $value)
                <input type="hidden" name="{{$name}}" value="{{$value}}">
            @endforeach
            <input type="hidden" name="CHECKSUMHASH" value="{{$checkSum}}">
            </tbody>
        </table>
    </form>

    <script type="text/javascript">
        "use strict";
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("form").submit();
        });
    </script>
@endsection
