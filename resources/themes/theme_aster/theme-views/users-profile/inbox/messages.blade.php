@php
    use App\Enums\GlobalConstant;
    use App\Utils\FileManagerLogic;
@endphp
@if (isset($chattingMessages))
    @foreach($chattingMessages as $key => $message)
        @if ($message->sent_by_seller || $message->sent_by_admin || $message->sent_by_delivery_man)
            <div class="received_msg">
                @if($message->message)
                    <p class="message_text" data-bs-toggle="tooltip"
                       @if($message->created_at->diffInDays() > 6)
                           data-bs-title="{{ $message->created_at->format('M-d-Y h:i a') }}"
                       @else
                           data-bs-title="{{ $message->created_at->format('l h:i a') }}"
                        @endif
                    >
                        {{$message->message}}
                    </p>
                @endif
                @if (count($message->attachment_full_url) >0)
                    <div class="d-flex gap-2 flex-wrap mt-3 justify-content-start custom-image-popup-init" data-bs-toggle="tooltip">
                        @foreach ($message->attachment_full_url as $index => $attachment)
                            @php($extension = strrchr($attachment['key'],'.'))
                            @if(in_array($extension,GlobalConstant::DOCUMENT_EXTENSION))
                                @php($icon = in_array($extension,['.pdf','.doc','docx','.txt']) ? 'word-icon': 'default-icon')
                                @php($downloadPath = $attachment['path'])
                                <div class="d-flex gap-2 mt-2" data-bs-toggle="tooltip" data-bs-title="{{ date('h:i:A | M d',strtotime($message->created_at)) }}">
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
                                <div class="position-relative overflow-hidden rounded-16px {{$index > 3 ? 'd-none' : ''}}">
                                    <a class="inbox-image-element custom-image-popup rounded-16px" href="{{getStorageImages(path: $attachment, type:'product') }}" data-bs-title="{{ date('h:i:A | M d',strtotime($message->created_at)) }}">
                                        <img loading="lazy" src="{{ getStorageImages(path: $attachment, type:'product') }}"
                                             class="rounded" alt="{{ translate('verification') }}">
                                    </a>
                                    @if($index > 2)
                                        <div class="extra-images show-extra-images">
                                                <span class="extra-image-count">
                                                    +{{ count($message->attachment_full_url) - $index }}
                                                </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="outgoing_msg" id="outgoing_msg">
                @if($message->message)
                    <p class="message_text"  data-bs-toggle="tooltip"
                       @if($message->created_at->diffInDays() > 6)
                           data-bs-title="{{ $message->created_at->format('M-d-Y h:i a') }}"
                       @else
                           data-bs-title="{{ $message->created_at->format('l h:i a') }}"
                        @endif
                    >
                        {{$message->message}}
                    </p>
                @endif
                @if ($message['attachment'] !=null)
                    <div class="d-flex gap-2 flex-wrap mt-3 justify-content-end custom-image-popup-init">
                        @foreach ($message->attachment_full_url  as $secondIndex => $attachment)
                            @php($extension = strrchr($attachment['key'],'.'))
                            @if(in_array($extension,GlobalConstant::DOCUMENT_EXTENSION))
                                @php($icon = in_array($extension,['.pdf','.doc','docx','.txt']) ? 'word-icon': 'default-icon')
                                @php($downloadPath =$attachment['path'])
                                <div class="d-flex gap-2 mt-2" data-bs-toggle="tooltip" data-bs-title="{{ date('h:i:A | M d',strtotime($message->created_at)) }}">
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
                                <div class="position-relative overflow-hidden rounded-16px {{$secondIndex > 3 ? 'd-none' : ''}}">
                                    <a class="inbox-image-element custom-image-popup rounded-16px" href="{{ getStorageImages(path: $attachment, type:'product') }}" data-bs-toggle="tooltip" data-bs-title="{{ date('h:i:A | M d',strtotime($message->created_at)) }}">
                                        <img loading="lazy" src="{{ getStorageImages(path: $attachment, type:'product') }}"
                                             class="rounded" alt="{{ translate('verification') }}">
                                    </a>
                                    @if($secondIndex > 2)
                                        <div class="extra-images show-extra-images">
                                            <span class="extra-image-count">
                                                +{{ count($message->attachment_full_url) - $secondIndex }}
                                            </span>
                                        </div>
                                   @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    @endForeach
    <div id="down"></div>
@endif
