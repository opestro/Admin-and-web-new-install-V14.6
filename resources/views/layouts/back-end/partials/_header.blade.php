@php
use Illuminate\Support\Facades\Session;
@endphp
@php($direction = Session::get('direction'))
<div id="headerMain" class="d-none">
    <header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container shadow">

        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper d-none d-sm-block d-xl-none">
                @php($ecommerceLogo = getWebConfig('company_web_logo'))
                <a class="navbar-brand" href="{{route('admin.dashboard.index')}}" aria-label="">
                    <img class="navbar-brand-logo"
                         src="{{getValidImage('storage/app/public/company/'.$ecommerceLogo,type: 'backend-logo')}}" alt="{{ translate('logo') }}">
                    <img class="navbar-brand-logo-mini"
                         src="{{getValidImage('storage/app/public/company/'.$ecommerceLogo,type: 'backend-logo')}}"
                         alt="{{ translate('logo') }}">
                </a>
            </div>
            <div class="navbar-nav-wrap-content-left">
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3 d-xl-none">
                    <i class="tio-first-page navbar-vertical-aside-toggle-short-align"></i>
                    <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                       data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                       data-toggle="tooltip" data-placement="right" title="Expand"></i>
                </button>
            </div>
            <div class="navbar-nav-wrap-content-right"
                 style="{{$direction == "rtl" ? 'margin-left:unset; margin-right: auto' : 'margin-right:unset; margin-left: auto'}}">
                <ul class="navbar-nav align-items-center flex-row gap-xl-16px">
                    <li class="nav-item">
                        <div class="hs-unfold">
                            <div>
                                @php( $local = session()->has('local')?session('local'):'en')
                                @php($lang = \App\Models\BusinessSetting::where('type', 'language')->first())
                                <div class="topbar-text dropdown disable-autohide {{$direction == "rtl" ? 'ml-3' : 'm-1'}} text-capitalize">
                                    <a class="topbar-link dropdown-toggle d-flex align-items-center title-color"
                                       href="javascript:" data-toggle="dropdown">
                                        @foreach(json_decode($lang['value'],true) as $data)
                                            @if($data['code']==$local)
                                                <img class="{{$direction == "rtl" ? 'ml-2' : 'mr-2'}}" width="20"
                                                    src="{{dynamicAsset(path: 'public/assets/front-end/img/flags/'.$data['code'].'.png')}}"
                                                    alt="{{$data['name']}}">
                                                <span class="d-none d-sm-block">{{$data['name']}}</span>
                                                <span class="d-sm-none">{{$data['code']}}</span>
                                            @endif
                                        @endforeach
                                    </a>
                                    <ul class="dropdown-menu position-absolute">
                                        @foreach(json_decode($lang['value'],true) as $key =>$data)
                                            @if($data['status']==1)
                                                <li class="change-language" data-action="{{route('change-language')}}" data-language-code="{{$data['code']}}">
                                                    <a class="dropdown-item py-1 {{$data['code']==$local ? 'active' : ':'}}"
                                                       href="javascript:">
                                                        <img class="{{$direction == "rtl" ? 'ml-2' : 'mr-2'}}" width="20"
                                                                src="{{dynamicAsset(path: 'public/assets/front-end/img/flags/'.$data['code'].'.png')}}"
                                                                alt="{{$data['name']}}"/>
                                                        <span class="text-capitalize">{{$data['name']}}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item">
                        <div class="hs-unfold">
                            <a title="Website home"
                               class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                               href="{{route('home')}}" target="_blank" data-toggle="tooltip" data-custom-class="header-icon-title">
                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.3663 6.75C12.7069 3.6875 11.3007 1.75 10 1.75C8.69941 1.75 7.29316 3.6875 6.63379 6.75H13.3663Z" fill="#073B74"/>
                                    <path d="M6.25 10.5C6.24985 11.3361 6.3056 12.1713 6.41688 13H13.5831C13.6944 12.1713 13.7502 11.3361 13.75 10.5C13.7502 9.66388 13.6944 8.82868 13.5831 8H6.41688C6.3056 8.82868 6.24985 9.66388 6.25 10.5Z" fill="#073B74"/>
                                    <path d="M6.63379 14.25C7.29316 17.3125 8.69941 19.25 10 19.25C11.3007 19.25 12.7069 17.3125 13.3663 14.25H6.63379Z" fill="#073B74"/>
                                    <path d="M14.6462 6.74965H18.5837C17.9921 5.40325 17.0932 4.21424 15.9591 3.27798C14.8249 2.34173 13.4872 1.68429 12.0531 1.3584C13.2387 2.40152 14.1687 4.33027 14.6462 6.74965Z" fill="#073B74"/>
                                    <path d="M19.0331 8H14.8456C14.9487 8.82934 15.0003 9.66428 15 10.5C15.0001 11.3357 14.9483 12.1707 14.845 13H19.0325C19.4883 11.3645 19.4889 9.6355 19.0331 8Z" fill="#073B74"/>
                                    <path d="M12.0531 19.6412C13.4874 19.3155 14.8254 18.6582 15.9598 17.7219C17.0941 16.7856 17.9932 15.5965 18.585 14.25H14.6475C14.1687 16.6694 13.2387 18.5981 12.0531 19.6412Z" fill="#073B74"/>
                                    <path d="M5.35376 14.25H1.41626C2.008 15.5965 2.90712 16.7856 4.04147 17.7219C5.17582 18.6582 6.51382 19.3155 7.94813 19.6412C6.76126 18.5981 5.83126 16.6694 5.35376 14.25Z" fill="#073B74"/>
                                    <path d="M7.94691 1.3584C6.5126 1.68411 5.1746 2.34147 4.04025 3.27774C2.9059 4.214 2.00678 5.40311 1.41504 6.74965H5.35254C5.83129 4.33027 6.76129 2.40152 7.94691 1.3584Z" fill="#073B74"/>
                                    <path d="M4.99996 10.5C4.99987 9.66426 5.05164 8.82933 5.15495 8H0.967455C0.511662 9.6355 0.511662 11.3645 0.967455 13H5.15495C5.05164 12.1707 4.99987 11.3357 4.99996 10.5Z" fill="#073B74"/>
                                </svg>
                            </a>
                        </div>
                    </li>

                    @if(\App\Utils\Helpers::module_permission_check('support_section'))
                        <li class="nav-item">
                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                   href="{{route('admin.contact.list')}}" title="{{translate('message')}}" data-toggle="tooltip" data-custom-class="header-icon-title">
                                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_5926_1152)">
                                        <path d="M16.6666 2.16699H3.33329C2.41663 2.16699 1.67496 2.91699 1.67496 3.83366L1.66663 18.8337L4.99996 15.5003H16.6666C17.5833 15.5003 18.3333 14.7503 18.3333 13.8337V3.83366C18.3333 2.91699 17.5833 2.16699 16.6666 2.16699ZM4.99996 8.00033H15V9.66699H4.99996V8.00033ZM11.6666 12.167H4.99996V10.5003H11.6666V12.167ZM15 7.16699H4.99996V5.50033H15V7.16699Z" fill="#073B74"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_5926_1152">
                                        <rect width="20" height="20" fill="white" transform="translate(0 0.5)"/>
                                        </clipPath>
                                        </defs>
                                    </svg>

                                    @php($message=\App\Models\Contact::where('seen',0)->count())
                                    @if($message!=0)
                                        <span class="btn-status btn-sm-status btn-status-danger">{{ $message }}</span>
                                    @endif
                                </a>
                            </div>
                        </li>
                    @endif

                    @if(\App\Utils\Helpers::module_permission_check('order_management'))
                        <li class="nav-item">
                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                                   href="{{route('admin.orders.list',['status'=>'pending'])}}" title="{{translate('pending_Orders')}}" data-toggle="tooltip" data-custom-class="header-icon-title">
                                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_5926_1157)">
                                        <path d="M15.148 15.1201C13.8635 15.1189 12.8212 16.1592 12.8199 17.4437C12.8187 18.7282 13.859 19.7705 15.1435 19.7717C16.428 19.773 17.4703 18.7327 17.4716 17.4482C17.4716 17.4474 17.4716 17.4467 17.4716 17.4459C17.4703 16.1628 16.4311 15.1226 15.148 15.1201Z" fill="#073B74"/>
                                        <path d="M19.2731 3.98349C19.2175 3.97271 19.161 3.96724 19.1043 3.96715H4.94317L4.71889 2.4667C4.57915 1.47022 3.7268 0.728822 2.72055 0.728516H0.897126C0.401648 0.728516 0 1.13016 0 1.62564C0 2.12112 0.401648 2.52277 0.897126 2.52277H2.72279C2.83685 2.52194 2.9334 2.60687 2.94707 2.72015L4.32863 12.1893C4.51805 13.3925 5.55303 14.2802 6.77107 14.2841H16.1034C17.2761 14.2856 18.2878 13.4614 18.5234 12.3127L19.9835 5.03472C20.0776 4.54827 19.7596 4.07763 19.2731 3.98349Z" fill="#073B74"/>
                                        <path d="M9.45041 17.3461C9.39578 16.0992 8.3668 15.1177 7.11875 15.1221C5.83531 15.1739 4.83691 16.2565 4.88877 17.5399C4.93854 18.7714 5.94031 19.7502 7.17259 19.7715H7.22866C8.51193 19.7152 9.50661 18.6293 9.45041 17.3461Z" fill="#073B74"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_5926_1157">
                                        <rect width="20" height="20" fill="white" transform="translate(0 0.25)"/>
                                        </clipPath>
                                        </defs>
                                    </svg>
                                    <span class="btn-status btn-sm-status btn-status-danger">{{\App\Models\Order::where('order_status','pending')->count()}}</span>
                                </a>
                            </div>
                        </li>
                    @endif

                    <li class="nav-item">
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker media align-items-center gap-3 navbar-dropdown-account-wrapper dropdown-toggle dropdown-toggle-left-arrow"
                               href="javascript:"
                               data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                                <div class="d-none d-md-block media-body text-right">
                                    <h5 class="profile-name mb-0">{{auth('admin')->user()->name}}</h5>
                                    <span class="fz-12">{{ auth('admin')->user()->role->name ?? '' }}</span>
                                </div>
                                <div class="avatar border avatar-circle">
                                    <img class="avatar-img"
                                         src="{{getValidImage('storage/app/public/admin/'.auth('admin')->user()->image,type: 'backend-profile')}}"
                                         alt="{{translate('image_description')}}">
                                    <span class="d-none avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            </a>
                            <div id="accountNavbarDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account">
                                <div class="dropdown-item-text">
                                    <div class="media align-items-center text-break">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            <img class="avatar-img" src="{{getValidImage('storage/app/public/admin/'.auth('admin')->user()->image,type: 'backend-profile')}}"
                                                 alt="{{translate('image_description')}}">
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5">{{auth('admin')->user()->name}}</span>
                                            <span class="card-text">{{auth('admin')->user()->email}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"
                                   href="{{route('admin.profile.update',auth('admin')->user()->id)}}">
                                    <span class="text-truncate pr-2" title="Settings">{{ translate('settings')}}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:" data-toggle="modal"  data-target="#sign-out-modal">
                                    <span class="text-truncate pr-2" title="{{translate('logout')}}">{{translate('logout')}}</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div id="website_info" class="bg-secondary w-100 d-none">
            <div class="p-3">
                <div class="bg-white p-1 rounded">
                    @php( $local = session()->has('local')?session('local'):'en')
                    <div class="topbar-text dropdown disable-autohide {{$direction == "rtl" ? 'ml-3' : 'm-1'}} text-capitalize">
                        <a class="topbar-link dropdown-toggle title-color d-flex align-items-center" href="#"
                           data-toggle="dropdown">
                            @foreach(json_decode($lang['value'],true) as $data)
                                @if($data['code']==$local)
                                    <img class="{{$direction == "rtl" ? 'ml-2' : 'mr-2'}}" width="20"
                                         src="{{dynamicAsset(path: 'public/assets/front-end/img/flags/'.$data['code'].'.png')}}"
                                         alt="{{$data['name']}}">
                                    {{$data['name']}}
                                @endif
                            @endforeach
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(json_decode($lang['value'],true) as $key =>$data)
                                @if($data['status']==1)
                                    <li class="change-language" data-action="{{route('change-language')}}" data-language-code="{{$data['code']}}">
                                        <a class="dropdown-item pb-1" href="javascript:">
                                            <img class="{{$direction == "rtl" ? 'ml-2' : 'mr-2'}}" width="20"
                                                 src="{{dynamicAsset(path: 'public/assets/front-end/img/flags/'.$data['code'].'.png')}}"
                                                    alt="{{$data['name']}}"/>
                                            <span class="text-capitalize">{{$data['name']}}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="bg-white p-1 rounded mt-2">
                    <a title="Website home" class="p-2 title-color"
                       href="{{route('home')}}" target="_blank">
                        <i class="tio-globe"></i>
                        {{translate('view_website')}}
                    </a>
                </div>
                @if(\App\Utils\Helpers::module_permission_check('support_section'))
                    <div class="bg-white p-1 rounded mt-2">
                        <a class="p-2  title-color"
                           href="{{route('admin.contact.list')}}">
                            <i class="tio-email"></i>
                            {{translate('message')}}
                            @php($message=\App\Models\Contact::where('seen',0)->count())
                            @if($message!=0)
                                <span>({{ $message }})</span>
                            @endif
                        </a>
                    </div>
                @endif
                @if(\App\Utils\Helpers::module_permission_check('order_management'))
                    <div class="bg-white p-1 rounded mt-2">
                        <a class="p-2  title-color"
                           href="{{route('admin.orders.list',['status'=>'pending'])}}">
                            <i class="tio-shopping-cart-outlined"></i>
                            {{translate('order_list')}}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>
