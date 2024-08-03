@extends('layouts.front-end.app')

@section('title', translate('support_Ticket'))

@section('content')
    <div class="container py-4 rtl text-align-direction">
        <div class="row">
            @include('web-views.partials._profile-aside')

            <section class="col-lg-9">
                <div class="d-flex align-items-center justify-content-end mb-3 d-lg-none">
                    <button class="profile-aside-btn btn btn--primary px-2 rounded px-2 py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7 9.81219C7 9.41419 6.842 9.03269 6.5605 8.75169C6.2795 8.47019 5.898 8.31219 5.5 8.31219C4.507 8.31219 2.993 8.31219 2 8.31219C1.602 8.31219 1.2205 8.47019 0.939499 8.75169C0.657999 9.03269 0.5 9.41419 0.5 9.81219V13.3122C0.5 13.7102 0.657999 14.0917 0.939499 14.3727C1.2205 14.6542 1.602 14.8122 2 14.8122H5.5C5.898 14.8122 6.2795 14.6542 6.5605 14.3727C6.842 14.0917 7 13.7102 7 13.3122V9.81219ZM14.5 9.81219C14.5 9.41419 14.342 9.03269 14.0605 8.75169C13.7795 8.47019 13.398 8.31219 13 8.31219C12.007 8.31219 10.493 8.31219 9.5 8.31219C9.102 8.31219 8.7205 8.47019 8.4395 8.75169C8.158 9.03269 8 9.41419 8 9.81219V13.3122C8 13.7102 8.158 14.0917 8.4395 14.3727C8.7205 14.6542 9.102 14.8122 9.5 14.8122H13C13.398 14.8122 13.7795 14.6542 14.0605 14.3727C14.342 14.0917 14.5 13.7102 14.5 13.3122V9.81219ZM12.3105 7.20869L14.3965 5.12269C14.982 4.53719 14.982 3.58719 14.3965 3.00169L12.3105 0.915687C11.725 0.330188 10.775 0.330188 10.1895 0.915687L8.1035 3.00169C7.518 3.58719 7.518 4.53719 8.1035 5.12269L10.1895 7.20869C10.775 7.79419 11.725 7.79419 12.3105 7.20869ZM7 2.31219C7 1.91419 6.842 1.53269 6.5605 1.25169C6.2795 0.970186 5.898 0.812187 5.5 0.812187C4.507 0.812187 2.993 0.812187 2 0.812187C1.602 0.812187 1.2205 0.970186 0.939499 1.25169C0.657999 1.53269 0.5 1.91419 0.5 2.31219V5.81219C0.5 6.21019 0.657999 6.59169 0.939499 6.87269C1.2205 7.15419 1.602 7.31219 2 7.31219H5.5C5.898 7.31219 6.2795 7.15419 6.5605 6.87269C6.842 6.59169 7 6.21019 7 5.81219V2.31219Z" fill="white"/>
                            </svg>
                    </button>
                </div>

                <div class="__card">
                    <div class="card-header border-0 p-2 p-sm-3">
                        <div class="bg-section rounded d-flex gap-2 align-items-start justify-content-between p-3 ">
                            <div class="support_ticket_head-media media flex-wrap gap-2 gap-sm-3">
                                <div class="rounded-circle overflow-hidden">
                                    <img class="rounded other-store-logo aspect-1" width="50"  alt="{{ translate('product') }}"
                                         src="{{ getValidImage(path: 'storage/app/public/profile/'.\App\Utils\customer_info()->image, type: 'avatar') }}">
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex gap-2 align-items-center">
                                            <h6 class="text-capitalize m-0 fs-14 font-semibold">{{ \App\Utils\customer_info()->f_name }} {{ \App\Utils\customer_info()->l_name }}</h6>
                                            <div class="d-none d-sm-block">
                                                <span
                                                    @if($ticket->priority == 'Urgent')
                                                        class="py-2 badge badge-danger rounded text-capitalize"
                                                    @elseif($ticket->priority == 'High')
                                                        class="py-2 badge badge-warning rounded text-capitalize"
                                                    @elseif($ticket->priority == 'Medium')
                                                        class="py-2 badge badge-success rounded text-capitalize"
                                                    @else
                                                        class="py-2 badge badge-info rounded text-capitalize"
                                                    @endif
                                                    >{{ translate($ticket->type) }}</span>
                                            </div>
                                        </div>
                                        <div class="meta-info d-flex flex-wrap align-items-center column-gap-4 fs-14 mt-2">
                                            <div class="d-flex align-items-center gap-2 gap-md-3">
                                                <div>{{translate('status')}}:</div>
                                                <span class="{{$ticket->status ==  'open' ? ' text-info ' : 'text-danger'}} fw-semibold">{{ ucwords($ticket->status) }}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 gap-md-3 text-capitalize">
                                                <div>{{translate('priority')}}:</div>
                                                <span
                                                    @if($ticket->priority == 'Urgent')
                                                        class="text-danger fw-bold"
                                                    @elseif($ticket->priority == 'High')
                                                        class="text-warning fw-bold "
                                                    @elseif($ticket->priority == 'Medium')
                                                        class="text-success fw-bold"
                                                    @else
                                                        class="text-primary fw-bold"
                                                    @endif> {{ translate($ticket->priority) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($ticket->status != 'close')
                                <a href="{{route('support-ticket.close',[$ticket['id']])}}" class="btn btn-sm fs-14 font-semibold text-capitalize btn-outline-danger d-none d-sm-inline-block">{{ translate('close_this_ticket') }}</a>
                                <a href="{{route('support-ticket.close',[$ticket['id']])}}" class="btn btn-outline-danger btn-sm d-sm-none">{{ translate('close') }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="__media-wrapper p-2 p-sm-3 d-flex flex-column-reverse">

                        @foreach($ticket->conversations as $conversation)

                            @if($conversation['admin_id'])
                                @php($admin=\App\Models\Admin::where('id',$conversation['admin_id'])->first())
                                <div class="media gap-3">
                                    <div class="media-body d-flex">

                                        <img class="rounded-circle __img-40 mt-2 text-align-direction" alt=""
                                             src="{{ getValidImage(path: 'storage/app/public/admin/'.$admin['image'], type: 'avatar') }}">
                                        <div class="mx-1 __incoming-msg">

                                            @if ($conversation['admin_message'])
                                            <div class="d-flex justify-content-start">
                                                <p class="font-size-md mb-1 btn--primary message-text">{{$conversation['admin_message']}}</p>
                                            </div>
                                            @endif

                                            @if ($conversation['attachment'] !=null && count(json_decode($conversation['attachment'])) > 0)
                                                <div class="row g-2 flex-wrap mt-3 justify-content-start">
                                                    @foreach (json_decode($conversation['attachment']) as $key => $photo)
                                                        <div class="col-sm-6 col-md-4 position-relative img_row{{$key}}">
                                                            <a data-lightbox="mygallery" href="{{dynamicStorage(path: "storage/app/public/support-ticket/".$photo)}}"
                                                               class="aspect-1 overflow-hidden d-block border rounded">
                                                                <img class="img-fit" alt="{{ translate('ticket') }}"
                                                                     src="{{ getValidImage(path: 'storage/app/public/support-ticket/'.$photo, type: 'product') }}">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <span class="time_date font-size-ms text-muted">
                                                {{$conversation->created_at->diffForHumans()}}
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            @else
                                <div class="media">
                                    <div class="media-body __outgoing-msg">
                                        @if ($conversation['customer_message'])
                                            <div class="d-flex justify-content-end">
                                                <p class="font-size-md mb-1 btn--primary message-text">{{$conversation['customer_message']}}</p>
                                            </div>
                                        @endif

                                        @if ($conversation['attachment'] !=null && count(json_decode($conversation['attachment'])) > 0)
                                            <div class="row g-2 flex-wrap mt-3 justify-content-end">
                                                @foreach (json_decode($conversation['attachment']) as $key => $photo)
                                                    <div class="col-sm-6 col-md-4 position-relative img_row{{$key}}">
                                                        <a data-lightbox="mygallery" href="{{dynamicStorage(path: "storage/app/public/support-ticket/".$photo)}}"
                                                           class="aspect-1 overflow-hidden d-block border rounded">
                                                            <img class="img-fit" alt="{{ translate('ticket') }}"
                                                                 src="{{ getValidImage(path: 'storage/app/public/support-ticket/'.$photo, type: 'product') }}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        <p class=" time_date font-size-ms text-end">
                                            {{$conversation->created_at->diffForHumans()}}
                                        </p>
                                    </div>
                                </div>

                            @endif
                        @endforeach

                        <div class="media">
                            <div class="media-body __outgoing-msg">
                                @if ($ticket['description'])
                                    <div class="d-flex justify-content-end">
                                        <p class="font-size-md mb-1 btn--primary message-text">{{$ticket['description']}}</p>
                                    </div>
                                @endif

                                @if ($ticket['attachment'] !=null && count(json_decode($ticket['attachment'])) > 0)
                                    <div class="row g-2 flex-wrap mt-3 justify-content-end">
                                        @foreach (json_decode($ticket['attachment']) as $key => $photo)
                                            <div class="col-sm-6 col-md-4 position-relative img_row{{$key}}">
                                                <a data-lightbox="mygallery" href="{{dynamicStorage(path: "storage/app/public/support-ticket/".$photo)}}"
                                                   class="aspect-1 overflow-hidden d-block border rounded">
                                                    <img class="img-fit" alt="{{ translate('ticket') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/support-ticket/'.$photo, type: 'product') }}">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <p class=" time_date font-size-ms text-end">
                                    {{$ticket->created_at->diffForHumans()}}
                                </p>
                            </div>
                        </div>

                    </div>
                    @if($ticket->status != 'close')
                    <div class="card-footer py-0 px-0">
                        <form class="needs-validation" href="{{route('support-ticket.comment',[$ticket['id']])}}" enctype="multipart/form-data"
                            method="post" novalidate>
                            @csrf
                            <div class="d-flex flex-wrap align-items-baseline">
                                <div class="px-2 d-flex">
                                    <label class="m-0 cursor-pointer position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                            <path d="M18.1029 1.83203H3.89453C2.75786 1.83203 1.83203 2.75786 1.83203 3.89453V18.1029C1.83203 19.2395 2.75786 20.1654 3.89453 20.1654H18.1029C19.2395 20.1654 20.1654 19.2395 20.1654 18.1029V3.89453C20.1654 2.75786 19.2395 1.83203 18.1029 1.83203ZM3.89453 3.20703H18.1029C18.4814 3.20703 18.7904 3.51595 18.7904 3.89453V12.7642L15.2539 9.2277C15.1255 9.09936 14.9514 9.02603 14.768 9.02603H14.7653C14.5819 9.02603 14.405 9.09936 14.2776 9.23136L10.3204 13.25L8.65845 11.5945C8.53011 11.4662 8.35595 11.3929 8.17261 11.3929C7.9957 11.3654 7.81053 11.4662 7.6822 11.6009L3.20703 16.1705V3.89453C3.20703 3.51595 3.51595 3.20703 3.89453 3.20703ZM3.21253 18.1304L8.17903 13.0575L13.9375 18.7904H3.89453C3.52603 18.7904 3.22811 18.4952 3.21253 18.1304ZM18.1029 18.7904H15.8845L11.2948 14.2189L14.7708 10.6898L18.7904 14.7084V18.1029C18.7904 18.4814 18.4814 18.7904 18.1029 18.7904Z" fill="#1455AC"/>
                                            <path d="M8.12834 9.03012C8.909 9.03012 9.54184 8.39728 9.54184 7.61662C9.54184 6.83597 8.909 6.20312 8.12834 6.20312C7.34769 6.20312 6.71484 6.83597 6.71484 7.61662C6.71484 8.39728 7.34769 9.03012 8.12834 9.03012Z" fill="#1455AC"/>
                                        </svg>
                                        <input type="file" id="f_p_v_up1" class="h-100 position-absolute w-100" hidden multiple accept="image/*">
                                    </label>
                                </div>
                                <div class="w-0 flex-grow-1">
                                    <textarea class="form-control ticket-view-control px-0 py-3" name="comment" rows="8" placeholder="{{translate('write_your_message_here')}}..." ></textarea>

                                    <div class="d-flex gap-3 flex-wrap filearray"></div>
                                    <div id="selected-files-container"></div>
                                </div>

                                <button type="submit" class="no-gutter py-0 px-3 m-0 border-0 bg-transparent text-base">
                                    <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="31" height="31" rx="6" fill="currentColor"/>
                                        <path d="M21.2267 15.5548L10.2267 10.0548C10.1404 10.0116 10.0436 9.99437 9.94779 10.005C9.85198 10.0157 9.7613 10.0538 9.68665 10.1148C9.61536 10.1745 9.56215 10.253 9.53301 10.3413C9.50386 10.4296 9.49993 10.5243 9.52165 10.6148L10.8467 15.4998H16.5017V16.4998H10.8467L9.50165 21.3698C9.48126 21.4453 9.47888 21.5245 9.4947 21.6012C9.51052 21.6778 9.5441 21.7496 9.59273 21.8109C9.64136 21.8722 9.7037 21.9212 9.77472 21.954C9.84574 21.9868 9.92347 22.0025 10.0017 21.9998C10.0799 21.9993 10.157 21.9805 10.2267 21.9448L21.2267 16.4448C21.3086 16.4028 21.3773 16.3391 21.4253 16.2605C21.4733 16.182 21.4987 16.0918 21.4987 15.9998C21.4987 15.9077 21.4733 15.8175 21.4253 15.739C21.3773 15.6605 21.3086 15.5967 21.2267 15.5548Z" fill="white"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/ticket-view.js') }}"></script>
@endpush
