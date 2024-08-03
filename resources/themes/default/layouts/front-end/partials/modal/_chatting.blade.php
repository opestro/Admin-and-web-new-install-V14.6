<div class="modal fade" id="chatting_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-faded-info">
                <h6 class="modal-title text-capitalize" id="exampleModalLongTitle">
                    @if(isset($seller) && isset($user_type) && $user_type == 'admin')
                        {{ translate('send_message_to') }} {{ getWebConfig(name: 'company_name') }}
                    @elseif(isset($seller) && isset($user_type) && $user_type == 'seller')
                        {{ translate('send_message_to') }} {{ $seller->shop['name'] }}
                    @endif
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('messages_store')}}" method="post" id="seller-chat-form">
                    @csrf

                    @if(isset($seller) && isset($user_type) && $user_type == 'admin')
                        <input value="{{ 0 }}" name="shop_id" hidden>
                        <input value="{{ 0 }}" name="admin_id" hidden>
                    @elseif(isset($seller) && isset($user_type) && $user_type == 'seller')
                        <input value="{{ $seller->shop['id'] }}" name="shop_id" hidden>
                        <input value="{{ $seller['id'] }}" name="seller_id" hidden>
                    @endif

                    <textarea name="message" class="form-control min-height-100px max-height-200px" required placeholder="{{ translate('Write_here') }}..."></textarea>
                    <br>
                    <div class="justify-content-end gap-2 d-flex flex-wrap">
                        <a href="{{route('chat', ['type' => 'vendor'])}}" class="btn btn-soft-primary bg--secondary border">
                            {{translate('go_to_chatbox')}}
                        </a>
                        <button class="btn btn--primary text-white">{{translate('send')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
