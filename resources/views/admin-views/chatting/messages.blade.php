@php
    use App\Enums\GlobalConstant;
    use App\Utils\FileManagerLogic;
@endphp

@foreach($chattingMessages as $key => $message)
    @php($genTimeGap = $message->created_at)
    @if ($message->sent_by_customer || $message->sent_by_delivery_man)
        <div class="incoming_msg d-flex align-items-end gap-2">
            <div class="">
                <img class="avatar-img user-avatar-image border inbox-user-avatar-25" id="profile_image" width="40"
                     height="40"
                     src="{{ getStorageImages(path: $lastChatUser->image_full_url,type: 'backend-profile')}}"
                     alt="Image Description">
            </div>
            <div class="received_msg" data-toggle="tooltip" data-custom-class="chatting-time min-w-0"
                 @if($message->message || count($message->attachment_full_url) > 0)
                     @if($message->created_at->diffInDays() > 6)
                         data-title="{{ $message->created_at->format('M-d-Y H:i:s') }}"
                     @else
                         data-title="{{ $message->created_at->format('l H:i:s') }}"
                     @endif
                 @endif
            >
                <div class="received_withdraw_msg">
                    @if (count($message->attachment_full_url)>0)
                        <div class="row g-1 flex-wrap pt-1 w-140">
                            @foreach ($message->attachment_full_url as $index => $attachment)
                            @php($extension = strrchr($attachment['key'],'.'))

                            @if(in_array($extension,GlobalConstant::DOCUMENT_EXTENSION))
                                    @php($icon = in_array($extension,['.pdf','.doc','docx','.txt']) ? 'word-icon': 'default-icon')
                                    @php($downloadPath =$attachment['path'])
                                    <div class="d-flex gap-2">
                                        <a href="{{$downloadPath}}" target="_blank">
                                            <div class="uploaded-file-item"><img
                                                    src="{{dynamicAsset('public/assets/back-end/img/'.$icon.'.png')}}"
                                                    class="file-icon" alt="">
                                                <div class="upload-file-item-content">
                                                    <div>
                                                        {{($attachment['key'])}}
                                                    </div>
                                                    <small>{{FileManagerLogic::getFileSize($downloadPath)}}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @else
                                    <div class="col-6 position-relative img_row {{$index}} {{$index > 3 ? 'd-none' : ''}}">
                                        <a data-lightbox="message-group-items-{{ $message['id'] }}"
                                           href="{{getStorageImages(path:$attachment,type: 'backend-basic')}}"
                                           class="aspect-1 overflow-hidden d-block border rounded position-relative">
                                            <img class="img-fit" alt=""
                                                 src="{{getStorageImages(path:$attachment,type: 'backend-basic')}}">
                                            @if($index > 2)
                                                <div class="extra-images">
                                                    <span class="extra-image-count">
                                                        +{{ count($message->attachment_full_url) - $index }}
                                                    </span>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    @if($message->message)
                        <div class="message-text-section rounded mt-1">
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
            <div class="sent_msg p-2" data-toggle="tooltip" data-custom-class="chatting-time min-w-0"
                 @if($message->message || count($message->attachment_full_url) > 0)
                     @if($message->created_at->diffInDays() > 0)
                         data-title="{{ $message->created_at->format('M-d-Y H:i:s') }}"
                     @else
                         data-title="{{ $message->created_at->format('l H:i:s') }}"
                    @endif
                @endif
            >
                @if (count($message->attachment_full_url)>0)
                    <div class="d-flex justify-content-end flex-wrap mb-2">
                        <div class="row g-1 flex-wrap pt-1 justify-content-end w-140">
                            @foreach ($message->attachment_full_url as $secondIndex => $attachment)
                                @php($extension = strrchr($attachment['key'],'.'))
                                @if(in_array($extension,GlobalConstant::DOCUMENT_EXTENSION))
                                    @php($icon = in_array($extension,['.pdf','.doc','docx','.txt']) ? 'word-icon': 'default-icon')
                                    @php($downloadPath = $attachment['path'])
                                    <div class="d-flex gap-2">
                                        <a href="{{$downloadPath}}" target="_blank">
                                            <div class="uploaded-file-item"><img
                                                    src="{{dynamicAsset('public/assets/back-end/img/'.$icon.'.png')}}"
                                                    class="file-icon" alt="">
                                                <div class="upload-file-item-content">
                                                    <div>
                                                        {{($attachment['key'])}}
                                                    </div>
                                                    <small>{{FileManagerLogic::getFileSize($downloadPath)}}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @else
                                    <div
                                        class="col-6 position-relative img_row{{$secondIndex}} {{$secondIndex > 3 ? 'd-none' : ''}}">
                                        <a data-lightbox="message-group-items-{{ $message['id'] }}"
                                           href="{{getStorageImages(path:$attachment,type: 'backend-basic')}}"
                                           class="aspect-1 overflow-hidden d-block border rounded position-relative">
                                            <img class="img-fit" alt=""
                                                 src="{{getStorageImages(path:$attachment,type: 'backend-basic')}}">

                                            @if($secondIndex > 2)
                                                <div class="extra-images">
                                                    <span class="extra-image-count">
                                                        +{{ count($message->attachment_full_url) - $secondIndex }}
                                                    </span>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                @if(!empty($message->message))
                    <div class="message-text-section rounded mt-1">
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


