<audio id="myAudio">
    <source src="{{ theme_asset(path: 'assets/sound/notification.mp3') }}" type="audio/mpeg">
</audio>

<div class="alert--container active">
    @if(env('APP_MODE') == 'demo')
        <div class="alert alert--message-2 alert-dismissible fade show" id="demo-reset-warning" role="alert">
            <img width="28" class="align-self-start" src="{{ theme_asset(path: 'assets/img/info-2.png') }}" alt="">
            <div class="w--0">
                <h6 class="mb-1">{{ translate('warning').'!'}}</h6>
                <span>
                    {{translate('though_it_is_a_demo_site').'.'.translate('_our_system_automatically_reset_after_one_hour_&_that’s_why_you_logged_out').'.'}}
                </span>
                <h6 class="text-primary mt-2">{{ translate('why_am_I_seeing_this').'?'}}</h6>
            </div>
            <button type="button" class="close position-relative border-0" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>

