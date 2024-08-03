@php
    use Illuminate\Support\Facades\Cookie;
@endphp
<script src="{{ theme_asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ theme_asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/sweet_alert/sweetalert2.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/magnific-popup-1.1.0/jquery.magnific-popup.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/easyzoom/easyzoom.min.js') }}"></script>
<script src="{{ theme_asset('assets/js/toastr.js') }}"></script>
<script src="{{ theme_asset('assets/js/main.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
<script src="{{ theme_asset('assets/js/country-picker-init.js') }}"></script>
@if(env('APP_MODE') == 'demo')
    <script>
        'use strict'
        function checkDemoResetTime() {
            let currentMinute = new Date().getMinutes();
            if (currentMinute > 55 && currentMinute <= 60) {
                $('#demo-reset-warning').addClass('active');
            } else {
                $('#demo-reset-warning').removeClass('active');
            }
        }
        checkDemoResetTime();
        setInterval(checkDemoResetTime, 60000);
    </script>
@endif
<script>
    @if(Request::is('/') &&  Cookie::has('popup_banner')===false)
        $(document).ready(function () {
            $('#initialModal').modal('show');
        });
        @php(Cookie::queue('popup_banner', 'off', 1))
    @endif
</script>
<script>
    'use strict';
    $('.delete-action').on('click', function () {
        Swal.fire({
            title: '{{translate("are_you_sure")}}?',
            text: $(this).data('message'),
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '{{$web_config['primary_color']}}',
            cancelButtonText: '{{translate('no')}}',
            confirmButtonText: '{{translate('yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = $(this).data('action');
            }
        })
    })
</script>

@if ($errors->any())
    <script>
        'use strict';
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
<script>
    'use strict';
    let cookieSection = $('#cookie-section');
    @php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
    let cookie_content = `
        <div class="cookies active absolute-white py-4">
            <div class="container">
                <h4 class="absolute-white mb-3">{{translate('Your_Privacy_Matter')}}</h4>
                <p>{{ $cookie ? $cookie['cookie_text'] : '' }}</p>
                <div class="d-flex gap-3 justify-content-end mt-4">
                    <button type="button" class="btn absolute-white btn-link" id="cookie-reject">{{translate('no_thanks')}}</button>
                    <button type="button" class="btn btn-primary" id="cookie-accept">{{translate('yes_i_Accept')}}</button>
                </div>
            </div>
        </div>
        `;
    $(document).on('click', '#cookie-accept', function () {
        document.cookie = '6valley_cookie_consent=accepted; max-age=' + 60 * 60 * 24 * 30;
        cookieSection.hide();
    });
    $(document).on('click', '#cookie-reject', function () {
        document.cookie = '6valley_cookie_consent=reject; max-age=' + 60 * 60 * 24;
        cookieSection.hide();
    });

    $(document).ready(function () {
        if (document.cookie.indexOf("6valley_cookie_consent=accepted") !== -1) {
            cookieSection.hide();
        } else {
            cookieSection.html(cookie_content).show();
        }
    });

    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

@if(!auth('customer')->check())
    <script>
        "use strict";
        $(document).ready(function() {
            const currentUrl = new URL(window.location.href);
            const referral_code_parameter = new URLSearchParams(currentUrl.search).get("referral_code");

            if (referral_code_parameter) {
                $('#registerModal').modal('show');
                let referralCode =  $('#referral_code');
                if (referralCode.length) {
                    referralCode.val(referral_code_parameter);
                }
            }
        });
    </script>
@endif

<script>
    "use strict";
    let errorMessages = {
        valueMissing: $('.please_fill_out_this_field').data('text')
    };

    $('input').each(function () {
        let $el = $(this);
        $el.on('invalid', function (event) {
            let target = event.target,
                validity = target.validity;
            target.setCustomValidity("");
            if (!validity.valid) {
                if (validity.valueMissing) {
                    target.setCustomValidity($el.data('errorRequired') || errorMessages.valueMissing);
                }
            }
        });
    });

    $('textarea').each(function () {
        let $el = $(this);
        $el.on('invalid', function (event) {
            let target = event.target,
                validity = target.validity;
            target.setCustomValidity("");
            if (!validity.valid) {
                if (validity.valueMissing) {
                    target.setCustomValidity($el.data('errorRequired') || errorMessages.valueMissing);
                }
            }
        });
    });
</script>
<script src="{{ theme_asset('assets/js/custom.js') }}"></script>
