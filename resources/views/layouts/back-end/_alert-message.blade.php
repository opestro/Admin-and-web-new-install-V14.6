<div class="alert--container active">
    <a href="{{route((is_null(auth('seller')->id())? 'admin':'vendor').'.messages.index', ['type' => 'customer'])}}">
        <div class="alert alert--message-2 alert-dismissible fade show "  id="chatting-new-notification-check" role="alert">
            <img width="28" src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/chatting-notification.svg') }}" alt="">
            <div class="w-0">
                <h6>{{ translate('Message') }}</h6>
                <span id="chatting-new-notification-check-message">
                    {{ translate('New_Message') }}
                </span>
            </div>
            <button type="button" class="close position-relative p-0" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </a>
    @if(env('APP_MODE') == 'demo')
    <div class="alert alert--message-2 alert-dismissible fade show" id="demo-reset-warning">
        <img width="28" class="align-self-start" src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-2.png') }}" alt="">
        <div class="w-0">
            <h6>{{ translate('warning').'!'}}</h6>
            <span class="warning-message">
                {{translate('though_it_is_a_demo_site').'.'.translate('_our_system_automatically_reset_after_one_hour_&_that’s_why_you_logged_out').'.'}}
            </span>
        </div>
        <button type="button" class="close position-relative p-0" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="alert alert--message-2 alert-dismissible fade show product-limited-stock-alert">
        <img width="28" class="align-self-start image" src="" alt="">
        <div class="w-0">
            <h6 class="title text-truncate"></h6>
            <span class="message">
            </span>
            <div class="d-flex justify-content-between gap-3 mt-2">
                <a href="javascript:" class="text-decoration-underline text-capitalize product-stock-alert-hide">{{translate('don’t_show_again')}}</a>
                <a href="javascript:" class="text-decoration-underline text-capitalize product-list">{{translate('click_to_view')}}</a>
            </div>
        </div>
        <button type="button" class="close position-relative p-0 product-stock-limit-close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="alert alert--message-3 alert--message-for-pos border-bottom alert-dismissible fade show">
        <img width="28" src="{{ dynamicAsset(path: 'public/assets/back-end/img/warning.png') }}" alt="">
        <div class="w-0">
            <h6>{{ translate('Warning').'!'}}</h6>
            <span class="warning-message"></span>
        </div>
        <button type="button" class="close position-relative p-0 close-alert--message-for-pos">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>

