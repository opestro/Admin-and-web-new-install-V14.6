@extends('layouts.front-end.app')

@section('title', translate('terms_and_Condition'))

@section('content')
    <div class="container py-3 rtl text-align-direction">
        <h2 class="text-center py-4 fs-24 font-semi-bold text-capitalize">{{ translate('terms_and_Condition') }}</h2>

        @if(!empty($termsCondition))
            <div class="card __card">
                <div class="card-body text-justify">
                    {!! $termsCondition !!}
                </div>
            </div>
        @else
            <div class="d-flex flex-column justify-content-center align-items-center gap-3">
                <img src="{{ dynamicStorage(path: 'public/assets/front-end/img/empty-icons/empty-terms-and-conditions.svg') }}"
                     alt="{{ translate('brand') }}" class="img-fluid" width="100">
                <h5 class="text-muted fs-14 font-semi-bold text-center">{{ translate('there_is_no_terms_&_conditions') }}</h5>
            </div>
        @endif

    </div>
@endsection
