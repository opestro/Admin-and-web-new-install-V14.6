@extends('layouts.front-end.app')

@section('title', translate('privacy_policy'))

@section('content')
    <div class="container py-5 rtl text-align-direction">
        <h2 class="text-center mb-3 headerTitle">{{translate('privacy_policy')}}</h2>
        <div class="card __card">
            <div class="card-body text-justify">
                {!! $privacyPolicy !!}
            </div>
        </div>
    </div>
@endsection
