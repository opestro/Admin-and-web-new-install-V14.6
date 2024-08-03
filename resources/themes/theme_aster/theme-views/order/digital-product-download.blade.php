@extends('theme-views.layouts.app')

@section('title', translate('download_Digital_Product').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-5">
        <div class="container">
            <div class="card h-100">
                <div class="card-body py-4 px-sm-4">
                    <h2 class="mb-30 text-center">{{ translate('download_your_product') }}</h2>
                    <form action="{{ route('digital-product-download-pos.index') }}" type="submit" method="get" class="p-sm-3 border rounded mb-4">
                        <div class="d-flex flex-column flex-sm-row flex-wrap gap-3 align-items-sm-end">
                            <div class="flex-grow-1 d-flex gap-3">
                                <div class="form-group flex-grow-1">
                                    <label for="order_id">{{ translate('order_ID') }}</label>
                                    <input type="text" id="order_id" name="order_id" class="form-control"
                                           placeholder="{{ translate('order_ID') }}" value="{{ request('order_id') }}">
                                </div>
                                <div class="form-group flex-grow-1">
                                    <label for="phone_or_email">{{ translate('email') }}</label>
                                    <input id="phone_or_email" class="form-control" type="email"
                                           placeholder="{{ translate('email_address') }}" value="{{ request('email') }}" name="email">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary h-45 flex-grow-1">
                                {{ isset($order) ? translate('verified') : translate('verify') }}
                            </button>
                        </div>
                    </form>

                    @if(isset($orderDetails))
                        @if($isDigitalProductExist != 0)
                            @if($isDigitalProductReadyCount == 0)
                                <div class="border rounded px-3 py-3 fs-15 text-base font-weight-medium custom-light-primary-color mb-3 d-flex align-items-center gap-3">
                                    <img src="{{ theme_asset('assets/img/icons/info-light.svg') }}" alt="" class="px-2">
                                    <span>
                                        {{ translate('your_digital_product_is_ready.') }}
                                        {{ translate('once_the_seller_has_uploaded_the_product__you_will_be_able_to_download_here_by_using_your_order_info.') }}
                                        {{ translate('if_you_face_any_issue_during_download_please_until_wait_or_contact_admin_via') }}

                                        @if(auth('customer')->check())
                                            <a class="text-base fw-bold text-underline" href="{{route('account-tickets')}}">
                                                {{ translate('support_ticket')}}
                                            </a>
                                        @else
                                            <a class="text-base fw-bold text-underline" href="{{route('customer.auth.login')}}">
                                                {{ translate('support_ticket')}}
                                            </a>
                                        @endif
                                    </span>
                                </div>
                            @else
                                <div class="d-flex flex-column gap-2 p-4 rounded border">
                                    @foreach($orderDetails as $index => $orderDetail)
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                <img width="50" src="{{ getStorageImages(path: $orderDetail?->product?->thumbnail_full_url, type: 'product') }}" alt="" class="border rounded">
                                                <a class="fs-13 font-semi-bold" href="{{ route('product', $orderDetail->product->slug) }}">
                                                    {{ $orderDetail->product->name }}
                                                </a>
                                            </div>
                                            <div>
                                                @php($productDetails = json_decode($orderDetail->product_details, true))

                                                @if($productDetails['digital_product_type'] == 'ready_product')
                                                    <?php
                                                        $checkFilePath = storageLink('product/digital-product', $productDetails['digital_file_ready'], ($productDetails['storage_path'] ?? 'public'));
                                                        $filePath = $checkFilePath['path'];
                                                        $fileExist = $checkFilePath['status'] == 200;
                                                        $fileName = $productDetails['digital_file_ready'];
                                                    ?>
                                                    @if ($fileExist)
                                                        <span class="btn p-0 shadow-none border-0 getDownloadFileUsingFileUrl" data-bs-toggle="tooltip" title="{{ translate('download') }}" data-file-path="{{ $filePath }}">
                                                            <img src="{{ theme_asset(path: 'assets/img/icons/download-green.svg') }}" alt="">
                                                        </span>
                                                    @else
                                                        <a class="btn p-0 shadow-none border-0" data-bs-toggle="tooltip" title="{{ translate('File_not_found') }}" href="javascript:" disabled>
                                                            <img src="{{ theme_asset(path: 'assets/img/icons/download-green.svg') }}" alt="">
                                                        </a>
                                                    @endif
                                                @elseif($productDetails['digital_product_type'] == 'ready_after_sell')
                                                    @if($orderDetail['digital_file_after_sell'])
                                                        <?php
                                                            $checkFilePath = $orderDetail->digital_file_after_sell_full_url;
                                                            $filePath = $checkFilePath['path'];
                                                            $fileName = $orderDetail['digital_file_after_sell'];
                                                            $fileExist = $checkFilePath['status'] == 200;
                                                        ?>
                                                        <span class="btn p-0 shadow-none border-0 getDownloadFileUsingFileUrl" data-bs-toggle="tooltip" title="{{ translate('download') }}" data-file-path="{{ $filePath }}">
                                                            <img src="{{ theme_asset(path: 'assets/img/icons/download-green.svg') }}" alt="">
                                                        </span>
                                                    @else
                                                        <a class="btn p-0 shadow-none border-0" data-bs-toggle="tooltip" title="{{ translate('product_not_uploaded_yet') }}" disabled>
                                                            <img src="{{ theme_asset(path: 'assets/img/icons/download-green.svg') }}" alt="">
                                                        </a>
                                                    @endif
                                                @endif

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="rounded px-3 py-3 fs-15 text-base font-weight-medium custom-light-primary-color mb-3 d-flex align-items-center gap-3">
                                <img src="{{ theme_asset('public/assets/front-end/img/icons/info-light.svg') }}" alt="" class="px-2">
                                <span>
                                    {{ translate('you_have_no_digital_products_in_your_order') }}
                                </span>
                            </div>
                        @endif

                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
