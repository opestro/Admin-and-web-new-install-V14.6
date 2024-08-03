@php
    use function App\Utils\customer_info;
    $customer_info = customer_info();
@endphp
<div class="col-lg-3">
    <div class="card profile-sidebar-sticky">
        <div class="card-body position-relative">
            <div class="d-none d-lg-flex justify-content-end">
                <div class="dropdown">
                    <button class="btn-circle p-0 bg-primary absolute-white size-1-125rem" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots fs-10 grid-center"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="javascript:" class="delete-action"
                               data-action="{{route('account-delete',[$customer_info['id']])}}"
                               data-text="{{translate('want_to_delete_this_account').'?'}}">
                                {{ translate('delete_my_account') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="d-lg-none bg-primary rounded px-1 text-white cursor-pointer position-absolute end-1 top-1 profile-menu-toggle">
                <i class="bi bi-list fs-18"></i>
            </div>
            <div class="d-flex flex-row flex-lg-column gap-2 gap-lg-4 align-items-center">
                <div class="avatar overflow-hidden profile-sidebar-avatar border border-primary rounded-circle p-1">
                    <img class="img-fit dark-support rounded-circle"
                         src="{{ getValidImage(path: 'storage/app/public/profile/'.$customer_info->image, type:'avatar') }}" alt="">
                </div>

                <div class="text-lg-center">
                    <h5 class="mb-1">{{$customer_info->f_name}} {{$customer_info->l_name}}</h5>
                    <p class="fw-medium">{{translate('joined')}} {{date('d M, Y',strtotime($customer_info->created_at))}}</p>
                </div>
            </div>
            <div class="profile-menu-aside">
                <div class="profile-menu-aside-close d-lg-none">
                    <i class="bi bi-x-lg text-primary"></i>
                </div>
                <ul class="list-unstyled profile-menu gap-1 mt-3">
                    <li class="{{Request::is('user-profile') || Request::is('user-account') ||Request::is('account-address-*') ? 'active' :''}}">
                        <a href="{{ route('user-profile') }}">
                            <img width="20" src="{{ theme_asset('assets/img/icons/profile-icon.png') }}"
                                 class="dark-support" alt="">
                            <span class="text-capitalize">{{translate('my_profile')}}</span>
                        </a>
                    </li>
                    <li class="{{Request::is('account-oder*') || Request::is('account-order-details*') || Request::is('refund-details*') || Request::is('track-order/order-wise-result-view') ? 'active' :''}}">
                        <a href="{{route('account-oder')}}">
                            <img width="20" src="{{ theme_asset('assets/img/icons/profile-icon2.png') }}"
                                 class="dark-support" alt="">
                            <span>{{translate('Orders')}}</span>
                        </a>
                    </li>
                    <li class="{{Request::is('wishlists') ? 'active' :''}}">
                        <a href="{{route('wishlists')}}">
                            <img width="20" src="{{theme_asset('assets/img/icons/profile-icon3.png')}}"
                                 class="dark-support" alt="">
                            <span>{{translate('Wish_List')}}</span>
                        </a>
                    </li>
                    <li class="{{Request::is('product-compare/index') ? 'active' :''}}">
                        <a href="{{route('product-compare.index')}}">
                            <img width="20" src="{{theme_asset('assets/img/icons/profile-icon4.png')}}"
                                 class="dark-support" alt="">
                            <span>{{translate('Compare_List')}}</span>
                        </a>
                    </li>

                    @if ($web_config['wallet_status'] == 1)
                        <li class="{{Request::is('wallet') ? 'active' :''}}">
                            <a href="{{route('wallet')}}">
                                <img width="20" src="{{theme_asset('assets/img/icons/profile-icon5.png')}}"
                                     class="dark-support" alt="">
                                <span>{{translate('wallet')}}</span>
                            </a>
                        </li>
                    @endif
                    @if ($web_config['loyalty_point_status'] == 1)
                        <li class="{{Request::is ('loyalty') ? 'active' : ''}}">
                            <a href="{{route('loyalty')}}">
                                <img width="20" src="{{theme_asset('assets/img/icons/profile-icon6.png')}}"
                                     class="dark-support" alt="">
                                <span class="text-capitalize">{{translate('loyalty_point')}}</span>
                            </a>
                        </li>
                    @endif
                    <li class="{{Request::is ('chat/vendor') || Request::is ('chat/delivery-man') ? 'active' : ''}}">
                        <a href="{{route('chat', ['type' => 'vendor'])}}">
                            <img width="20" src="{{theme_asset('assets/img/icons/profile-icon7.png')}}"
                                 class="dark-support" alt="">
                            <span>{{translate('inbox')}}</span>
                        </a>
                    </li>
                    <li class="{{Request::is ('account-tickets') || Request::is('support-ticket*') ? 'active' : ''}}">
                        <a href="{{route('account-tickets')}}">
                            <img width="20" src="{{theme_asset('assets/img/icons/profile-icon8.png')}}"
                                 class="dark-support" alt="">
                            <span class="text-capitalize">{{translate('support_ticket')}}</span>
                        </a>
                    </li>
                    @if ($web_config['ref_earning_status'])
                        <li class="{{Request::is ('refer-earn') || Request::is('refer-earn*') ? 'active' : ''}}">
                            <a href="{{ route('refer-earn') }}">
                                <img width="20" src="{{theme_asset('assets/img/icons/refer-and-earn.svg')}}"
                                     class="dark-support" alt="">
                                <span>{{translate('refer_&_earn')}}</span>
                            </a>
                        </li>
                    @endif
                    <li class="{{Request::is ('user-coupons') || Request::is('user-coupons*') ? 'active' : ''}}">
                        <a href="{{route('user-coupons')}}">
                            <img width="20" src="{{theme_asset('assets/img/icons/coupon.svg')}}" class="dark-support"
                                 alt="">
                            <span>{{translate('coupons')}}</span>
                        </a>
                    </li>
                    <li class="d-lg-none">
                        <a class="d-flex align-items-center" href="javascript:"
                           class="delete-action"
                           data-action="{{route('account-delete',[$customer_info['id']])}}"
                           data-text="{{translate('want_to_delete_this_account').'?'}}">
                            <i class="bi bi-trash3-fill text-danger fs-16"></i>
                            {{ translate('delete_my_account') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
