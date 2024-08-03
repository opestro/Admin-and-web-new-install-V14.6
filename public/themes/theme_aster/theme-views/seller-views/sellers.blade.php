@php use App\Utils\ProductManager; @endphp
@extends('theme-views.layouts.app')

@section('title', (request('filter') && request('filter') == 'top-vendors' ? translate('top_Stores') : translate('all_Stores')).' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-30">
        <div class="container">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row gy-2 align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-1 text-capitalize">
                                {{ (request('filter') && request('filter') == 'top-vendors' ? translate('top_Stores') : translate('all_Stores')) }}
                            </h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb fs-12 mb-0">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">{{translate('home')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        <a href="{{route('vendors')}}">{{translate('stores')}}</a></li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-4">
                            <div class="custom_search position-relative float-end">
                                <form action="{{ route('vendors') }}" method="get">
                                    @if(request('filter'))
                                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                                    @endif
                                    <div class="d-flex">
                                        <div
                                            class="select-wrap focus-border border border-end-logical-0 d-flex align-items-center">
                                            <input type="search"
                                                   class="form-control border-0 focus-input search-bar-input"
                                                   name="shop_name" placeholder="{{translate('shop_name')}}" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                                <div
                                    class="card search-card __inline-13 position-absolute z-999 bg-white top-100 start-0 search-result-box"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="auto-col xxl-items-6 justify-content-center gap-3">
                        @foreach ($sellers as $shop)
                            @php($currentDate = date('Y-m-d'))
                            @php($startDate = date('Y-m-d', strtotime($shop['vacation_start_date'])))
                            @php($endDate = date('Y-m-d', strtotime($shop['vacation_end_date'])))

                            <a href="{{route('shopView',['id'=>$shop['seller_id']])}}"
                               class="store-item grid-center py-2">
                                <div class="position-relative">
                                    <div class="avatar rounded-circle border" style="--size: 6.875rem">
                                        @if($shop['id'] != 0)
                                            <img class="dark-support img-fit rounded-circle img-w-h-100"
                                                 src="{{ getValidImage(path: 'storage/app/public/shop/'.$shop->image, type:'shop') }}"
                                                 alt="{{$shop->name}}" loading="lazy">
                                        @else
                                            <img class="dark-support img-fit rounded-circle img-w-h-100"
                                                 src="{{ getValidImage(path: 'storage/app/public/company/'.$shop->image, type:'shop') }}"
                                                 alt="{{$shop->name}}" loading="lazy">
                                        @endif
                                    </div>
                                    @if($shop->temporary_close)
                                        <span class="temporary-closed position-absolute rounded-circle">
                                            <span class="px-1 text-center">{{translate('Temporary_OFF')}}</span>
                                        </span>
                                    @elseif($shop->vacation_status && ($currentDate >= $startDate) && ($currentDate <= $endDate))
                                        <span class="temporary-closed position-absolute rounded-circle">
                                            <span>{{translate('closed_Now')}}</span>
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex flex-column align-items-center flex-wrap gap-2 mt-3">
                                    <h6 class="text-truncate mx-auto text-center">{{Str::limit($shop->name, 14)}}</h6>
                                    <p>{{ $shop['products_count'] < 1000 ? $shop['products_count'] : number_format($shop['products_count']/1000 , 1).'K'}} {{translate('products')}}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if (count($sellers) == 0)
                        <div class="w-100 text-center pt-5">
                            <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-vendor.svg') }}" alt="">
                            <h5 class="text-center text-muted">{{ translate('there_is_no_vendor') }}.</h5>
                        </div>
                    @endif
                    <div class="mt-5">
                        {{ $sellers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
