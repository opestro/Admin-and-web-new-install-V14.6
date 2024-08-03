@extends('layouts.front-end.app')

@section('title', translate('flash_Deal_Products'))

@push('css_or_js')
    <meta property="og:image" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Deals of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Deals of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    <style>
        .countdown-background {
            background: var(--web-primary);
        }

        .cz-countdown-days {
            border: .5px solid var(--web-primary);
        }

        .cz-countdown-hours {
            border: .5px solid var(--web-primary);
        }

        .cz-countdown-minutes {
            border: .5px solid var(--web-primary);
        }

        .cz-countdown-seconds {
            border: .5px solid var(--web-primary);
        }

        .flash_deal_product_details .flash-product-price {
            color: var(--web-primary);
        }
    </style>
@endpush

@section('content')
    @php($decimal_point_settings = getWebConfig(name: 'decimal_point_settings'))
    <div class="__inline-59 pt-md-3">
        @if(file_exists('storage/app/public/deal/'.$deal['banner']))
            @php($deal_banner = dynamicStorage(path: 'storage/app/public/deal/'.$deal['banner']))
        @else
            @php($deal_banner = theme_asset(path: 'public/assets/front-end/img/flash-deals.png'))
        @endif
        <div class="container md-4 mt-3 rtl text-align-direction">
            <div class="__flash-deals-bg rounded" style="background: url({{$deal_banner}}) no-repeat center center / cover">
                <div class="row g-3 justify-content-end align-items-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="countdown-card bg-transparent">
                            <div class="text-center text-white">
                                <div class="countdown-background">
                                 <span class="cz-countdown d-flex justify-content-center align-items-center"
                                       data-countdown="{{$web_config['flash_deals']?date('m/d/Y',strtotime($web_config['flash_deals']['end_date'])):''}} 23:59:00">
                                     <span class="cz-countdown-days">
                                         <span class="cz-countdown-value"></span>
                                         <span class="cz-countdown-text">{{ translate('days')}}</span>
                                     </span>
                                     <span class="cz-countdown-value p-1">:</span>
                                     <span class="cz-countdown-hours">
                                         <span class="cz-countdown-value"></span>
                                         <span class="cz-countdown-text">{{ translate('hrs')}}</span>
                                     </span>
                                     <span class="cz-countdown-value p-1">:</span>
                                     <span class="cz-countdown-minutes">
                                         <span class="cz-countdown-value"></span>
                                         <span class="cz-countdown-text">{{ translate('min')}}</span>
                                     </span>
                                     <span class="cz-countdown-value p-1">:</span>
                                     <span class="cz-countdown-seconds">
                                         <span class="cz-countdown-value"></span>
                                         <span class="cz-countdown-text">{{ translate('sec')}}</span>
                                     </span>
                                 </span>

                                    <?php
                                        $startDate = \Carbon\Carbon::parse($web_config['flash_deals']['start_date']);
                                        $endDate = \Carbon\Carbon::parse($web_config['flash_deals']['end_date']);
                                        $now = \Carbon\Carbon::now();
                                        $totalDuration = $endDate->diffInSeconds($startDate);
                                        $elapsedDuration = $now->diffInSeconds($startDate);
                                        $flashDealsPercentage = ($elapsedDuration / $totalDuration) * 100;
                                    ?>

                                    <div class="progress __progress">
                                        <div class="progress-bar flash-deal-progress-bar" role="progressbar"
                                             style="width: {{ number_format($flashDealsPercentage, 2) }}%"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl text-align-direction">
            <div class="row">
                <div class="col-lg-4 col-md-6 my-2 text-center {{Session::get('direction') === "rtl" ? 'text-sm-right' : 'text-sm-left'}}">
                    <div class="flash_deal_title">
                        {{$web_config['flash_deals']->title}}
                    </div>
                    <span class="fs-14 font-weight-normal">{{translate('hurry_Up')}} ! {{translate('the_offer_is_limited')}}. {{translate('grab_while_it_lasts')}}</span>
                </div>

                <section class="col-lg-12">
                    <div class="row g-3 mt-2">
                        @foreach($flashDealProducts as $flashDealProduct)
                            <div class="col--xl-2 col-sm-4 col-lg-3 col-6">
                                @include('web-views.partials._inline-single-product',['product'=> $flashDealProduct,'decimal_point_settings'=>$decimal_point_settings])
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/deals.js') }}"></script>
@endpush
