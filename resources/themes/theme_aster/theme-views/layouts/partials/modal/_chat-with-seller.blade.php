<div class="modal fade" id="contact_sellerModal" tabindex="-1" aria-labelledby="contact_sellerModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header px-sm-5 pb-1">
                <h5 class="text-capitalize" id="contact_sellerModalLabel">
                    @if(isset($shop) && isset($user_type) && $user_type == 'admin')
                        {{ translate('contact_with') }} {{ getWebConfig(name: 'company_name') }}
                    @elseif(isset($shop) && isset($user_type) && $user_type == 'seller')
                        {{ translate('contact_with') }} {{ $shop['name'] }}
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5">
                <form action="{{route('messages_store') }}" method="post" id="contact_with_seller_form"
                      data-success-message="{{ translate('send_successfully') }}">
                    @csrf

                    @if(isset($shop) && isset($user_type) && $user_type == 'admin')
                        <input value="{{ 0 }}" name="shop_id" hidden>
                        <input value="{{ 0 }}" name="admin_id" hidden>
                    @elseif(isset($shop) && isset($user_type) && $user_type == 'seller')
                        <input value="{{ $shop['id'] }}" name="shop_id" hidden>
                        <input value="{{ $shop['seller_id'] }}" name="seller_id" hidden>
                    @endif

                    <textarea name="message" class="form-control min-height-100px max-height-200px" required
                              placeholder="{{ translate('type_your_message') }}"></textarea>
                    <div class="d-flex justify-content-between mt-3">
                        <div class="d-flex">
                            <button class="btn btn-primary me-2">
                                {{ translate('send') }}
                            </button>
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                {{ translate('close') }}
                            </button>
                        </div>
                        <div>
                            <a href="{{route('chat', ['type' => 'vendor'])}}"
                               class="btn btn-primary me-2 text-capitalize">{{ translate('go_to_chatbox') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
