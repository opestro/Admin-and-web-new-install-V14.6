
@foreach($chattingMessages as $key => $message)
        @php($genTimeGap = $message->created_at)
        @if ($message->sent_by_customer || $message->sent_by_delivery_man)
            <div class="incoming_msg d-flex align-items-end gap-2">
                <div class="">
                    <img class="avatar-img user-avatar-image border inbox-user-avatar-25" id="profile_image" width="40" height="40"
                         src="{{ request('type') == 'customer' || $message->sent_by_customer ? getValidImage(path: 'storage/app/public/profile/'.$lastChatUser['image'],type: 'backend-profile') : getValidImage(path:'storage/app/public/delivery-man/'.$lastChatUser['image'],type: 'backend-profile') }}"
                         alt="Image Description">
                </div>
                <div class="received_msg" data-toggle="tooltip" data-custom-class="chatting-time min-w-0" data-title="@if($message->message) {{$message->created_at->format('D')}} {{ $message->created_at->format('h:i A') }} @endif">
                    <div class="received_withdraw_msg">
                        @if (json_decode($message['attachment']))
                            <div class="row g-1 flex-wrap pt-1 w-140">
                                @foreach (json_decode($message['attachment']) as $index => $photo)
                                    @if($index < 3 || count(json_decode($message['attachment'], true)) < 5)
                                        <div class="col-6 position-relative img_row{{$index}}">
                                            <a data-lightbox="message-group-items-{{ $message['id'] }}"
                                               href="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}"
                                               class="aspect-1 overflow-hidden d-block border rounded-lg position-relative">
                                                <img class="img-fit" alt=""
                                                     src="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}">
                                            </a>
                                        </div>
                                    @elseif($index == 3)
                                        <div class="col-6 position-relative img_row{{$index}}">
                                            <a data-lightbox="message-group-items-{{ $message['id'] }}"
                                               href="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}"
                                               class="aspect-1 overflow-hidden d-block border rounded-lg position-relative">
                                                <img class="img-fit" alt=""
                                                     src="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}">
                                                <div class="extra-images">
                                                    <span class="extra-image-count">
                                                        +{{ count(json_decode($message['attachment'], true)) - $index }}
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    @else
                                        <div class="col-6 position-relative d-none img_row{{$index}}">
                                            <a data-lightbox="message-group-items-{{ $message['id'] }}"
                                               href="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}"
                                               class="aspect-1 overflow-hidden d-block border rounded position-relative">
                                                <img class="img-fit" alt=""
                                                     src="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}">
                                                <div class="extra-images">
                                                    <span class="extra-image-count">
                                                        +{{ count(json_decode($message['attachment'], true)) - $index }}
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if($message->message)
                            <div class="message-text-section rounded">
                                <p class="m-0 pb-1">
                                    {{$message->message}}
                                </p>
                                <span class="small text-end w-100 d-block text-muted"></span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="outgoing_msg mb-0">
                <div class="sent_msg p-2" data-toggle="tooltip" data-custom-class="chatting-time min-w-0" data-title="@if($message->message) {{$message->created_at->format('D')}} {{ $message->created_at->format('h:i A') }} @endif">
                    @if (json_decode($message['attachment']))
                        <div class="d-flex justify-content-end flex-wrap mb-2">
                            <div class="row g-1 flex-wrap pt-1 justify-content-end w-140">
                                @foreach (json_decode($message['attachment']) as $secondIndex => $photo)
                                    @if($secondIndex < 3 || count(json_decode($message['attachment'], true)) < 5)
                                        <div class="col-6 position-relative img_row{{$secondIndex}}">
                                            <a data-lightbox="message-group-items-{{ $message['id'] }}"
                                               href="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}"
                                               class="aspect-1 overflow-hidden d-block border rounded-lg position-relative">
                                                <img class="img-fit" alt=""
                                                     src="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}">
                                            </a>
                                        </div>
                                    @elseif($secondIndex == 3)
                                        <div class="col-6 position-relative img_row{{$secondIndex}}">
                                            <a data-lightbox="message-group-items-{{ $message['id'] }}"
                                               href="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}"
                                               class="aspect-1 overflow-hidden d-block border rounded-lg position-relative">
                                                <img class="img-fit" alt=""
                                                     src="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}">
                                                <div class="extra-images">
                                                <span class="extra-image-count">
                                                    +{{ count(json_decode($message['attachment'], true)) - $secondIndex }}
                                                </span>
                                                </div>
                                            </a>
                                        </div>
                                    @else
                                        <div class="col-6 position-relative d-none img_row{{$secondIndex}}">
                                            <a data-lightbox="message-group-items-{{ $message['id'] }}"
                                               href="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}"
                                               class="aspect-1 overflow-hidden d-block border rounded position-relative">
                                                <img class="img-fit" alt=""
                                                     src="{{getValidImage(path:'storage/app/public/chatting/'.$photo,type: 'backend-basic')}}">
                                                <div class="extra-images">
                                                <span class="extra-image-count">
                                                    +{{ count(json_decode($message['attachment'], true)) - $secondIndex }}
                                                </span>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if(!empty($message->message))
                        <div class="message-text-section rounded mb-1">
                            <p class="m-0 pb-1">
                                {{$message->message}}
                            </p>
                            <span class="small text-start w-100 d-block text-muted"></span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endForeach


