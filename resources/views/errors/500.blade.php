@extends('errors::minimal')

@section('title', __('Not Found'))

@section('message')
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-12">
                <div class="text-primary text-center">
                    <img src="{{dynamicAsset(path: "public/assets/front-end/png/500.png")}}" alt="" class="img-fluid">
                </div>

                <p class="text-center h4 lead py-2">
                    {{ translate('We_are_sorry_server_is_not_responding') }}
                    <br>
                    {{translate('Try_after_sometime')}}
                </p>
            </div>
        </div>
    </div>

@endsection
