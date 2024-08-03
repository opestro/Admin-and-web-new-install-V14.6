@extends('errors::minimal')

@section('title', translate('Page_Expired'))

@section('message')
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-12">
                <h1 class="text-center display-1">{{ '419' }}</h1>
                <h2 class="text-center text-muted py-2">{{translate('Page_Expired')}}</h2>
            </div>
        </div>
    </div>
@endsection
