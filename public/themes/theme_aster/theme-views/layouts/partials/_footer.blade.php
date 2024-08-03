<footer class="footer">
    <div class="footer-bg-img" data-bg-img="{{theme_asset('assets/img/background/footer-bg.png')}}">
    </div>
    <div class="footer-top">
        <div class="container">
            <div class="row gy-3 align-items-center">
                <div class="col-lg-3 col-sm-3 text-center text-lg-start">
                    <img width="180" loading="lazy" alt="{{translate('image')}}"
                         src="{{ getValidImage(path: 'storage/app/public/company/'.($web_config['footer_logo']->value), type:'logo') }}">
                </div>
                <div
                    class="col-lg-6 col-sm-6 d-flex justify-content-center justify-content-sm-start justify-content-lg-center">
                    <ul class="list-socials list-socials--white gap-4 fs-18">
                        @if($web_config['social_media'])
                            @foreach ($web_config['social_media'] as $item)
                                <li>
                                    @if ($item->name == "twitter")
                                        <a href="{{$item->link}}" target="_blank" class="font-bold">
                                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20"
                                                 height="20" viewBox="0 0 24 24">
                                                <g opacity=".3">
                                                    <polygon fill="#fff" fill-rule="evenodd"
                                                             points="16.002,19 6.208,5 8.255,5 18.035,19"
                                                             clip-rule="evenodd"></polygon>
                                                    <polygon
                                                        points="8.776,4 4.288,4 15.481,20 19.953,20 8.776,4"></polygon>
                                                </g>
                                                <polygon fill-rule="evenodd"
                                                         points="10.13,12.36 11.32,14.04 5.38,21 2.74,21"
                                                         clip-rule="evenodd"></polygon>
                                                <polygon fill-rule="evenodd"
                                                         points="20.74,3 13.78,11.16 12.6,9.47 18.14,3"
                                                         clip-rule="evenodd"></polygon>
                                                <path
                                                    d="M8.255,5l9.779,14h-2.032L6.208,5H8.255 M9.298,3h-6.93l12.593,18h6.91L9.298,3L9.298,3z"
                                                    fill="currentColor"></path>
                                            </svg>
                                        </a>
                                    @elseif($item->name == 'google-plus')
                                        <a href="{{$item->link}}" target="_blank">
                                            <i class="bi bi-google"></i>
                                        </a>
                                    @else
                                        <a href="{{$item->link}}" target="_blank">
                                            <i class="bi bi-{{$item->name}}"></i>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="col-lg-3 col-sm-3 d-flex justify-content-center justify-content-sm-start">
                    <div class="media gap-3 absolute-white">
                        <i class="bi bi-telephone-forward fs-28"></i>
                        <div class="media-body">
                            <h6 class="absolute-white mb-1">{{translate('hotline')}}</h6>
                            <a dir="ltr" href="tel:{{$web_config['phone']->value}}"
                               class="absolute-white">{{$web_config['phone']->value}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-main px-2  px-lg-0">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4">
                    <div class="widget widget--about text-center text-lg-start absolute-white">
                        <p>{{ getWebConfig(name: 'shop_address')}}</p>
                        <a href="mailto:{{$web_config['email']->value}}">{{$web_config['email']->value}}</a>

                        <div class="d-flex gap-3 justify-content-center justify-content-lg-start flex-wrap mt-4">
                            @if($web_config['android']['status'])
                                <a href="{{ $web_config['android']['link'] }}">
                                    <img
                                        src="{{ theme_asset('assets/img/media/google-play.png') }}" loading="lazy"
                                        alt="{{translate('image')}}">
                                </a>
                            @endif
                            @if($web_config['ios']['status'])
                                <a href="{{ $web_config['ios']['link'] }}">
                                    <img
                                        src="{{ theme_asset('assets/img/media/app-store.png') }}" loading="lazy"
                                        alt="{{translate('image')}}">
                                </a>
                            @endif
                        </div>
                        <div class="mt-4 mb-3">
                            <div class="d-flex gap-2">
                                <h6 class="text-uppercase mb-2 font-weight-bold footer-heder">{{translate('newsletter')}}</h6>
                                <i class="bi bi-send-fill mt-n1"></i>
                            </div>
                            <p>{{translate('subscribe_our_newsletter_to_get_latest_updates')}}</p>
                        </div>
                        <form class="newsletter-form" action="{{ route('subscription') }}" method="post">
                            @csrf
                            <div class="position-relative">
                                <label class="position-relative m-0 d-block">
                                    <i class="bi bi-envelope envelop-icon text-muted fs-18"></i>
                                    <input type="text" placeholder="{{ translate('enter_your_email') }}"
                                           class="form-control" name="subscription_email" required>
                                </label>
                                <button type="submit" class="btn btn-primary">{{ translate('submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row gy-5">
                        <div class="col-sm-4 col-6">
                            <div class="widget widget--nav absolute-white">
                                <h4 class="widget__title">{{translate('accounts')}}</h4>
                                <ul class="d-flex flex-column gap-3">
                                    @if($web_config['seller_registration'])
                                        <li>
                                            <a class="text-capitalize"
                                               href="{{route('vendor.auth.registration.index')}}">{{translate('open_your_store')}}</a>
                                        </li>
                                    @endif
                                    <li>
                                        @if(auth('customer')->check())
                                            <a href="{{route('user-profile')}}">{{translate('profile')}}</a>
                                        @else
                                            <button class="bg-transparent border-0 p-0" data-bs-toggle="modal"
                                                    data-bs-target="#loginModal">{{translate('profile')}}</button>
                                        @endif
                                    </li>
                                    <li>
                                        <a class="text-capitalize"
                                           href="{{route('track-order.index') }}">{{translate('track_order')}}</a>
                                    </li>
                                    <li><a class="text-capitalize"
                                           href="{{route('contacts')}}">{{translate('help_&_support')}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="widget widget--nav absolute-white">
                                <h4 class="widget__title text-capitalize">{{translate('quick_links')}}</h4>
                                <ul class="d-flex flex-column gap-3">
                                    @if($web_config['flash_deals'])
                                        <li>
                                            <a class="text-capitalize"
                                               href="{{route('flash-deals',[$web_config['flash_deals']['id']])}}">{{translate('flash_deals')}}</a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="text-capitalize"
                                           href="{{route('products',['data_from'=>'featured','page'=>1])}}">{{translate('featured_products')}}</a>
                                    </li>
                                    <li><a class="text-capitalize"
                                           href="{{route('vendors')}}">{{translate('top_stores')}}</a></li>
                                    <li>
                                        <a class="text-capitalize"
                                           href="{{route('products',['data_from'=>'latest'])}}">{{translate('latest_products')}}</a>
                                    </li>
                                    <li><a href="{{route('helpTopic')}}">{{translate('FAQ')}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="widget widget--nav absolute-white">
                                <h4 class="widget__title">{{translate('other')}}</h4>
                                <ul class="d-flex flex-column gap-3">
                                    <li><a class="text-capitalize"
                                           href="{{route('about-us')}}">{{translate('about_company')}}</a></li>
                                    <li><a class="text-capitalize"
                                           href="{{route('privacy-policy')}}">{{translate('privacy_policy')}}</a></li>
                                    <li><a class="text-capitalize"
                                           href="{{route('terms')}}">{{translate('terms_&_conditions')}}</a></li>

                                    @if(isset($web_config['refund_policy']['status']) && $web_config['refund_policy']['status'] == 1)
                                        <li>
                                            <a class="text-capitalize"
                                               href="{{route('refund-policy')}}">{{translate('refund_policy')}}</a>
                                        </li>
                                    @endif

                                    @if(isset($web_config['return_policy']['status']) && $web_config['return_policy']['status'] == 1)
                                        <li>
                                            <a class="text-capitalize"
                                               href="{{route('return-policy')}}">{{translate('return_policy')}}</a>
                                        </li>
                                    @endif

                                    @if(isset($web_config['cancellation_policy']['status']) && $web_config['cancellation_policy']['status'] == 1)
                                        <li>
                                            <a class="text-capitalize"
                                               href="{{route('cancellation-policy')}}">{{translate('cancellation_policy')}}</a>
                                        </li>
                                    @endif

                                    <li>
                                        @if(auth('customer')->check())
                                            <a class="text-capitalize"
                                               href="{{route('account-tickets')}}">{{translate('support_ticket')}}</a>
                                        @else
                                            <button class="bg-transparent border-0 p-0 text-capitalize"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#loginModal">{{translate('support_ticket')}}</button>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom absolute-white">
        <div class="container">
            <div class="text-center copyright-text">
                {{ $web_config['copyright_text']->value }}
            </div>
        </div>
    </div>
</footer>
