<audio id="myAudio">
    <source src="{{ dynamicAsset(path: 'public/assets/front-end/sound/notification.mp3') }}" type="audio/mpeg">
</audio>

<div class="alert--container active">
    @if(env('APP_MODE') == 'demo')
        <div class="alert alert--message-2 alert-dismissible fade show" id="demo-reset-warning">
            <img width="28" class="align-self-start" src="{{ theme_asset(path: 'public/assets/front-end/img/info-2.png') }}" alt="">
            <div class="w--0">
                <h6>{{ translate('warning').'!'}}</h6>
                <span>
                    {{translate('though_it_is_a_demo_site').'.'.translate('_our_system_automatically_reset_after_one_hour_&_that’s_why_you_logged_out').'.'}}
                </span>
            </div>
            <button type="button" class="close __close position-relative p-0" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>

