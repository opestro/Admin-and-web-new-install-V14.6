@extends('layouts.back-end.app')

@section('title', translate('notification_setup'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('notification_setup')}}
            </h2>
        </div>
        @include('admin-views.notification-setup.partials.inline-menu')


    </div>
@endsection


@push('script')
@endpush