@extends('layouts.back-end.app')

@section('title', translate('POS'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/css/intlTelInput.css') }}">
@endpush
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="card">

                    <h5 class="p-3 m-0 bg-light">
                        {{ translate('product_Section') }}
                    </h5>

                    <div class="px-3 py-4">
                        <div class="row gy-1">
                            <div class="col-sm-6">
                                <div class="input-group d-flex justify-content-end">
                                    <select name="category" id="category" class="form-control js-select2-custom w-100 action-category-filter" title="select category">
                                        <option value="">{{ translate('all_categories') }}</option>
                                        @foreach ($categories as $item)
                                            <option value="{{$item->id}}" {{$categoryId==$item->id?'selected':''}}>
                                                {{ $item->defaultName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <form class="">
                                    <div class="input-group-overlay input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="search" autocomplete="off" type="text"
                                                value="{{ $searchValue }}"
                                                name="searchValue" class="form-control search-bar-input"
                                                placeholder="{{ translate('search_by_name_or_sku') }}"
                                                aria-label="Search here">
                                        <diV class="card pos-search-card w-4 position-absolute z-index-1 w-100">
                                            <div id="pos-search-box"
                                                    class="card-body search-result-box d--none"></div>
                                        </diV>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-2" id="items">
                        <div class="pos-item-wrap">
                            @foreach($products as $product)
                                @include('admin-views.pos.partials._single-product',['product'=>$product])
                            @endforeach
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {!!$products->withQueryString()->links()!!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-5">
                <div class="card billing-section-wrap">
                    <h5 class="p-3 m-0 bg-light">{{ translate('billing_Section') }}</h5>
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn btn-outline--primary d-flex align-items-center gap-2 action-view-all-hold-orders"
                            data-toggle="tooltip" data-placement="top"
                                    title="{{translate('please_resume_the_order_from_here')}}">
                                {{ translate('view_All_Hold_Orders') }}
                                <span class="total_hold_orders">
                                    {{$totalHoldOrder}}
                                </span>
                            </button>
                        </div>

                        <div class="form-group d-flex gap-2">
                            <?php
                            $userId = 0;
                            if (Illuminate\Support\Str::contains(session('current_user'), 'saved-customer')) {
                                $userId = explode('-', session('current_user'))[2];
                            }
                            ?>
                            <select id='customer' name="customer_id" data-placeholder="Walking Customer" class="js-example-matcher form-control form-ellipsis action-customer-change">
                                <option value="0" {{ $userId == 0 ? 'selected':''}}>{{ translate('walking_customer') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $userId == $customer->id ? 'selected':''}}>{{ $customer->f_name }} {{ $customer->l_name }}
                                        ({{ $customer->phone }})
                                    </option>
                                @endforeach
                            </select>

                            <button class="btn btn-success rounded text-nowrap" id="add_new_customer" type="button"
                                    data-toggle="modal" data-target="#add-customer" title="{{translate('add_new_customer')}}">
                                {{ translate('add_New_Customer') }}
                            </button>
                        </div>

                        <div id="cart-summary">
                            @include('admin-views.pos.partials._cart-summary')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="modal fade pt-5" id="quick-view" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" id="quick-view-modal"></div>
    </div>
</div>

<button class="d-none" id="hold-orders-modal-btn" type="button" data-toggle="modal" data-target="#hold-orders-modal">
</button>

@if($order)
@include('admin-views.pos.partials.modals._print-invoice')
@endif

@include('admin-views.pos.partials.modals._add-customer')
@include('admin-views.pos.partials.modals._hold-orders-modal')
@include('admin-views.pos.partials.modals._add-coupon-discount')
@include('admin-views.pos.partials.modals._add-discount')
@include('admin-views.pos.partials.modals._short-cut-keys')

<span id="route-admin-pos-get-cart-ids" data-url="{{ route('admin.pos.get-cart-ids') }}"></span>
<span id="route-admin-pos-new-cart-id" data-url="{{ route('admin.pos.new-cart-id') }}"></span>
<span id="route-admin-pos-clear-cart-ids" data-url="{{ route('admin.pos.clear-cart-ids') }}"></span>
<span id="route-admin-pos-view-hold-orders" data-url="{{ route('admin.pos.view-hold-orders') }}"></span>
<span id="route-admin-products-search-product" data-url="{{ route('admin.pos.search-product') }}"></span>
<span id="route-admin-pos-change-customer" data-url="{{ route('admin.pos.change-customer') }}"></span>
<span id="route-admin-pos-update-discount" data-url="{{ route('admin.pos.update-discount') }}"></span>
<span id="route-admin-pos-coupon-discount" data-url="{{ route('admin.pos.coupon-discount') }}"></span>
<span id="route-admin-pos-cancel-order" data-url="{{ route('admin.pos.cancel-order') }}"></span>
<span id="route-admin-pos-quick-view" data-url="{{ route('admin.pos.quick-view') }}"></span>
<span id="route-admin-pos-add-to-cart" data-url="{{ route('admin.pos.add-to-cart') }}"></span>
<span id="route-admin-pos-remove-cart" data-url="{{ route('admin.pos.remove-cart') }}"></span>
<span id="route-admin-pos-empty-cart" data-url="{{ route('admin.pos.empty-cart') }}"></span>
<span id="route-admin-pos-update-quantity" data-url="{{ route('admin.pos.update-quantity') }}"></span>
<span id="route-admin-pos-get-variant-price" data-url="{{ route('admin.pos.get-variant-price') }}"></span>
<span id="route-admin-pos-change-cart-editable" data-url="{{ route('admin.pos.change-cart').'/?cart_id=:value' }}"></span>

<span id="message-cart-word" data-text="{{ translate('cart') }}"></span>
<span id="message-stock-out" data-text="{{ translate('stock_out') }}"></span>
<span id="message-stock-id" data-text="{{ translate('in_stock') }}"></span>
<span id="message-add-to-cart" data-text="{{ translate('add_to_cart') }}"></span>
<span id="message-cart-updated" data-text="{{ translate('cart_updated') }}"></span>
<span id="message-update-to-cart" data-text="{{ translate('update_to_cart') }}"></span>
<span id="message-cart-is-empty" data-text="{{ translate('cart_is_empty') }}"></span>
<span id="message-coupon-is-invalid" data-text="{{ translate('coupon_is_invalid') }}"></span>
<span id="message-product-quantity-updated" data-text="{{ translate('product_quantity_updated') }}"></span>
<span id="message-coupon-added-successfully" data-text="{{ translate('coupon_added_successfully') }}"></span>
<span id="message-sorry-stock-limit-exceeded" data-text="{{ translate('sorry_stock_limit_exceeded') }}"></span>
<span id="message-please-choose-all-the-options" data-text="{{ translate('please_choose_all_the_options') }}"></span>
<span id="message-item-has-been-removed-from-cart" data-text="{{ translate('item_has_been_removed_from_cart') }}"></span>
<span id="message-you-want-to-remove-all-items-from-cart" data-text="{{ translate('you_want_to_remove_all_items_from_cart') }}"></span>
<span id="message-product-quantity-is-not-enough" data-text="{{ translate('product_quantity_is_not_enough') }}"></span>
<span id="message-sorry-product-is-out-of-stock" data-text="{{ translate('sorry_product_is_out_of_stock') }}"></span>
<span id="message-item-has-been-added-in-your-cart" data-text="{{ translate('item_has_been_added_in_your_cart') }}"></span>
<span id="message-extra-discount-added-successfully" data-text="{{ translate('extra_discount_added_successfully') }}"></span>
<span id="message-amount-can-not-be-negative-or-zero" data-text="{{ translate('amount_can_not_be_negative_or_zero') }}"></span>
<span id="message-sorry-the-minimum-value-was-reached" data-text="{{ translate('sorry_the_minimum_value_was_reached') }}"></span>
<span id="message-this-discount-is-not-applied-for-this-amount" data-text="{{ translate('this_discount_is_not_applied_for_this_amount') }}"></span>
<span id="message-product-quantity-cannot-be-zero-in-cart" data-text="{{ translate('product_quantity_can_not_be_zero_or_less_than_zero_in_cart') }}"></span>

@endsection

@push('script_2')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/country-picker-init.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/pos-script.js') }}"></script>
    <script>
        "use strict";
        $(document).on('ready', function () {
            @if($order)
            $('#print-invoice').modal('show');
            @endif
        });
    </script>
@endpush
