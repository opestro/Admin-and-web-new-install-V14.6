@extends('theme-views.layouts.app')

@section('title')
    {{ Request::is('chat/vendor') ? translate('chat_with_vendor') : translate('chat_with_delivery_man')}}
@endsection

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100 mb-3 border-0">
                        <div class="flexible-grid md-down-1 h-100 width--15-625">
                            <div class="bg-light h-100">
                                <div class="p-3">
                                    <h4 class="mb-3">{{translate('messages')}}</h4>
                                    <form action="#" class="mb-3">
                                        <div class="search-bar style--two">
                                            <button type="submit">
                                                <i class="bi bi-search"></i>
                                            </button>
                                            <input type="search" class="form-control" id="search-value" autocomplete="off"
                                                   placeholder="{{translate('search').'...'}}">
                                        </div>
                                    </form>

                                    <ul class="nav nav--tabs gap-3">
                                        <li class="nav-item" role="presentation">
                                            <a class="{{Request::is('chat/vendor')?'active':''}}"
                                               href="{{route('chat', ['type' => 'vendor'])}}">{{translate('vendor')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="{{Request::is('chat/delivery-man')?'active':''}}"
                                               href="{{route('chat', ['type' => 'delivery-man'])}}">{{translate('delivery_man')}}</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content p-2 pt-0">
                                    <div class="tab-pane fade show active" id="seller-tab-pane" role="tabpanel"
                                         aria-labelledby="seller-tab" tabindex="0">
                                        <div class="chat-list custom-scrollbar ">
                                            @if(isset($allChattingUsers) && count($allChattingUsers) > 0)
                                                @foreach($allChattingUsers as $key => $chatting)
                                                    @php($userId = !is_null($chatting->admin_id) ? 0 : ($chatting?->seller_id ?? $chatting?->delivery_man_id) )
                                                    @php($seenMessage = !is_null($chatting->admin_id) ? $chatting->sent_by_admin :  ($chatting?->seller_id ? $chatting->sent_by_seller : $chatting->sent_by_delivery_man) )
                                                    @php($userName = !is_null($chatting->admin_id) ? getWebConfig('company_name') :  ($chatting?->seller_id ? $chatting?->seller?->shop->name : $chatting?->deliveryMan?->f_name ))
                                                    <div class="chat-list chat-list-item {{ $loop->iteration == 1 ? 'active' : ''}} get-ajax-message-view media gap-2 align-items-center"
                                                         data-user-id="{{ $userId }}">
                                                        <div class="avatar rounded-circle ">
                                                            @if($chatting->delivery_man_id)
                                                                <img alt="" class="img-fit aspect-1 rounded-circle dark-support" loading="lazy"
                                                                     src="{{ getStorageImages(path: $chatting?->deliveryMan->image_full_url, type: 'avatar') }}">
                                                            @elseif($chatting?->seller_id)
                                                                <img alt="" class="img-fit aspect-1 rounded-circle dark-support" loading="lazy"
                                                                     src="{{ getStorageImages(path: $chatting?->seller?->shop->image_full_url, type: 'shop') }}">
                                                            @else
                                                                <img alt="" class="img-fit aspect-1 rounded-circle dark-support" loading="lazy"
                                                                     src="{{ getStorageImages(path: $web_config['fav_icon'], type: 'shop') }}">
                                                            @endif
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="chat-people-name gap-2 align-items-center mb-1">
                                                                <div
                                                                    class="text-truncate d-flex align-items-center gap-1 width--100">
                                                                    <h6 class="fs-12 seller">{{$userName}}</h6>
                                                                    <div class="fs-12 text-muted"></div>
                                                                </div>

                                                                <div class="fs-10">
                                                                    {{$chatting->created_at->diffForHumans()}}
                                                                </div>
                                                            </div>
                                                            <p class="fs-10 d-flex align-items-center justify-content-between">
                                                                @if($chatting->message)
                                                                    <span>{{ $chatting->message }}</span>
                                                                @elseif(json_decode($chatting['attachment'], true) !=null)
                                                                    <span>
                                                                    <i class="bi bi-paperclip"></i>
                                                                    {{ translate('sent_attachments') }}
                                                                </span>
                                                                @endif
                                                                @if($chatting['unseen_message_count'] > 0)
                                                                    <span class="chatting-count-badge">{{ $chatting['unseen_message_count'] }}</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative">
                                @if(isset($chattingMessages) && count($chattingMessages) > 0)
                                    <div class="border-bottom px-3 py-2">
                                        <div class="media gap-2 align-items-center">
                                            <div class="avatar rounded-circle">
                                                <img alt="" class="img-fit aspect-1 rounded-circle dark-support profile-image" loading="lazy" src="{{ getStorageImages(path:($userType == 'admin' ? $web_config['fav_icon'] : $lastChatUser->image_full_url), type: 'avatar') }}">
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-column gap-1">
                                                    <h6 id="name" class="profile-name">{{ $userType == 'admin' ? getWebConfig('company_name') : ($userType == 'vendor' ? $lastChatUser->name : ($lastChatUser?->f_name.' '.$lastChatUser?->l_name))  }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="messaging">
                                        <div style="--height: 54dvh" class="inbox_msg custom-scrollbar p-3 message-history height-480px scroll_msg" id="chatting-messages-section">
                                            @include('theme-views.users-profile.inbox.messages')
                                        </div>
                                        <div class="type_msg px-2">
                                            <form method="post" class="mt-4 chatting-messages-form" enctype="multipart/form-data">
                                                @csrf
                                                <div class="d-flex border">
                                                    <div class="d-flex align-items-center m-0 gap-2 align-self-start px-3 pt-2">
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
                                                                   accept=".doc, .docx, .pdf, .zip, .gif, .txt, .csv, .xls, .xlsx, .rar, .tar, .targz, .zip, .pdf">
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
                                                    <div class="position-relative w-0 flex-grow-1">
                                                        <textarea class="w-100 focus-input border-0 resize-none pt-2" name="message" id="msgInputValue"
                                                                  placeholder="{{translate('start_a_new_message')}}"></textarea>
                                                        <div class="overflow-x-auto pb-2 pt-1">
                                                            <div>
                                                                <div class="d-flex gap-3 align-items-center">
                                                                    <div class="d-flex gap-3 image-array"></div>
                                                                    <div class="d-flex gap-3 file-array"></div>
                                                                </div>
                                                            </div>
                                                            <div id="selected-files-container"></div>
                                                            <div id="selected-image-container"></div>
                                                        </div>
                                                        <div class="circle-progress ml-auto collapse">
                                                            <div class="inner">
                                                                <div class="text"></div>
                                                                <svg id="svg" width="24" height="24" viewPort="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                                    <circle id="bar" r="10" cx="12" cy="12" fill="transparent" stroke-dasharray="100" stroke-dashoffset="100"></circle>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="border-0 bg-F1F7FF px-3 rounded-r" type="submit" id="message-send-button">
                                                        <i class="bi bi-send-fill fs-16 text-primary"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="temporarily-closed-sticky-alert {{$userData['temporary-close-status'] == 1 ? '' : 'd-none'}}">
                                        <div class="alert-box">
                                            <div><img src="{{ theme_asset('assets/img/icons/warning.svg') }}" alt=""></div>
                                            <div>
                                                {{ translate('sorry') }} !
                                                {{ translate('currently_we_are_not_available.') }}
                                                {{ translate('but_you_can_ask_or_still_message_us.') }}
                                                {{ translate('We_will_get_back_to_you_soon.') }}
                                                {{ translate('Thank_you_for_your_patience.') }}.
                                            </div>
                                            <div>
                                                <button type="button" class="close close-element-onclick-by-data" aria-label="Close" data-selector=".temporarily-closed-sticky-alert">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 mt-5 w-100">
                                        <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-message.svg') }}" alt="">
                                        <h5 class="text-center text-muted">
                                            {{ translate('No_message_found') }}!
                                        </h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <span id="chatting-post-url" data-url="{{ Request::is('chat/vendor') ? route('messages').'?vendor_id=' : route('messages').'?delivery_man_id=' }}"></span>
    <span id="get-file-icon" data-default-icon="{{dynamicAsset("public/assets/back-end/img/default-icon.png")}}"
          data-word-icon="{{dynamicAsset("public/assets/back-end/img/default-icon.png")}}"></span>
@endsection

@push('script')
    <script src="{{theme_asset('assets/js/chatting.js')}}"></script>
    <script src="{{ theme_asset('assets/js/picmo-emoji.js')}}"></script>
    <script src="{{theme_asset('assets/js/emoji.js')}}"></script>
    <script src="{{ theme_asset('assets/js/select-multiple-file.js')}}"></script>
    <script src="{{ theme_asset('assets/js/select-multiple-image-for-message.js')}}"></script>
@endpush

