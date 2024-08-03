@extends('layouts.front-end.app')

@section('title')
    {{ Request::is('chat/vendor') ? translate('chat_with_vendor') : translate('chat_with_delivery_man')}}
@endsection

@section('content')
    <div class="__chat-seller">
        <div class="container py-4 rtl text-align-direction">
            <div class="row">
                @include('web-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3 d-lg-none">
                        <h5 class="font-bold m-0 fs-16">{{translate('inbox')}}</h5>

                        <button class="profile-aside-btn btn btn--primary px-2 rounded px-2 py-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7 9.81219C7 9.41419 6.842 9.03269 6.5605 8.75169C6.2795 8.47019 5.898 8.31219 5.5 8.31219C4.507 8.31219 2.993 8.31219 2 8.31219C1.602 8.31219 1.2205 8.47019 0.939499 8.75169C0.657999 9.03269 0.5 9.41419 0.5 9.81219V13.3122C0.5 13.7102 0.657999 14.0917 0.939499 14.3727C1.2205 14.6542 1.602 14.8122 2 14.8122H5.5C5.898 14.8122 6.2795 14.6542 6.5605 14.3727C6.842 14.0917 7 13.7102 7 13.3122V9.81219ZM14.5 9.81219C14.5 9.41419 14.342 9.03269 14.0605 8.75169C13.7795 8.47019 13.398 8.31219 13 8.31219C12.007 8.31219 10.493 8.31219 9.5 8.31219C9.102 8.31219 8.7205 8.47019 8.4395 8.75169C8.158 9.03269 8 9.41419 8 9.81219V13.3122C8 13.7102 8.158 14.0917 8.4395 14.3727C8.7205 14.6542 9.102 14.8122 9.5 14.8122H13C13.398 14.8122 13.7795 14.6542 14.0605 14.3727C14.342 14.0917 14.5 13.7102 14.5 13.3122V9.81219ZM12.3105 7.20869L14.3965 5.12269C14.982 4.53719 14.982 3.58719 14.3965 3.00169L12.3105 0.915687C11.725 0.330188 10.775 0.330188 10.1895 0.915687L8.1035 3.00169C7.518 3.58719 7.518 4.53719 8.1035 5.12269L10.1895 7.20869C10.775 7.79419 11.725 7.79419 12.3105 7.20869ZM7 2.31219C7 1.91419 6.842 1.53269 6.5605 1.25169C6.2795 0.970186 5.898 0.812187 5.5 0.812187C4.507 0.812187 2.993 0.812187 2 0.812187C1.602 0.812187 1.2205 0.970186 0.939499 1.25169C0.657999 1.53269 0.5 1.91419 0.5 2.31219V5.81219C0.5 6.21019 0.657999 6.59169 0.939499 6.87269C1.2205 7.15419 1.602 7.31219 2 7.31219H5.5C5.898 7.31219 6.2795 7.15419 6.5605 6.87269C6.842 6.59169 7 6.21019 7 5.81219V2.31219Z" fill="white"/>
                            </svg>
                        </button>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-lg-4 chatSel">
                            <div class="chat--sidebar-card h-100">
                                <div class="chat--sidebar-top">
                                    <ul class="nav nav-tabs nav--tabs justify-content-center">
                                        @php($business_mode = getWebConfig(name: 'business_mode'))
                                        @if($business_mode == 'multi')
                                            <li class="nav-item">
                                                <a class="nav-link {{Request::is('chat/vendor')?'active': '' }}" href="{{route('chat', ['type' => 'vendor'])}}">
                                                    {{translate('vendor')}}
                                                </a>
                                            </li>
                                        @endif
                                        <li class="nav-item">
                                            <a class="nav-link {{Request::is('chat/delivery-man')?'active': '' }}" href="{{route('chat', ['type' => 'delivery-man'])}}">
                                                {{translate('deliveryman')}}
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="heading_search px-0">
                                        <form class="rounded bg-white form-inline d-flex justify-content-center md-form form-sm active-cyan-2 mt-2">
                                            <input class="form-control form-control-sm border-0 me-3 w--0"
                                                   id="myInput" type="text" placeholder="{{translate('search')}}" aria-label="Search">
                                            <span class="px-2"><i class="fa fa-search __color-92C6FF" aria-hidden="true"></i></span>
                                        </form>
                                    </div>
                                </div>
                                <div class="inbox_chat">
                                    @if(isset($allChattingUsers) && count($allChattingUsers) > 0)
                                        @foreach($allChattingUsers as $key => $chatting)
                                            @php($userId = !is_null($chatting->admin_id) ? 0 : ($chatting?->seller_id ?? $chatting?->delivery_man_id) )
                                            @php($seenMessage = !is_null($chatting->admin_id) ? $chatting->sent_by_admin :  ($chatting?->seller_id ? $chatting->sent_by_seller : $chatting->sent_by_delivery_man) )
                                            @php($userName = !is_null($chatting->admin_id) ? getWebConfig('company_name') :  ($chatting?->seller_id ? $chatting?->seller?->shop->name : $chatting?->deliveryMan?->f_name ))
                                            <div class="list_filter chat_list {{ $loop->iteration == 1 ? 'active' : ''}} get-ajax-message-view"
                                                 data-user-id="{{ $userId }}">
                                                <div class="chat_people">
                                                    <div class="chat_img">
                                                        @if($chatting->delivery_man_id)
                                                            <img alt="" class="__inline-14 __rounded-10 img-profile"
                                                                 src="{{ getStorageImages(path: $chatting?->deliveryMan->image_full_url, type: 'avatar') }}">
                                                        @elseif($chatting?->seller_id)
                                                            <img alt="" class="__inline-14 __rounded-10 img-profile"
                                                                 src="{{ getStorageImages(path: $chatting?->seller?->shop->image_full_url, type: 'shop') }}">
                                                        @else
                                                            <img alt="" class="__inline-14 __rounded-10 img-profile"
                                                                 src="{{ getStorageImages(path: $web_config['fav_icon'], type: 'shop') }}">
                                                        @endif
                                                    </div>
                                                    <div class="chat_ib">
                                                        <div>
                                                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                                                                <h5 class="{{$seenMessage == 0 ? 'active-text' : ''}}">{{$userName}}</h5>
                                                                <span class="date">
                                                                {{$chatting->created_at->diffForHumans()}}
                                                            </span>
                                                            </div>

                                                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                                                @if($chatting->message)
                                                                    <span class="last-msg">{{ $chatting->message }}</span>
                                                                @elseif(json_decode($chatting['attachment'], true) !=null)
                                                                    <span class="last-msg">
                                                                        <i class="fa fa-paperclip pe-1"></i>
                                                                        {{ translate('sent_attachments') }}
                                                                    </span>
                                                                @endif
                                                                @if($chatting->unseen_message_count >0)
                                                                    <span class="new-msg badge btn--primary rounded-full">
                                                                        {{ $chatting->unseen_message_count }}
                                                                    </span>
                                                               @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endForeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <section class="col-lg-8">
                            @if(isset($chattingMessages) && count($chattingMessages) > 0)
                                <div class="bg-white Chat __shadow h-100 rounded-left-0">
                                    <div class="messaging ">
                                        <div class="inbox_msg position-relative">
                                            <div class="mesgs">
                                                <a class="msg-user" href="#">
                                                    @if($userType == 'admin')
                                                        <img alt="" class="img profile-image" src="{{ getStorageImages(path: $web_config['fav_icon'], type: 'shop') }}">
                                                    @else
                                                        <img alt="" class="img profile-image" src="{{ getStorageImages(path: $lastChatUser['image_full_url'], type: ($userType == 'vendor' ? 'shop':'avatar')) }}">
                                                    @endif
                                                    <h5 class="m-0 profile-name">{{ $userType == 'admin' ? getWebConfig('company_name') : ($userType == 'vendor' ? $lastChatUser->name : ($lastChatUser?->f_name.' '.$lastChatUser?->l_name))  }}</h5>
                                                </a>
                                                <div class=" msg_history d-flex flex-column-reverse" id="chatting-messages-section">
                                                    @include('web-views.users-profile.inbox.messages')
                                                </div>
                                                <div class="type_msg">
                                                    <div class="input_msg_write">
                                                        <form class="d-flex justify-content-center align-items-center md-form form-sm active-cyan-2 chatting-messages-form" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="d-flex align-items-center m-0 px-3 gap-2 pt-3 align-self-start">
                                                                <label class="py-0 d-flex align-items-center m-0 cursor-pointer">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                                        <path d="M18.1029 1.83203H3.89453C2.75786 1.83203 1.83203 2.75786 1.83203 3.89453V18.1029C1.83203 19.2395 2.75786 20.1654 3.89453 20.1654H18.1029C19.2395 20.1654 20.1654 19.2395 20.1654 18.1029V3.89453C20.1654 2.75786 19.2395 1.83203 18.1029 1.83203ZM3.89453 3.20703H18.1029C18.4814 3.20703 18.7904 3.51595 18.7904 3.89453V12.7642L15.2539 9.2277C15.1255 9.09936 14.9514 9.02603 14.768 9.02603H14.7653C14.5819 9.02603 14.405 9.09936 14.2776 9.23136L10.3204 13.25L8.65845 11.5945C8.53011 11.4662 8.35595 11.3929 8.17261 11.3929C7.9957 11.3654 7.81053 11.4662 7.6822 11.6009L3.20703 16.1705V3.89453C3.20703 3.51595 3.51595 3.20703 3.89453 3.20703ZM3.21253 18.1304L8.17903 13.0575L13.9375 18.7904H3.89453C3.52603 18.7904 3.22811 18.4952 3.21253 18.1304ZM18.1029 18.7904H15.8845L11.2948 14.2189L14.7708 10.6898L18.7904 14.7084V18.1029C18.7904 18.4814 18.4814 18.7904 18.1029 18.7904Z" fill="#1455AC"/>
                                                                        <path d="M8.12834 9.03012C8.909 9.03012 9.54184 8.39728 9.54184 7.61662C9.54184 6.83597 8.909 6.20312 8.12834 6.20312C7.34769 6.20312 6.71484 6.83597 6.71484 7.61662C6.71484 8.39728 7.34769 9.03012 8.12834 9.03012Z" fill="#1455AC"/>
                                                                    </svg>
                                                                    <input type="file" id="select-image" class="h-100 position-absolute w-100 " hidden multiple accept="image/*">
                                                                </label>
                                                                <label class="py-0 m-0 cursor-pointer">
                                                                    <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M5.61597 17.2917C4.66813 17.2919 3.7415 17.011 2.95335 16.4845C2.16519 15.958 1.55092 15.2096 1.18827 14.3338C0.825613 13.4581 0.730874 12.4945 0.916037 11.5649C1.1012 10.6353 1.55794 9.78158 2.22847 9.11165L9.2993 2.03999C9.41655 1.92274 9.57557 1.85687 9.74139 1.85687C9.9072 1.85687 10.0662 1.92274 10.1835 2.03999C10.3007 2.15724 10.3666 2.31626 10.3666 2.48207C10.3666 2.64788 10.3007 2.80691 10.1835 2.92415L3.11181 9.99499C2.76945 10.3208 2.49576 10.7118 2.30686 11.145C2.11796 11.5782 2.01768 12.0449 2.01193 12.5175C2.00617 12.99 2.09506 13.459 2.27334 13.8967C2.45163 14.3344 2.71572 14.7319 3.05004 15.066C3.38436 15.4 3.78216 15.6638 4.21999 15.8417C4.65783 16.0196 5.12685 16.1081 5.59941 16.102C6.07198 16.0958 6.53854 15.9951 6.9716 15.8059C7.40465 15.6166 7.79545 15.3426 8.12097 15L17.2543 5.86665C17.6728 5.43446 17.9047 4.85506 17.8999 4.25344C17.895 3.65183 17.6539 3.07623 17.2285 2.65081C16.8031 2.22539 16.2275 1.98425 15.6258 1.97942C15.0242 1.97459 14.4448 2.20645 14.0126 2.62499L6.64764 9.99499C6.45226 10.1904 6.3425 10.4554 6.3425 10.7317C6.3425 11.008 6.45226 11.2729 6.64764 11.4683C6.84301 11.6637 7.108 11.7735 7.3843 11.7735C7.66061 11.7735 7.9256 11.6637 8.12097 11.4683L12.8335 6.75499C12.8911 6.69527 12.96 6.64762 13.0363 6.61483C13.1125 6.58204 13.1945 6.56476 13.2775 6.564C13.3605 6.56324 13.4428 6.57901 13.5196 6.6104C13.5964 6.64179 13.6663 6.68817 13.725 6.74682C13.7837 6.80548 13.8301 6.87524 13.8616 6.95203C13.893 7.02883 13.9089 7.11112 13.9082 7.19411C13.9075 7.27709 13.8903 7.35911 13.8576 7.43538C13.8249 7.51165 13.7773 7.58064 13.7176 7.63832L9.0043 12.3525C8.57454 12.7824 7.99162 13.0239 7.38377 13.024C6.77591 13.0241 6.19293 12.7827 5.76305 12.3529C5.33318 11.9231 5.09164 11.3402 5.09156 10.7324C5.09148 10.1245 5.33288 9.54153 5.76264 9.11165L13.1293 1.74999C13.7935 1.08573 14.6943 0.712511 15.6336 0.712433C16.5729 0.712355 17.4738 1.08542 18.1381 1.74957C18.8023 2.41372 19.1755 3.31454 19.1756 4.25386C19.1757 5.19318 18.8026 6.09406 18.1385 6.75832L9.00514 15.8883C8.56103 16.3347 8.03283 16.6885 7.45109 16.9294C6.86934 17.1703 6.24561 17.2934 5.61597 17.2917Z" fill="#46A046"/>
                                                                    </svg>
                                                                    <input type="file" id="select-file" class="h-100 position-absolute w-100 " hidden multiple
                                                                           accept=".doc, .docx, .pdf, .zip, .gif, .txt, .csv, .xls, .xlsx, .rar, .tar, .targz, .zip, .pdf"">
                                                                </label>
                                                                <label class="py-0 m-0 cursor-pointer" id="trigger">
                                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_7224_10484)">
                                                                            <path d="M10 20C8.02219 20 6.08879 19.4135 4.4443 18.3147C2.79981 17.2159 1.51809 15.6541 0.761209 13.8268C0.00433286 11.9996 -0.193701 9.98891 0.192152 8.0491C0.578004 6.10929 1.53041 4.32746 2.92894 2.92894C4.32746 1.53041 6.10929 0.578004 8.0491 0.192152C9.98891 -0.193701 11.9996 0.00433286 13.8268 0.761209C15.6541 1.51809 17.2159 2.79981 18.3147 4.4443C19.4135 6.08879 20 8.02219 20 10C19.9971 12.6513 18.9426 15.1932 17.0679 17.0679C15.1932 18.9426 12.6513 19.9971 10 20ZM10 1.66667C8.35183 1.66667 6.74066 2.15541 5.37025 3.07109C3.99984 3.98677 2.93174 5.28826 2.30101 6.81098C1.67028 8.33369 1.50525 10.0092 1.82679 11.6258C2.14834 13.2423 2.94201 14.7271 4.10745 15.8926C5.27289 17.058 6.75774 17.8517 8.37425 18.1732C9.99076 18.4948 11.6663 18.3297 13.189 17.699C14.7118 17.0683 16.0132 16.0002 16.9289 14.6298C17.8446 13.2593 18.3333 11.6482 18.3333 10C18.3309 7.79061 17.4522 5.67241 15.8899 4.11013C14.3276 2.54785 12.2094 1.6691 10 1.66667ZM14.7217 13.1217C14.8868 12.9747 14.9867 12.7682 14.9995 12.5475C15.0123 12.3268 14.937 12.1101 14.79 11.945C14.643 11.7799 14.4365 11.68 14.2158 11.6671C13.9952 11.6543 13.7784 11.7297 13.6133 11.8767C12.5946 12.7344 11.3288 13.2447 10 13.3333C8.67202 13.2448 7.40686 12.7351 6.38834 11.8783C6.22346 11.7311 6.00687 11.6555 5.7862 11.668C5.56553 11.6805 5.35887 11.7801 5.21167 11.945C5.06448 12.1099 4.98881 12.3265 5.00131 12.5471C5.01381 12.7678 5.11346 12.9745 5.27834 13.1217C6.60156 14.2521 8.26185 14.9126 10 15C11.7382 14.9126 13.3984 14.2521 14.7217 13.1217ZM5 8.33334C5 9.16667 5.74584 9.16667 6.66667 9.16667C7.5875 9.16667 8.33334 9.16667 8.33334 8.33334C8.33334 7.89131 8.15774 7.46739 7.84518 7.15483C7.53262 6.84227 7.1087 6.66667 6.66667 6.66667C6.22464 6.66667 5.80072 6.84227 5.48816 7.15483C5.1756 7.46739 5 7.89131 5 8.33334ZM11.6667 8.33334C11.6667 9.16667 12.4125 9.16667 13.3333 9.16667C14.2542 9.16667 15 9.16667 15 8.33334C15 7.89131 14.8244 7.46739 14.5118 7.15483C14.1993 6.84227 13.7754 6.66667 13.3333 6.66667C12.8913 6.66667 12.4674 6.84227 12.1548 7.15483C11.8423 7.46739 11.6667 7.89131 11.6667 8.33334Z" fill="#F9BD23"/>
                                                                        </g>
                                                                        <defs>
                                                                            <clipPath id="clip0_7224_10484">
                                                                                <rect width="20" height="20" fill="white"/>
                                                                            </clipPath>
                                                                        </defs>
                                                                    </svg>
                                                                </label>
                                                            </div>
                                                            <div class="position-relative d-flex w-100">
                                                                <div class="w-0 flex-grow-1">
                                                                    <textarea class="form-control ticket-view-control px-0 py-3 h-70px" name="message" rows="8" id="msgInputValue" placeholder="{{translate('write_your_message_here')}}..." ></textarea>
                                                                    <div>
                                                                        <div class="overflow-x-auto pt-3 pb-2">
                                                                            <div>
                                                                                <div class="d-flex gap-3 align-items-center">
                                                                                    <div class="d-flex gap-3 image-array"></div>
                                                                                    <div class="d-flex gap-3 file-array"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div id="selected-files-container"></div>
                                                                            <div id="selected-image-container"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button type="submit" id="msgSendBtn" class="aSend no-gutter py-0 px-3 m-0 border-0 bg-transparent">
                                                                    <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <rect width="31" height="31" rx="6" fill="currentColor"/>
                                                                        <path d="M21.2267 15.5548L10.2267 10.0548C10.1404 10.0116 10.0436 9.99437 9.94779 10.005C9.85198 10.0157 9.7613 10.0538 9.68665 10.1148C9.61536 10.1745 9.56215 10.253 9.53301 10.3413C9.50386 10.4296 9.49993 10.5243 9.52165 10.6148L10.8467 15.4998H16.5017V16.4998H10.8467L9.50165 21.3698C9.48126 21.4453 9.47888 21.5245 9.4947 21.6012C9.51052 21.6778 9.5441 21.7496 9.59273 21.8109C9.64136 21.8722 9.7037 21.9212 9.77472 21.954C9.84574 21.9868 9.92347 22.0025 10.0017 21.9998C10.0799 21.9993 10.157 21.9805 10.2267 21.9448L21.2267 16.4448C21.3086 16.4028 21.3773 16.3391 21.4253 16.2605C21.4733 16.182 21.4987 16.0918 21.4987 15.9998C21.4987 15.9077 21.4733 15.8175 21.4253 15.739C21.3773 15.6605 21.3086 15.5967 21.2267 15.5548Z" fill="white"/>
                                                                    </svg>
                                                                </button>
                                                                <div class="circle-progress ml-auto collapse">
                                                                    <div class="inner">
                                                                        <div class="text"></div>
                                                                        <svg id="svg" width="24" height="24" viewPort="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                                            <circle id="bar" r="10" cx="12" cy="12" fill="transparent" stroke-dasharray="100" stroke-dashoffset="100"></circle>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="temporarily-closed-sticky-alert {{$userData['temporary-close-status'] == 1 ? '' : 'd-none'}}">
                                                <div class="alert-box">
                                                    <div><img src="{{ theme_asset('public/assets/front-end/img/icons/warning.svg') }}" alt=""></div>
                                                    <div>
                                                        {{ translate('sorry') }} !
                                                        {{ translate('currently_we_are_not_available.') }}
                                                        {{ translate('but_you_can_ask_or_still_message_us.') }}
                                                        {{ translate('We_will_get_back_to_you_soon.') }}
                                                        {{ translate('Thank_you_for_your_patience.') }}.
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <button type="button" class="close close-element-onclick-by-data" aria-label="Close" data-selector=".temporarily-closed-sticky-alert">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card Chat __shadow h-100 d-flex flex-column justify-content-center rounded-left-0">
                                    <div class="text-center">
                                        <img src="{{theme_asset(path: 'public/assets/front-end/img/empt-msg.png')}}" alt="">
                                        <p class="text-body mt-4">
                                            {{translate('you_haven’t_any_conversation_yet')}}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="chatting-post-url" data-url="{{ Request::is('chat/vendor') ? route('messages').'?vendor_id=' : route('messages').'?delivery_man_id=' }}"></span>
    <span id="get-file-icon" data-default-icon="{{dynamicAsset("public/assets/back-end/img/default-icon.png")}}"
          data-word-icon="{{dynamicAsset("public/assets/back-end/img/default-icon.png")}}"></span>
@endsection

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/chatting.js')}}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/picmo-emoji.js')}}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/emoji.js')}}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/select-multiple-file.js')}}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/select-multiple-image-for-message.js')}}"></script>
@endpush

