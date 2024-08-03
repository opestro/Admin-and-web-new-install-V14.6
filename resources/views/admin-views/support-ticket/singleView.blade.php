@extends('layouts.back-end.app')

@section('title', translate('support_Ticket'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/support_ticket.png')}}" alt="">
                {{translate('support_ticket')}}
            </h2>
        </div>
        <div class="card card-chat justify-content-between">
            <div class="card-header flex-wrap gap-3">
                @foreach($supportTicket as $ticket )
                <div class="media d-flex gap-3">
                    <img class="rounded-circle avatar"
                         src="{{ getValidImage(path: 'storage/app/public/profile/'.(isset($ticket->customer)?$ticket->customer->image:''), type: 'backend-basic') }}" alt=""/>
                    <div class="media-body">
                        <h6 class="font-size-md mb-1">{{isset($ticket->customer)?$ticket->customer['f_name'].' '.$ticket->customer['l_name']:translate('not_found')}}</h6>
                        <div class="fz-12">{{isset($ticket->customer)?$ticket->customer['phone']:''}}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div class="type font-weight-bold bg-soft--primary c1 px-2 rounded">{{translate(str_replace('_',' ',$ticket['type']))}}</div>
                    <div class="priority d-flex flex-wrap align-items-center gap-3">
                        <span class="title-color">{{translate('priority')}}:</span>
                        <span class="font-weight-bold badge-soft-info rounded px-2">{{translate(str_replace('_',' ',$ticket['priority']))}}</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="card-body p-3 overflow-y-auto height-220 flex-grow-1 msg_history d-flex flex-column-reverse"  id="show_msg">
                @foreach($ticket->conversations->reverse()->values() as $key => $message)
                    @if ($message['admin_id'])
                        <div class="outgoing_msg">
                            <div class="sent_msg">
                                <div class="received_withdraw_msg">
                                    @if($message->admin_message)
                                    <div class="d-flex justify-content-end">
                                        <p class="bg-chat rounded px-3 py-2 mb-1 w-max-content">
                                            {{$message->admin_message}}
                                        </p>
                                    </div>
                                    @endif
                                    @if ($message['attachment'] !=null && count(json_decode($message['attachment'])) > 0)
                                        <div class="row g-2 flex-wrap pt-1 justify-content-end">
                                            @foreach (json_decode($message['attachment']) as $index => $photo)
                                                <div class="col-6 col-md-2 position-relative img_row{{$index}}">
                                                    <a data-lightbox="mygallery" href="{{dynamicStorage(path: "storage/app/public/support-ticket/".$photo)}}"
                                                       class="aspect-1 overflow-hidden d-block border rounded">
                                                        <img src="{{ getValidImage(path: 'storage/app/public/support-ticket/'.$photo, type: 'backend-basic') }}"
                                                             alt="" class="img-fit">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($message->admin_message || json_decode($message['attachment']) !=null)
                                    <span class="time_date fz-12 pt-2 d-flex justify-content-end">
                                        {{$message->created_at->diffForHumans()}}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="incoming_msg">
                            <div class="received_msg p-2">
                                @if($message->customer_message)
                                <div class="d-flex justify-content-start">
                                    <p class="bg-chat rounded px-3 py-2 mb-1 w-max-content">
                                        {{$message->customer_message}}
                                    </p>
                                </div>
                                @endif

                                @if ($message['attachment'] !=null && count(json_decode($message['attachment'])) > 0)
                                    <div class="row g-2 flex-wrap pt-1 justify-content-start">
                                        @foreach (json_decode($message['attachment']) as $index => $photo)
                                            <div class="col-6 col-md-2 position-relative img_row{{$index}}">
                                                <a data-lightbox="mygallery" href="{{dynamicStorage(path: "storage/app/public/support-ticket/".$photo)}}"
                                                   class="aspect-1 overflow-hidden d-block border rounded">
                                                    <img src="{{ getValidImage(path: 'storage/app/public/support-ticket/'.$photo, type: 'backend-basic') }}"
                                                         alt="" class="img-fit">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if($message->customer_message || json_decode($message['attachment']) !=null)
                                    <span class="time_date fz-12 d-flex justify-content-start pt-2">
                                        {{$message->created_at->diffForHumans()}}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                @endForeach

                <div class="incoming_msg">
                    <div class="received_msg">
                        <div class="received_withdraw_msg">
                            @if($ticket->description)
                                <div class="d-flex justify-content-start">
                                    <p class="bg-c1 text-white rounded px-3 py-2 mb-1 w-max-content">
                                        {{$ticket->description}}
                                    </p>
                                </div>
                            @endif
                            @if ($ticket['attachment'] !=null && count(json_decode($ticket['attachment'])) > 0)
                                <div class="row g-2 flex-wrap pt-1">
                                    @foreach (json_decode($ticket['attachment']) as $index => $photo)
                                        <div class="col-6 col-md-2 position-relative img_row{{$index}}">
                                            <a data-lightbox="mygallery" href="{{dynamicStorage(path: "storage/app/public/support-ticket/".$photo)}}"
                                               class="aspect-1 overflow-hidden d-block border rounded">
                                                <img src="{{ getValidImage(path: 'storage/app/public/support-ticket/'.$photo, type: 'backend-basic') }}"
                                                     alt="" class="img-fit">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <span class="time_date fz-12 pt-2 d-flex justify-content-start"> {{$ticket->created_at->diffForHumans()}} </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0">
                <div class="type_msg">
                    <div class="input_msg_write">
                        @foreach($supportTicket as $reply)
                            <form class="needs-validation" href="{{route('admin.support-ticket.replay',$reply['id'])}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$reply['id']}}">
                                <input type="hidden" name="adminId" value="1">
                                <h5 class="pt-2 pb-1 d-flex mx-1">{{translate('leave_a_Message')}}</h5>
                                <div class="position-relative d-flex align-items-center">
                                    @if(theme_root_path() == "default")
                                        <label class="py-0 px-3 d-flex align-items-center m-0 cursor-pointer position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                <path d="M18.1029 1.83203H3.89453C2.75786 1.83203 1.83203 2.75786 1.83203 3.89453V18.1029C1.83203 19.2395 2.75786 20.1654 3.89453 20.1654H18.1029C19.2395 20.1654 20.1654 19.2395 20.1654 18.1029V3.89453C20.1654 2.75786 19.2395 1.83203 18.1029 1.83203ZM3.89453 3.20703H18.1029C18.4814 3.20703 18.7904 3.51595 18.7904 3.89453V12.7642L15.2539 9.2277C15.1255 9.09936 14.9514 9.02603 14.768 9.02603H14.7653C14.5819 9.02603 14.405 9.09936 14.2776 9.23136L10.3204 13.25L8.65845 11.5945C8.53011 11.4662 8.35595 11.3929 8.17261 11.3929C7.9957 11.3654 7.81053 11.4662 7.6822 11.6009L3.20703 16.1705V3.89453C3.20703 3.51595 3.51595 3.20703 3.89453 3.20703ZM3.21253 18.1304L8.17903 13.0575L13.9375 18.7904H3.89453C3.52603 18.7904 3.22811 18.4952 3.21253 18.1304ZM18.1029 18.7904H15.8845L11.2948 14.2189L14.7708 10.6898L18.7904 14.7084V18.1029C18.7904 18.4814 18.4814 18.7904 18.1029 18.7904Z" fill="#1455AC"/>
                                                <path d="M8.12834 9.03012C8.909 9.03012 9.54184 8.39728 9.54184 7.61662C9.54184 6.83597 8.909 6.20312 8.12834 6.20312C7.34769 6.20312 6.71484 6.83597 6.71484 7.61662C6.71484 8.39728 7.34769 9.03012 8.12834 9.03012Z" fill="#1455AC"/>
                                            </svg>
                                            <input type="file" id="message-file-array" class="h-100 position-absolute w-100 " hidden multiple  accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </label>
                                    @endif
                                    <textarea class="form-control {{ theme_root_path() == "default" ? 'pl-8':'' }}" id="msgInputValue" name="replay"
                                              type="text" placeholder="{{translate('write_your_message_here')}}" ></textarea>
                                </div>
                                <div class="mt-3 d-flex justify-content-between">
                                    <div class="">
                                        <div class="d-flex gap-3 flex-wrap filearray"></div>
                                        <div id="selected-files-container"></div>
                                    </div>
                                    <div>
                                        <button class="aSend btn btn--primary" type="submit" id="msgSendBtn">{{translate('send_Reply')}}</button>
                                    </div>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/select-multiple-image-for-message.js')}}"></script>
@endpush
