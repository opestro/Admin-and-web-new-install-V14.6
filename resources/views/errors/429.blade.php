@extends('errors::minimal')

@section('title', translate('Too_Many_Requests'))

@section('message')
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-12">
                <h1 class="text-center display-1">{{ '429' }}</h1>
                <h2 class="text-center text-muted py-2">{{translate('Too_Many_Requests')}}</h2>
            </div>
        </div>
    </div>
@endsection
