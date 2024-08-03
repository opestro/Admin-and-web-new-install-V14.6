@extends('theme-views.layouts.app')

@section('title', translate('my_Inbox').' | '.$web_config['name']->value.' '.translate('ecommerce'))

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
                                            @if(isset($inhouseShop))
                                                <div  data-link="{{route('chat', ['type' => 'vendor'])}}/?id=0"
                                                      class="chat-list get-view-by-onclick chat-list-item {{ (request()->has('id') && request('id') == 0) || ($last_chat->seller_id == null && $last_chat->admin_id == 0) ? 'active':'' }} media gap-2 align-items-center"
                                                      id="user_0">
                                                    <div class="avatar rounded-circle ">
                                                        <img class="img-fit aspect-1 rounded-circle dark-support" alt="" loading="lazy"
                                                             src="{{ getValidImage(path: 'storage/app/public/company/'.($web_config['fav_icon']->value), type: 'shop') }}">
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="chat-people-name gap-2 align-items-center mb-1">
                                                            <div
                                                                class="text-truncate d-flex align-items-center gap-1 width--100">
                                                                <h6 class="fs-12 seller"
                                                                    id="0">{{ $web_config['name']->value }}</h6>
                                                                <div class="fs-12 text-muted"></div>
                                                            </div>

                                                            <div class="fs-10">
                                                                {{ $inhouseShop->created_at->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                        <p class="fs-10">{{ $web_config['email']->value }}</p>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (isset($unique_shops))
                                                @foreach($unique_shops as $key=>$shop)
                                                    @php($type = $shop->delivery_man_id ? 'delivery-man' : 'vendor')
                                                    @php($unique_id = $shop->delivery_man_id ?? $shop->shop_id)
                                                    <div  data-link="{{route('chat', ['type' => $type])}}/?id={{$unique_id}}"
                                                        class="chat-list get-view-by-onclick chat-list-item {{($last_chat->delivery_man_id==$unique_id || $last_chat->shop_id==$unique_id) ? 'active' : ''}} media gap-2 align-items-center"
                                                        id="user_{{$unique_id}}">
                                                        <div class="avatar rounded-circle ">
                                                            @if($shop->delivery_man_id)
                                                                <img class="img-fit aspect-1 rounded-circle dark-support" alt="" loading="lazy"
                                                                src="{{ getValidImage(path: 'storage/app/public/delivery-man/'.$shop->image, type:'avatar') }}">
                                                            @else
                                                                <img class="img-fit aspect-1 rounded-circle dark-support" alt="" loading="lazy"
                                                                     src="{{ getValidImage(path: 'storage/app/public/shop/'.$shop->image, type:'shop') }}">
                                                            @endif
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="chat-people-name gap-2 align-items-center mb-1">
                                                                <div
                                                                    class="text-truncate d-flex align-items-center gap-1 width--100">
                                                                    <h6 class="fs-12 seller"
                                                                        id="{{$unique_id}}">{{$shop->f_name? $shop->f_name. ' ' . $shop->l_name: $shop->name}}</h6>
                                                                    <div class="fs-12 text-muted"></div>
                                                                </div>

                                                                <div class="fs-10">
                                                                    {{ $shop->created_at->diffForHumans() }}
                                                                </div>
                                                            </div>
                                                            <p class="fs-10">{{$shop->seller_email ?? $shop->email}}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative">
                                @if(isset($last_chat))
                                    <div class="border-bottom px-3 py-2">
                                        <div class="media gap-2 align-items-center">
                                            <div class="avatar rounded-circle">
                                                @if($last_chat->deliveryMan)
                                                    <img alt="" loading="lazy" id="image" class="img-fit aspect-1 rounded-circle dark-support"
                                                        src="{{ getValidImage(path: 'storage/app/public/delivery-man/'.$last_chat->deliveryMan->image, type:'avatar') }}">
                                                @elseif($last_chat->seller_id)
                                                    <img alt="" loading="lazy" id="image" class="img-fit aspect-1 rounded-circle dark-support"
                                                         src="{{ getValidImage(path: 'storage/app/public/shop/'.$last_chat->shop->image, type:'shop') }}">
                                                @elseif(isset($last_chat->admin_id) && $last_chat->admin_id == 0)
                                                    <img alt="" loading="lazy" id="image" class="img-fit aspect-1 rounded-circle dark-support"
                                                         src="{{ getValidImage(path: 'storage/app/public/company/'.($web_config['fav_icon']->value), type: 'shop') }}">
                                                @endif
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-column gap-1">
                                                    @if($last_chat->deliveryMan)
                                                        <h6 id="name">{{ $last_chat->deliveryMan->f_name.' '.$last_chat->deliveryMan->l_name }}</h6>
                                                    @elseif($last_chat->seller_id)
                                                        <h6 id="name">{{ $last_chat->shop->name }}</h6>
                                                    @elseif(isset($last_chat->admin_id) && $last_chat->admin_id == 0)
                                                        <h6 id="name">{{ $web_config['name']->value }}</h6>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="messaging">
                                        <div class="inbox_msg custom-scrollbar p-3 message-history height-480px"
                                             id="show_msg">
                                            @if (isset($chattings))
                                                @foreach($chattings as $key => $chat)
                                                    @if ($chat->sent_by_admin || $chat->sent_by_seller || $chat->sent_by_delivery_man)
                                                            <div class="received_msg">
                                                                @if($chat->message)
                                                                <p class="message_text">
                                                                    {{$chat->message}}
                                                                </p>
                                                                @endif
                                                                @if (json_decode($chat['attachment']) !=null)
                                                                    <div class="d-flex gap-2 flex-wrap mt-3 justify-content-start custom-image-popup-init">
                                                                        @foreach (json_decode($chat['attachment']) as $index => $photo)
                                                                            @if(file_exists(base_path("storage/app/public/chatting/".$photo)))
                                                                                <a class="inbox-image-element custom-image-popup" href="{{ getValidImage(path: 'storage/app/public/chatting/'.$photo, type:'product') }}">
                                                                                    <img src="{{ getValidImage(path: 'storage/app/public/chatting/'.$photo, type:'product') }}" alt="">
                                                                                </a>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                                <span class="time_date"> {{ date('h:i:A | M d',strtotime($chat->created_at)) }} </span>
                                                            </div>
                                                    @else
                                                        <div class="outgoing_msg" id="outgoing_msg">
                                                            @if($chat->message)
                                                            <p class="message_text">
                                                                {{$chat->message}}
                                                            </p>
                                                            @endif
                                                            @if ($chat['attachment'] !=null)
                                                                <div class="d-flex gap-2 flex-wrap mt-3 justify-content-end custom-image-popup-init">
                                                                    @foreach (json_decode($chat['attachment']) as $index => $photo)
                                                                        @if(file_exists(base_path("storage/app/public/chatting/".$photo)))
                                                                            <a class="inbox-image-element custom-image-popup" href="{{ getValidImage(path: 'storage/app/public/chatting/'.$photo, type:'product') }}">
                                                                                <img src="{{ getValidImage(path: 'storage/app/public/chatting/'.$photo, type:'product') }}" alt="">
                                                                            </a>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                            <span class="time_date d-flex justify-content-end"> {{ date('h:i:A | M d',strtotime($chat->created_at)) }} </span>
                                                        </div>
                                                @endif
                                                @endForeach
                                                <div id="down"></div>
                                            @endif
                                        </div>
                                        <div class="type_msg px-2">
                                            <form action="{{route('messages_store')}}" method="post" class="mt-4" id="submit-message">
                                                @csrf
                                                <div class="input_msg_write border rounded py-2 px-2 px-sm-3 d-flex align-items-center justify-content-between gap-2">
                                                    <div class="d-flex align-items-center gap-2 py-0 h-auto form-control focus-border rounded-10">
                                                        @if( Request::is('chat/vendor') )
                                                            <input type="text" id="shop-id" hidden value="{{$last_chat->shop_id}}" name="">
                                                            @if($last_chat->shop)
                                                                <input type="text" id="seller-id" hidden value="{{$last_chat->shop->seller_id}}" name="">
                                                            @endif
                                                        @elseif( Request::is('chat/delivery-man') )
                                                            <input type="text" id="delivery-man-id" hidden
                                                                   value="{{$last_chat->delivery_man_id}}" name="">
                                                        @endif
                                                        <textarea class="w-100 focus-input" id="write-message"
                                                                  placeholder="{{translate('start_a_new_message')}}"></textarea>
                                                    </div>

                                                    <button class="bg-transparent border-0" type="submit" id="message-send-button">
                                                        <i class="bi bi-send-fill fs-16 text-primary"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    @if(Request::is('chat/vendor') && (($last_chat->shop && $last_chat->shop->temporary_close) || (isset($last_chat->admin_id) && $last_chat->admin_id == 0 && getWebConfig(name: 'temporary_close')['status'])))
                                        <div class="temporarily-closed-sticky-alert">
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
                                    @endif
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
@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/chat.js') }}"></script>
@endpush


