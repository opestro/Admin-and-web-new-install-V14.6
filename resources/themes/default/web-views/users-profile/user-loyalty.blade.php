@extends('layouts.front-end.app')

@section('title', translate('my_loyalty_point'))

@section('content')

    <div class="container py-2 py-md-4 p-0 p-md-2 user-profile-container px-5px">
        <div class="row">

            @include('web-views.partials._profile-aside')

            <section class="col-lg-9 __customer-profile px-0">

                <div class="card loyalty-card mb-10px">
                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                            <h5 class="font-bold m-0 fs-16 text-capitalize">{{translate('loyalty_point')}}</h5>

                            <button class="profile-aside-btn btn btn--primary px-2 rounded px-2 py-1 d-lg-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M7 9.81219C7 9.41419 6.842 9.03269 6.5605 8.75169C6.2795 8.47019 5.898 8.31219 5.5 8.31219C4.507 8.31219 2.993 8.31219 2 8.31219C1.602 8.31219 1.2205 8.47019 0.939499 8.75169C0.657999 9.03269 0.5 9.41419 0.5 9.81219V13.3122C0.5 13.7102 0.657999 14.0917 0.939499 14.3727C1.2205 14.6542 1.602 14.8122 2 14.8122H5.5C5.898 14.8122 6.2795 14.6542 6.5605 14.3727C6.842 14.0917 7 13.7102 7 13.3122V9.81219ZM14.5 9.81219C14.5 9.41419 14.342 9.03269 14.0605 8.75169C13.7795 8.47019 13.398 8.31219 13 8.31219C12.007 8.31219 10.493 8.31219 9.5 8.31219C9.102 8.31219 8.7205 8.47019 8.4395 8.75169C8.158 9.03269 8 9.41419 8 9.81219V13.3122C8 13.7102 8.158 14.0917 8.4395 14.3727C8.7205 14.6542 9.102 14.8122 9.5 14.8122H13C13.398 14.8122 13.7795 14.6542 14.0605 14.3727C14.342 14.0917 14.5 13.7102 14.5 13.3122V9.81219ZM12.3105 7.20869L14.3965 5.12269C14.982 4.53719 14.982 3.58719 14.3965 3.00169L12.3105 0.915687C11.725 0.330188 10.775 0.330188 10.1895 0.915687L8.1035 3.00169C7.518 3.58719 7.518 4.53719 8.1035 5.12269L10.1895 7.20869C10.775 7.79419 11.725 7.79419 12.3105 7.20869ZM7 2.31219C7 1.91419 6.842 1.53269 6.5605 1.25169C6.2795 0.970186 5.898 0.812187 5.5 0.812187C4.507 0.812187 2.993 0.812187 2 0.812187C1.602 0.812187 1.2205 0.970186 0.939499 1.25169C0.657999 1.53269 0.5 1.91419 0.5 2.31219V5.81219C0.5 6.21019 0.657999 6.59169 0.939499 6.87269C1.2205 7.15419 1.602 7.31219 2 7.31219H5.5C5.898 7.31219 6.2795 7.15419 6.5605 6.87269C6.842 6.59169 7 6.21019 7 5.81219V2.31219Z"
                                          fill="white"/>
                                </svg>
                            </button>
                        </div>

                        <div class="d-flex justify-content-end mb-2 mb-sm-0 d-sm-none">
                            <div class="position-relative">
                                <button type="button"
                                        class="border-0 bg-transparent p-0 how-to-use-info-button rounded-circle lh-1">
                                    <img src="{{theme_asset(path: 'public/assets/front-end/img/icons/icon-i.png')}}" alt=""
                                         width="18">
                                </button>
                                <div class="how-to-use-hover-ele">
                                    <h6 class='subtitle text-capitalize mb-2 fs-14 font-bold'>{{translate('how_to_use')}}</h6>
                                    <ul class='pl-4 fs-12'>
                                        <li>
                                            {{translate('convert_your_loyalty_point_to_wallet_money')}}.
                                        </li>
                                        <li>
                                            {{translate('minimum')}} {{ $loyaltyPointMinimumPoint }} {{translate('points_required_to_convert_into_currency')}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 g-xl-4">
                            <div class="col-md-6">
                                <div class="card btn--primary h-100 position-relative">
                                    <div class="card-body d-flex align-items-center z-2">
                                        <div
                                            class="d-flex flex-wrap gap-8 justify-content-between align-items-center w-100">
                                            <div class="text-white">
                                                <p class="mb-2 fs-13 font-weight-medium">{{translate('Total Loyalty Point')}}</p>
                                                <h3 class="text-white d-flex align-items-center m-0 font-bold">
                                                    {{ $totalLoyaltyPoint ?? 0 }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <img class="wallet-card-bg z-1"
                                         src="{{ theme_asset(path: 'public/assets/front-end/img/reward-card.png') }}" alt="">
                                </div>
                            </div>
                            <div class="col-md-6 d-none d-sm-block">
                                <div class="my-wallet-card-content h-100 text-break-word">
                                    <h6 class="subtitle text-capitalize">{{translate('how_to_use')}}</h6>
                                    <ul class="fs-13">
                                        <li>
                                            {{translate('convert_your_loyalty_point_to_wallet_money')}}.
                                        </li>
                                        <li>
                                            {{translate('minimum')}} {{ $loyaltyPointMinimumPoint }} {{translate('points_required_to_convert_into_currency')}}
                                        </li>
                                    </ul>
                                    <div class="mt-3 text-center d-none d-sm-block">
                                        @if ($walletStatus == 1 && $loyaltyPointStatus == 1)

                                            <button type="button" data-insufficient-point="{{ translate('insufficient_loyalty_point') }}"
                                                    class="btn btn-sm rounded btn--primary {{ $loyaltyPointMinimumPoint > $totalLoyaltyPoint ? 'alert-insufficient-loyalty-point':'' }}"
                                                @if($loyaltyPointMinimumPoint <= $totalLoyaltyPoint) data-toggle="modal" data-target="#convertToCurrency" @endif

                                            >
                                                <span>
                                                    <svg width="16" viewBox="0 0 16 16" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_1029_2558)">
                                                        <path
                                                            d="M0 5.00053C0.000244156 6.15774 0.401714 7.27906 1.13601 8.17345C1.8703 9.06784 2.89199 9.67997 4.027 9.90553C4.09164 9.18906 4.27551 8.48842 4.571 7.83253C3.695 7.53653 3.132 6.86453 3 5.91053H2.5V5.48453H2.966V5.05053C2.966 5.00453 2.966 4.95753 2.97 4.91553H2.5V4.48853H3.011C3.236 3.24053 4.213 2.50053 5.681 2.50053C5.997 2.50053 6.271 2.53153 6.5 2.58553V3.31853C6.23254 3.25902 5.95896 3.2315 5.685 3.23653C4.766 3.23653 4.147 3.70253 3.951 4.48853H5.868V4.91553H3.888C3.885 4.96153 3.885 5.01253 3.885 5.06253V5.48453H5.868V5.91153H3.93C4.048 6.51353 4.398 6.94153 4.935 7.14053C5.46154 6.26849 6.18567 5.53242 7.04897 4.99168C7.91228 4.45094 8.89059 4.12068 9.905 4.02753C9.65975 2.81304 8.97248 1.7328 7.97633 0.996044C6.98017 0.259291 5.74603 -0.0815315 4.51296 0.0395967C3.27989 0.160725 2.13566 0.735182 1.30191 1.65169C0.468163 2.5682 0.00423697 3.76153 0 5.00053ZM16 10.5005C16 11.9592 15.4205 13.3582 14.3891 14.3896C13.3576 15.4211 11.9587 16.0005 10.5 16.0005C9.04131 16.0005 7.64236 15.4211 6.61091 14.3896C5.57946 13.3582 5 11.9592 5 10.5005C5 9.04184 5.57946 7.64289 6.61091 6.61144C7.64236 5.57999 9.04131 5.00053 10.5 5.00053C11.9587 5.00053 13.3576 5.57999 14.3891 6.61144C15.4205 7.64289 16 9.04184 16 10.5005ZM8.25 11.8225C8.319 12.6575 8.996 13.3075 10.214 13.3845V14.0005H10.754V13.3805C12.013 13.2945 12.75 12.6405 12.75 11.6905C12.75 10.8255 12.187 10.3805 11.18 10.1505L10.754 10.0505V8.37453C11.294 8.43453 11.638 8.72153 11.72 9.11953H12.668C12.598 8.31553 11.889 7.68653 10.754 7.61753V7.00053H10.214V7.62953C9.138 7.73253 8.406 8.36153 8.406 9.25153C8.406 10.0385 8.95 10.5395 9.856 10.7445L10.214 10.8295V12.6095C9.66 12.5295 9.294 12.2335 9.211 11.8225H8.25ZM10.21 9.92753C9.678 9.80753 9.39 9.56353 9.39 9.19553C9.39 8.78553 9.701 8.47653 10.214 8.38653V9.92653H10.209L10.21 9.92753ZM10.832 10.9715C11.477 11.1165 11.775 11.3515 11.775 11.7675C11.775 12.2415 11.405 12.5675 10.755 12.6275V10.9535L10.832 10.9715Z"
                                                            fill="white"/>
                                                        </g>
                                                        <defs>
                                                        <clipPath id="clip0_1029_2558">
                                                        <rect width="16" height="16" fill="white"/>
                                                        </clipPath>
                                                        </defs>
                                                    </svg>
                                                    </span>
                                                    <span class="font-semibold"> {{ translate('convert_to_currency') }}</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center d-sm-none">
                                    @if ($walletStatus == 1 && $loyaltyPointStatus == 1)
                                        <button type="button" data-insufficient-point="{{ translate('insufficient_loyalty_point') }}"
                                            class="btn btn-sm rounded btn--primary {{ $loyaltyPointMinimumPoint > $totalLoyaltyPoint ? 'alert-insufficient-loyalty-point':'' }}"
                                            @if($loyaltyPointMinimumPoint <= $totalLoyaltyPoint) data-toggle="modal" data-target="#convertToCurrency" @endif >
                                        <span>
                                        <svg width="16" viewBox="0 0 16 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_1029_2558)">
                                            <path
                                                d="M0 5.00053C0.000244156 6.15774 0.401714 7.27906 1.13601 8.17345C1.8703 9.06784 2.89199 9.67997 4.027 9.90553C4.09164 9.18906 4.27551 8.48842 4.571 7.83253C3.695 7.53653 3.132 6.86453 3 5.91053H2.5V5.48453H2.966V5.05053C2.966 5.00453 2.966 4.95753 2.97 4.91553H2.5V4.48853H3.011C3.236 3.24053 4.213 2.50053 5.681 2.50053C5.997 2.50053 6.271 2.53153 6.5 2.58553V3.31853C6.23254 3.25902 5.95896 3.2315 5.685 3.23653C4.766 3.23653 4.147 3.70253 3.951 4.48853H5.868V4.91553H3.888C3.885 4.96153 3.885 5.01253 3.885 5.06253V5.48453H5.868V5.91153H3.93C4.048 6.51353 4.398 6.94153 4.935 7.14053C5.46154 6.26849 6.18567 5.53242 7.04897 4.99168C7.91228 4.45094 8.89059 4.12068 9.905 4.02753C9.65975 2.81304 8.97248 1.7328 7.97633 0.996044C6.98017 0.259291 5.74603 -0.0815315 4.51296 0.0395967C3.27989 0.160725 2.13566 0.735182 1.30191 1.65169C0.468163 2.5682 0.00423697 3.76153 0 5.00053ZM16 10.5005C16 11.9592 15.4205 13.3582 14.3891 14.3896C13.3576 15.4211 11.9587 16.0005 10.5 16.0005C9.04131 16.0005 7.64236 15.4211 6.61091 14.3896C5.57946 13.3582 5 11.9592 5 10.5005C5 9.04184 5.57946 7.64289 6.61091 6.61144C7.64236 5.57999 9.04131 5.00053 10.5 5.00053C11.9587 5.00053 13.3576 5.57999 14.3891 6.61144C15.4205 7.64289 16 9.04184 16 10.5005ZM8.25 11.8225C8.319 12.6575 8.996 13.3075 10.214 13.3845V14.0005H10.754V13.3805C12.013 13.2945 12.75 12.6405 12.75 11.6905C12.75 10.8255 12.187 10.3805 11.18 10.1505L10.754 10.0505V8.37453C11.294 8.43453 11.638 8.72153 11.72 9.11953H12.668C12.598 8.31553 11.889 7.68653 10.754 7.61753V7.00053H10.214V7.62953C9.138 7.73253 8.406 8.36153 8.406 9.25153C8.406 10.0385 8.95 10.5395 9.856 10.7445L10.214 10.8295V12.6095C9.66 12.5295 9.294 12.2335 9.211 11.8225H8.25ZM10.21 9.92753C9.678 9.80753 9.39 9.56353 9.39 9.19553C9.39 8.78553 9.701 8.47653 10.214 8.38653V9.92653H10.209L10.21 9.92753ZM10.832 10.9715C11.477 11.1165 11.775 11.3515 11.775 11.7675C11.775 12.2415 11.405 12.5675 10.755 12.6275V10.9535L10.832 10.9715Z"
                                                fill="white"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_1029_2558">
                                            <rect width="16" height="16" fill="white"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                        </span>
                                        <span>
                                            {{translate('convert_to_currency')}}
                                        </span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card __card loyalty-card">
                    <div
                        class="card-header border-0 gap-2 d-flex flex-column flex-sm-row align-items-center justify-content-between">
                        <h5 class="mb-0 font-bold fs-16">{{ translate('Transaction_History') }}</h5>

                        @if(count($loyaltyPointList) > 0)
                            <div class="navbar-nav">
                                <div class="dropdown border rounded">
                                    <button class="btn btn-sm pl-3 dropdown-toggle ps-0" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ translate('filter')}}
                                        : {{ request()->has('type') ? ucwords(translate(request('type'))):translate('all')}}
                                    </button>

                                    <div class="dropdown-menu __dropdown-menu-3 __min-w-165px text-align-direction"
                                         aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{route('loyalty')}}/?type=all">
                                            {{translate('all_Transaction')}}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{route('loyalty')}}/?type=order_place">
                                            {{translate('order_transactions')}}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{route('loyalty')}}/?type=point_to_wallet">
                                            {{translate('point_to_wallet')}}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{route('loyalty')}}/?type=refund_order">
                                            {{translate('refund_order')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="card-body pt-0">
                        <div class="d-flex flex-column gap-2">
                            @foreach($loyaltyPointList as $key=> $item)
                                <div class="bg--light my-1 p-3 p-sm-3 rounded d-flex justify-content-between g-2">
                                    <div class="">
                                        <h6 class="mb-2 d-flex align-items-center gap-8">
                                            @if($item['debit'] != 0)
                                                <img
                                                    src="{{ theme_asset(path: 'public/assets/front-end/img/icons/coin-danger.png') }}"
                                                    width="25" alt="">
                                            @else
                                                <img
                                                    src="{{ theme_asset(path: 'public/assets/front-end/img/icons/coin-success.png') }}"
                                                    width="25" alt="">
                                            @endif
                                            <span class="fs-18 font-bold">
                                                {{ $item['debit'] != 0 ? ' - '.$item['debit'] : ' + '.$item['credit'] }}
                                            </span>
                                        </h6>
                                        <h6 class="text-muted text-capitalize mb-0 fs-13 font-semibold">
                                            {{str_replace('_', ' ',$item['transaction_type'])}}
                                        </h6>
                                    </div>
                                    <div class="text-end fs-13">
                                        <div class="text-muted mb-1 fs-13 font-semibold">
                                            {{$item['created_at']}}
                                        </div>
                                        @if($item['debit'] != 0)
                                            <p class="text-danger fs-12 m-0">{{translate('debit')}}</p>
                                        @else
                                            <p class="text-success fs-12 m-0">{{translate('credit')}}</p>
                                        @endif
                                    </div>
                                </div>

                            @endforeach
                        </div>
                        @if($loyaltyPointList->count()==0)
                            <div class="d-flex flex-column gap-3 align-items-center text-center my-5">
                                <img width="72"
                                     src="{{ theme_asset(path: 'public/assets/front-end/img/icons/empty-transaction-history.png')}}"
                                     class="dark-support" alt="">
                                <h6 class="text-muted mt-3">{{translate('you_do_not_have_any')}}
                                    <br> {{ request('type') != 'all' ? ucwords(translate(request('type'))) : '' }} {{translate('transaction_yet')}}
                                </h6>
                            </div>
                        @endif
                        <div class="card-footer border-0">
                            {{ $loyaltyPointList->links() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="bottom-sticky4_js"></div>
    <div class="bottom-sticky4 bg-white d-md-none p-3">
        @if ($walletStatus == 1 && $loyaltyPointStatus == 1)
                <button type="button" data-insufficient-point="{{ translate('insufficient_loyalty_point') }}"
                        class="btn btn-sm rounded btn--primary w-100 {{ $loyaltyPointMinimumPoint > $totalLoyaltyPoint ? 'alert-insufficient-loyalty-point':'' }}"
                        @if($loyaltyPointMinimumPoint <= $totalLoyaltyPoint) data-toggle="modal" data-target="#convertToCurrency" @endif >
                <span>
                <svg width="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_1029_2558)">
                    <path
                        d="M0 5.00053C0.000244156 6.15774 0.401714 7.27906 1.13601 8.17345C1.8703 9.06784 2.89199 9.67997 4.027 9.90553C4.09164 9.18906 4.27551 8.48842 4.571 7.83253C3.695 7.53653 3.132 6.86453 3 5.91053H2.5V5.48453H2.966V5.05053C2.966 5.00453 2.966 4.95753 2.97 4.91553H2.5V4.48853H3.011C3.236 3.24053 4.213 2.50053 5.681 2.50053C5.997 2.50053 6.271 2.53153 6.5 2.58553V3.31853C6.23254 3.25902 5.95896 3.2315 5.685 3.23653C4.766 3.23653 4.147 3.70253 3.951 4.48853H5.868V4.91553H3.888C3.885 4.96153 3.885 5.01253 3.885 5.06253V5.48453H5.868V5.91153H3.93C4.048 6.51353 4.398 6.94153 4.935 7.14053C5.46154 6.26849 6.18567 5.53242 7.04897 4.99168C7.91228 4.45094 8.89059 4.12068 9.905 4.02753C9.65975 2.81304 8.97248 1.7328 7.97633 0.996044C6.98017 0.259291 5.74603 -0.0815315 4.51296 0.0395967C3.27989 0.160725 2.13566 0.735182 1.30191 1.65169C0.468163 2.5682 0.00423697 3.76153 0 5.00053ZM16 10.5005C16 11.9592 15.4205 13.3582 14.3891 14.3896C13.3576 15.4211 11.9587 16.0005 10.5 16.0005C9.04131 16.0005 7.64236 15.4211 6.61091 14.3896C5.57946 13.3582 5 11.9592 5 10.5005C5 9.04184 5.57946 7.64289 6.61091 6.61144C7.64236 5.57999 9.04131 5.00053 10.5 5.00053C11.9587 5.00053 13.3576 5.57999 14.3891 6.61144C15.4205 7.64289 16 9.04184 16 10.5005ZM8.25 11.8225C8.319 12.6575 8.996 13.3075 10.214 13.3845V14.0005H10.754V13.3805C12.013 13.2945 12.75 12.6405 12.75 11.6905C12.75 10.8255 12.187 10.3805 11.18 10.1505L10.754 10.0505V8.37453C11.294 8.43453 11.638 8.72153 11.72 9.11953H12.668C12.598 8.31553 11.889 7.68653 10.754 7.61753V7.00053H10.214V7.62953C9.138 7.73253 8.406 8.36153 8.406 9.25153C8.406 10.0385 8.95 10.5395 9.856 10.7445L10.214 10.8295V12.6095C9.66 12.5295 9.294 12.2335 9.211 11.8225H8.25ZM10.21 9.92753C9.678 9.80753 9.39 9.56353 9.39 9.19553C9.39 8.78553 9.701 8.47653 10.214 8.38653V9.92653H10.209L10.21 9.92753ZM10.832 10.9715C11.477 11.1165 11.775 11.3515 11.775 11.7675C11.775 12.2415 11.405 12.5675 10.755 12.6275V10.9535L10.832 10.9715Z"
                        fill="white"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_1029_2558">
                    <rect width="16" height="16" fill="white"/>
                    </clipPath>
                    </defs>
                </svg>
                </span>
                <span>
                    {{translate('convert_to_currency')}}
                </span>
            </button>
        @endif
    </div>

    <div class="modal fade rtl" id="convertToCurrency" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title flex-grow-1 text-center" id="exampleModalLongTitle">
                        {{translate('Convert point to wallet money')}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('loyalty-exchange-currency')}}" method="POST">
                    @csrf
                    <div class="modal-body py-sm-5">
                        <div class="px-sm-5">
                            <div class="text-start mb-2">
                                {{translate('Enters Point Amount')}}
                            </div>
                            <div class="form-group">
                                <input class="form-control text-center fz-30" type="number" id="city" name="point"
                                       required>
                            </div>
                            <div class="text-center mb-3 text-primary">
                    <span>
                        {{ $loyaltyPointExchangeRate }} {{translate('point')}} = {{ webCurrencyConverter(amount: 1)}}
                    </span>
                            </div>
                            <div class="my-wallet-card-content text-break-word">
                                <h6 class="subtitle text-capitalize text-primary">
                                    {{translate('Note')}}
                                </h6>
                                <ul>
                                    <li>
                                        {{translate('convert_your_loyalty_point_to_wallet_money')}}.
                                    </li>
                                    <li>
                                        {{translate('minimum')}} {{ $loyaltyPointMinimumPoint }} {{translate('points_required_to_convert_into_currency')}}
                                    </li>
                                </ul>
                            </div>
                            <button type="submit" class="btn btn--primary w-100 mt-5">
                                {{translate('submit')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/user-loyalty.js') }}"></script>
@endpush
