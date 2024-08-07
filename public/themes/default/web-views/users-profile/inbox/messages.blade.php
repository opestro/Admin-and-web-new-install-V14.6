@php
    use App\Enums\GlobalConstant;
    use App\Utils\FileManagerLogic;
@endphp
@if (isset($chattingMessages))
    @foreach($chattingMessages as $key => $message)
        @if ($message->sent_by_seller || $message->sent_by_admin || $message->sent_by_delivery_man)
            <div class="incoming_msg d-flex" data-toggle="tooltip"
                 @if($message->created_at->diffInDays() > 6)
                     title="{{ $message->created_at->format('M-d-Y H:i:s') }}"
                 @else
                     title="{{ $message->created_at->format('l H:i:s') }}"
                @endif
            >
                <div class="incoming_msg_img">
                    <img src="{{ $userType == 'admin' ? getStorageImages(path: $web_config['fav_icon'], type: 'shop') : ( $userType == 'vendor' ? getStorageImages(path: $message?->shop?->image_full_url, type: 'shop') : getStorageImages(path: $message?->deliveryMan?->image_full_url, type: 'avatar')) }}"
                         alt="Image Description">
                </div>
                <div class="received_msg">
                    <div class="received_withdraw_msg">
                        @if($message->message)
                            <p>
                                {{$message->message}}
                            </p>
                        @endif
                        @if (count($message->attachment_full_url) >0)
                            <div class="row g-2 flex-wrap mt-3">
                                @foreach ($message->attachment_full_url as $index => $attachment)
                                    @php($extension = strrchr($attachment['key'],'.'))
                                    @if(in_array($extension,GlobalConstant::DOCUMENT_EXTENSION))
                                        @php($icon = in_array($extension,['.pdf','.doc','docx','.txt']) ? 'word-icon': 'default-icon')
                                        @php($downloadPath = $attachment['path'])
                                        <div class="d-flex gap-2 mt-2">
                                            <a href="{{$downloadPath}}" target="_blank">
                                                <div class="uploaded-file-item gap-2"><img
                                                        src="{{dynamicAsset('public/assets/front-end/img/word-icon/'.$icon.'.png')}}"
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
                                        <div class="col-6 col-md-4 img_row{{$index}} {{$index > 2 ? 'd-none' : ''}}">
                                            <div class="aspect-1 overflow-hidden d-block rounded-16px position-relative">
                                                <a data-lightbox="mygallery{{ $message['id'] }}" href="{{$attachment['path']}}" class="aspect-1 overflow-hidden d-block border rounded">
                                                    <img class="img-fit" alt="{{ translate('chatting') }}"
                                                         src="{{ getStorageImages(path: $attachment, type: 'product') }}">
                                                    @if($index > 1)
                                                        <div class="extra-images show-extra-images">
                                                            <span class="extra-image-count">
                                                                +{{ count($message->attachment_full_url) - $index }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="outgoing_msg" data-toggle="tooltip"
                 @if($message->created_at->diffInDays() > 6)
                     title="{{ $message->created_at->format('M-d-Y H:i:s') }}"
                 @else
                     title="{{ $message->created_at->format('l H:i:s') }}"
                @endif
            >
                <div class="send_msg">
                    @if($message->message)
                        <p class="btn--primary">
                            {{$message->message}}
                        </p>
                    @endif
                    @if (count($message->attachment_full_url) >0)
                        <div class="row g-2 flex-wrap mt-3 justify-content-end">
                            @foreach ($message->attachment_full_url as $secondIndex => $attachment)
                                @php($extension = strrchr($attachment['key'],'.'))
                                @if(in_array($extension,GlobalConstant::DOCUMENT_EXTENSION))
                                    @php($icon = in_array($extension,['.pdf','.doc','docx','.txt']) ? 'word-icon': 'default-icon')
                                    @php($downloadPath = $attachment['path'])
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{$downloadPath}}" target="_blank">
                                            <div class="uploaded-file-item gap-2"><img
                                                    src="{{theme_asset('public/assets/front-end/img/word-icon/'.$icon.'.png')}}"
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
                                    <div class="col-6 col-md-4 img_row{{$secondIndex}} {{$secondIndex > 2 ? 'd-none' : ''}}">
                                        <div class="aspect-1 overflow-hidden d-block rounded-16px position-relative">
                                            <a data-lightbox="mygallery{{ $message['id'] }}" href="{{$attachment['path']}}" class="aspect-1 overflow-hidden d-block border rounded">
                                                <img class="img-fit" alt="{{ translate('chatting') }}"
                                                     src="{{ getStorageImages(path: $attachment, type: 'product') }}">
                                                @if($secondIndex > 1 )
                                                    <div class="extra-images show-extra-images">
                                                            <span class="extra-image-count">
                                                                +{{ count($message->attachment_full_url) - $secondIndex }}
                                                            </span>
                                                    </div>
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endForeach
    <div id="down"></div>
@endif
