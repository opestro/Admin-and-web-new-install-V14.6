<div class="container rtl pb-4 pt-3 px-0 px-md-3">
    <div class="shipping-policy-web">
        <div class="footer-top-slider owl-theme owl-carousel">
            @foreach ($companyReliability as $key=>$value)
                @if ($value['status'] == 1 && !empty($value['title']))
                    <div class="footer-top-slide-item">
                        <div class="d-flex justify-content-center">
                            <div class="shipping-method-system">
                                <div class="w-100 d-flex justify-content-center mb-1">
                                    <img alt="" class="size-60"
                                         src="{{ getValidImage(path: 'storage/app/public/company-reliability/'.$value['image'], type: 'source', source: theme_asset(path: 'public/assets/front-end/img').'/'.$value['item'].'.png') }}"
                                    >
                                </div>
                                <div class="w-100 text-center">
                                    <p class="m-0">{{ $value['title'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
