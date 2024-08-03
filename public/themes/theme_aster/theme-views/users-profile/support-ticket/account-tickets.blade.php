@php use function App\Utils\customer_info; @endphp
@extends('theme-views.layouts.app')
@section('title', translate('My_Support_Tickets').' | '.$web_config['name']->value.' '.translate('ecommerce'))
@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column gap-3 p-2 p-sm-4">
                            <div class="d-flex left gap-2 justify-content-between">
                                <div class="media gap-3"></div>
                                <button class="btn btn-primary rounded-pill px-2 py-0 px-sm-4 py-sm-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#reviewModal">{{translate('create_support_tickets')}}</button>
                            </div>
                            @foreach($supportTickets as $key=>$supportTicket)
                                <div class="bg-light rounded-10">
                                    <div class="border-bottom support-ticket-row border-grey p-3">
                                        <div class="media gap-2 gap-sm-3">
                                            <div class="avatar">
                                                <img loading="lazy" class="img-fit dark-support" alt=""
                                                    src="{{ getValidImage(path: 'storage/app/public/profile/'.(customer_info()->image), type:'avatar' ) }}">
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-column gap-1">
                                                    <div class="media align-items-start justify-content-between">
                                                        <div class="media-body">
                                                            <a href="{{route('support-ticket.index',$supportTicket['id'])}}">
                                                                <h6 class="">{{ customer_info()->f_name }}
                                                                    &nbsp{{ customer_info()->l_name }}</h6>
                                                            </a>
                                                            <div
                                                                class="fs-12 text-muted mb-1">{{ customer_info()['email'] }}</div>
                                                        </div>
                                                        @if($supportTicket->status != 'close')
                                                            <a href="{{route('support-ticket.close',[$supportTicket['id']])}}"
                                                               class="btn btn-outline-danger fw-semibold text-nowrap">{{ translate('close_ticket') }}</a>
                                                        @endif
                                                    </div>

                                                    <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-3">
                                                    <span
                                                        @if($supportTicket->priority == 'Urgent')
                                                            class="badge rounded-pill bg-danger"
                                                        @elseif($supportTicket->priority == 'High')
                                                            class="badge rounded-pill bg-warning"
                                                        @elseif($supportTicket->priority == 'Medium')
                                                            class="badge rounded-pill bg-info"
                                                        @else
                                                            class="badge rounded-pill bg-success"
                                                        @endif
                                                    >
                                                        {{ translate($supportTicket->priority) }}</span>
                                                        <span
                                                            class="{{$supportTicket->status ==  'open' ? 'badge bg-info' : 'badge bg-danger'}} rounded-pill">{{ translate($supportTicket->status) }}</span>
                                                        <span
                                                            class="badge bg-white text-dark">{{ translate($supportTicket->type) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap justify-content-between gap-2 p-3">
                                        <h6 class="text-truncate width--60ch">{{ $supportTicket->subject }}</h6>
                                        <div
                                            class="fs-12">{{date('d M, Y H:i A',strtotime($supportTicket->created_at))}}</div>
                                    </div>
                                </div>
                            @endforeach
                            @if($supportTickets->count()==0)
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 w-100">
                                        <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-ticket.svg') }}" alt="">
                                        <h5 class="text-center text-muted">
                                            {{ translate('No_ticket_created_yet') }}!
                                        </h5>
                                    </div>
                                </div>
                            @endif
                            <div class="border-0">
                                {{$supportTickets->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header px-sm-5">
                    <h1 class="modal-title fs-5" id="reviewModalLabel">{{translate('submit_new_ticket')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="p-3 px-sm-5">
                    <span>{{translate('you_will_get_response')}}.</span>
                </div>
                <div class="modal-body px-sm-5">
                    <form action="{{route('ticket-submit')}}" id="open-ticket" method="post">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="rating">{{ translate('Subject') }}</label>
                            <input type="text" class="form-control" id="ticket-subject" name="ticket_subject" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 mb-4">
                                <label for="rating">{{ translate('type') }}</label>
                                <select id="ticket-type" name="ticket_type" class="form-select" required>
                                    <option value="Website problem">{{translate('website_problem')}}</option>
                                    <option value="Partner request">{{translate('partner_request')}}</option>
                                    <option value="Complaint">{{translate('complaint')}}</option>
                                    <option value="Info inquiry">{{translate('info_inquiry')}} </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="rating">{{ translate('priority') }}</label>
                                <select id="ticket-priority" name="ticket_priority" class="form-select" required>
                                    <option value>{{translate('choose_priority')}}</option>
                                    <option value="Urgent">{{translate('Urgent')}}</option>
                                    <option value="High">{{translate('High')}}</option>
                                    <option value="Medium">{{translate('Medium')}}</option>
                                    <option value="Low">{{translate('Low')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="comment">{{translate('describe_your_issue')}}</label>
                            <textarea class="form-control" rows="6" id="ticket-description" name="ticket_description"
                                      placeholder="{{translate('leave_your_issue')}}"></textarea>
                        </div>
                        <div class="modal-footer gap-3 pb-4 px-sm-5">
                            <button type="button" class="btn btn-secondary m-0"
                                    data-bs-dismiss="modal">{{translate('back')}}</button>
                            <button type="submit" class="btn btn-primary m-0">{{ translate('submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

