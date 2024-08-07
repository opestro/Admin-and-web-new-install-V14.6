@extends('theme-views.layouts.app')
@section('title', translate('my_Wishlists').' | '.$web_config['name']->value.' '.translate('ecommerce'))
@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="d-flex gap-4 flex-wrap d-lg-none mb-3 mt-2 justify-content-end">
                        @if($wishlists->count()>0)
                            <a href="javascript:"
                               data-action="{{ route('delete-wishlist-all') }}"
                               data-text = "{{translate('want_to_clear_all_wishlist').'?'}}"
                               class="btn-link text-danger text-capitalize">
                                {{translate('clear_all')}}
                            </a>
                        @endif
                    </div>
                    <div class="card h-lg-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5>{{translate('My_Wish_List')}}</h5>
                                <div class="d-lg-flex gap-4 flex-wrap d-none">
                                    @if($wishlists->count()>0)
                                    <a href="javascript:"
                                       data-action="{{route('delete-wishlist-all')}}"
                                       data-text = "{{translate('want_to_clear_all_wishlist').'?'}}"
                                       class="btn-link text-danger text-capitalize delete-action">
                                        {{translate('clear_all')}}
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-4" id="set-wish-list">
                                @include('theme-views.partials._wish-list-data',['wishlists'=>$wishlists, 'brand_setting'=>$brand_setting])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
