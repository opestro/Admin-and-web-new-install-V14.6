@extends('layouts.back-end.app')

@section('title',translate('chatting_Page'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/support-ticket.png')}}" alt="">
                {{translate('chatting_List')}}
            </h2>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-4 chatSel">
                <div class="card card-body px-0 h-100">
                    <div class="inbox_people">
                        <form class="search-form mb-4 px-20" id="chat-search-form">
                            <div class="search-input-group">
                                <i class="tio-search search-icon" aria-hidden="true"></i>
                                <input id="myInput" type="text" aria-label="Search customers..."
                                       placeholder="{{ request('type') == 'customer' ? translate('search_customers') : translate('search_delivery_men')}}...">
                            </div>
                        </form>
                        <ul class="nav nav-tabs gap-3 border-0 mb-3 mx-4" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link bg-transparent p-2 {{ request('type') == 'customer' ? 'active' : '' }}"
                                   href="{{ route('admin.messages.index', ['type' => 'customer']) }}">
                                    {{translate("customer")}}
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link bg-transparent p-2 {{ request('type') == 'delivery-man' ? 'active' : '' }}"
                                   href="{{ route('admin.messages.index', ['type' => 'delivery-man']) }}">
                                    {{translate('delivery_Man')}}
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="customers" role="tabpanel"
                                 aria-labelledby="pills-home-tab">
                                <div class="inbox_chat d-flex flex-column">
                                    @if(isset($allChattingUsers) && count($allChattingUsers) > 0)
                                        @foreach($allChattingUsers as $key => $chatting)
                                            @if($chatting->user_id && $chatting->customer)
                                                <div class="list_filter">
                                                    <div
                                                        class="chat_list p-3 d-flex gap-2 @if ($key == 0) bg-soft-secondary @endif get-ajax-message-view"
                                                        data-user-id="{{ $chatting->user_id }}">
                                                        <div class="chat_people media gap-10 w-100" id="chat_people">
                                                            <div class="chat_img avatar avatar-sm avatar-circle">
                                                                <img src="{{ getValidImage(path:'storage/app/public/profile/'.$chatting->customer->image,type: 'backend-profile') }}"
                                                                    id="{{$chatting->user_id}}"
                                                                    class="avatar-img avatar-circle" alt="">
                                                                <span
                                                                    class="avatar-status avatar-sm-status avatar-status-success"></span>
                                                            </div>
                                                            <div class="chat_ib media-body">
                                                                <h5 class="mb-1 seller {{$chatting->seen_by_seller ?'active-text' :''}} d-flex justify-content-between align-items-center gap-2"
                                                                    id="{{ $chatting->user_id }}"
                                                                    data-name="{{ $chatting->customer->f_name.' '.$chatting->customer->l_name }}"
                                                                    data-phone="{{ $chatting->customer->phone }}">
                                                                    {{ $chatting->customer->f_name .' '. $chatting->customer->l_name }}

                                                                    <span class="lead small">{{ $chatting->created_at->diffForHumans() }}</span>
                                                                </h5>
                                                                <span
                                                                    class="mt-2 font-weight-normal text-muted d-block text-start"
                                                                    id="{{ $chatting->user_id }}"
                                                                    data-name="{{ $chatting->customer->f_name .' '. $chatting->customer->l_name}}"
                                                                    data-phone="{{ $chatting->customer->phone }}">{{ $chatting->customer->phone }}</span>
                                                            </div>
                                                        </div>
                                                        @if(!$chatting->seen_by_admin && !($key == 0))
                                                            <div
                                                                class="message-status bg-danger notify-alert-{{ $chatting->user_id }}"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @elseif($chatting->delivery_man_id && $chatting->deliveryMan)
                                                <div class="list_filter">
                                                    <div
                                                        class="chat_list p-3 d-flex gap-2 @if ($key == 0) bg-soft-secondary @endif get-ajax-message-view"
                                                        data-user-id="{{ $chatting->delivery_man_id }}">
                                                        <div class="chat_people media gap-10 w-100" id="chat_people">
                                                            <div class="chat_img avatar avatar-sm avatar-circle">
                                                                <img
                                                                    src="{{ getValidImage(path:'storage/app/public/delivery-man/'.$chatting->deliveryMan->image,type: 'backend-profile') }}"
                                                                    id="{{$chatting->user_id}}"
                                                                    class="avatar-img avatar-circle" alt="">
                                                                <span
                                                                    class="avatar-status avatar-sm-status avatar-status-success"></span>
                                                            </div>
                                                            <div class="chat_ib media-body">
                                                                <h5 class="mb-1 seller {{$chatting->seen_by_seller ?'active-text' :''}}"
                                                                    id="{{ $chatting->delivery_man_id }}"
                                                                    data-name="{{ $chatting->deliveryMan->f_name.' '.$chatting->deliveryMan->l_name }}"
                                                                    data-phone="{{ $chatting->deliveryMan->country_code.$chatting->deliveryMan->phone }}">
                                                                    {{ $chatting->deliveryMan->f_name.' '.$chatting->deliveryMan->l_name }}

                                                                    <span class="lead small float-end">{{ $chatting->created_at->diffForHumans() }}</span>
                                                                </h5>
                                                                <span
                                                                    class="mt-2 font-weight-normal text-muted d-block"
                                                                    id="{{ $chatting->delivery_man_id }}"
                                                                    data-name="{{ $chatting->deliveryMan->f_name .' '. $chatting->deliveryMan->l_name}}"
                                                                    data-phone="{{ $chatting->deliveryMan->country_code.$chatting->deliveryMan->phone }}">{{ $chatting->deliveryMan->country_code.$chatting->deliveryMan->phone }}</span>
                                                            </div>
                                                        </div>
                                                        @if(!$chatting->seen_by_admin && !($key == 0))
                                                            <div
                                                                class="message-status bg-danger notify-alert-{{ $chatting->delivery_man_id }}"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <section class="col-xl-9 col-lg-8 mt-4 mt-lg-0">
                <div class="card card-body card-chat justify-content-center Chat" id="">
                    @if(isset($lastChatUser))
                        <div class="inbox_msg_header d-flex flex-wrap gap-3 justify-content-between align-items-center border px-3 py-2 rounded mb-4">
                            <div class="media align-items-center gap-3">
                                <div class="avatar avatar-sm avatar-circle border">
                                    <img class="avatar-img user-avatar-image" id="profile_image"
                                         src="{{ request('type') == 'customer' ? getValidImage(path: 'storage/app/public/profile/'.$lastChatUser['image'],type: 'backend-profile') : getValidImage(path:'storage/app/public/delivery-man/'.$lastChatUser['image'],type: 'backend-profile') }}"
                                         alt="Image Description">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                                <div class="media-body">
                                    <h5 class="profile-name mb-1" id="profile_name">{{ $lastChatUser['f_name'].' '.$lastChatUser['l_name'] }}</h5>
                                    <span class="fz-12" id="profile_phone">{{ $lastChatUser['country_code'] }} {{ $lastChatUser['phone'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-3 overflow-y-auto height-220 flex-grow-1 msg_history d-flex flex-column-reverse" id="chatting-messages-section">
                            @include('admin-views.chatting.messages', ['lastChatUser'=>$lastChatUser, 'chattingMessages'=>$chattingMessages])
                        </div>

                        <div class="type_msg">
                            <div class="input_msg_write">
                                <form class="mt-4 chatting-messages-ajax-form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="current-user-hidden-id" value="{{ $lastChatUser->id }}" name="{{ $userType == 'customer' ? 'user_id' : 'delivery_man_id' }}">
                                    <div class="position-relative d-flex">
                                        @if(theme_root_path() == "default")
                                            <label class="py-0 px-3 d-flex align-items-center m-0 cursor-pointer position-absolute top-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                    <path
                                                        d="M18.1029 1.83203H3.89453C2.75786 1.83203 1.83203 2.75786 1.83203 3.89453V18.1029C1.83203 19.2395 2.75786 20.1654 3.89453 20.1654H18.1029C19.2395 20.1654 20.1654 19.2395 20.1654 18.1029V3.89453C20.1654 2.75786 19.2395 1.83203 18.1029 1.83203ZM3.89453 3.20703H18.1029C18.4814 3.20703 18.7904 3.51595 18.7904 3.89453V12.7642L15.2539 9.2277C15.1255 9.09936 14.9514 9.02603 14.768 9.02603H14.7653C14.5819 9.02603 14.405 9.09936 14.2776 9.23136L10.3204 13.25L8.65845 11.5945C8.53011 11.4662 8.35595 11.3929 8.17261 11.3929C7.9957 11.3654 7.81053 11.4662 7.6822 11.6009L3.20703 16.1705V3.89453C3.20703 3.51595 3.51595 3.20703 3.89453 3.20703ZM3.21253 18.1304L8.17903 13.0575L13.9375 18.7904H3.89453C3.52603 18.7904 3.22811 18.4952 3.21253 18.1304ZM18.1029 18.7904H15.8845L11.2948 14.2189L14.7708 10.6898L18.7904 14.7084V18.1029C18.7904 18.4814 18.4814 18.7904 18.1029 18.7904Z"
                                                        fill="#1455AC"/>
                                                    <path
                                                        d="M8.12834 9.03012C8.909 9.03012 9.54184 8.39728 9.54184 7.61662C9.54184 6.83597 8.909 6.20312 8.12834 6.20312C7.34769 6.20312 6.71484 6.83597 6.71484 7.61662C6.71484 8.39728 7.34769 9.03012 8.12834 9.03012Z"
                                                        fill="#1455AC"/>
                                                </svg>
                                                <input type="file" id="msgfilesValue" class="h-100 position-absolute w-100 " hidden multiple
                                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            </label>
                                        @endif
                                        <textarea class="form-control w-0 {{ theme_root_path() == "default" ? 'pl-8':'' }}" id="msgInputValue"
                                                  name="message"
                                                  type="text" placeholder="{{translate('send_a_message')}}"
                                                  aria-label="Search"></textarea>
                                        <div class="d-flex align-items-center justify-content-center bg-F1F7FF radius-right-button">
                                            <button class="aSend bg-transparent outline-0 border-0 shadow-0" type="submit" id="msgSendBtn">
                                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/send-icon.png')}}" alt="">
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-between">
                                        <div class="overflow-x-auto pb-2 pt-3 w-100">
                                            <div class="d-flex gap-3 filearray"></div>
                                            <div id="selected-files-container"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="d-flex flex-column align-items-center gap-3">
                                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/empty-message.png') }}" alt="">
                                <p>{{ translate('you_haven’t_any_conversation_yet') }}</p>
                            </div>
                        </div>
                    @endif

                </div>
            </section>
        </div>
        <span id="chatting-post-url"
              data-url="{{ Request::is('admin/messages/index/customer') ? route('admin.messages.message').'?user_id=' : route('admin.messages.message').'?delivery_man_id=' }}"></span>
        <span id="image-url" data-url="{{ asset('storage/app/public/chatting') }}"></span>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset('public/assets/back-end/js/admin/chatting.js')}}"></script>
@endpush

