@extends('layouts.blank')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>6valley Software Installation</h2>
        <h6 class="fw-normal">All Done, Great Job. Your software is ready to run.</h6>
    </div>

    <div class="card mt-4">
        <div class="p-4 mb-md-3 mx-xl-4 px-md-5">
            <div class="p-4 rounded mb-4 text-center">
                <h5 class="fw-normal">Configure the following setting to run the system properly</h5>

                <ul class="list-group mar-no mar-top bord-no">
                    <li class="list-group-item">Business Setting</li>
                    <li class="list-group-item">MAIL Setting</li>
                    <li class="list-group-item">Payment Gateway Configuration</li>
                    <li class="list-group-item">SMS Gateway Configuration</li>
                    <li class="list-group-item">3rd Party APIs</li>
                </ul>
            </div>

            <div class="text-center">
                @php($adminLoginUrl = getWebConfig(name: 'admin_login_url'))
                <a href="{{ env('APP_URL') }}" target="_blank" class="btn btn-secondary px-sm-5">
                    Landing Page
                </a>
                <a href="{{ env('APP_URL') }}/login/{{$adminLoginUrl}}" target="_blank" class="btn btn-dark px-sm-5">
                    Admin Panel
                </a>
            </div>
        </div>
    </div>
@endsection
