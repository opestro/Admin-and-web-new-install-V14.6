<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{translate('forgot_password')}}</title>
    <link rel="shortcut icon" href="{{ dynamicStorage(path: 'storage/app/public/company/'.getWebConfig(name: 'company_fav_icon')) }}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/google-fonts.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/vendor.min.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/vendor/icon-set/style.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/theme.minc619.css?v=1.0')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/toastr.css')}}">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/style.css')}}">

</head>

<body>
<main id="content" role="main" class="main __inline-20">
    <div class="position-fixed top-0 right-0 left-0 bg-img-hero __h-32rem">
        <figure class="position-absolute right-0 bottom-0 left-0">
            <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1921 273">
                <polygon fill="#fff" points="0,273 1921,273 1921,0 "/>
            </svg>
        </figure>
    </div>
    <div class="container py-5 py-sm-7">
        @php($ecommerceLogo=getWebConfig('company_web_logo'))
        <a class="d-flex justify-content-center mb-5" href="javascript:">
            <img class="z-index-2 __w-rem" height="40" src="{{getValidImage(path: 'storage/app/public/company/'.$ecommerceLogo,type: 'backend-logo')}}" alt="{{translate('logo')}}">
        </a>
        <div class="container py-4 py-lg-5 my-4">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <h2 class="h3 mb-4">{{translate('provide_your_otp_and_proceed').'?'}}</h2>
                    <div class="card py-2 mt-4">
                        <form class="card-body needs-validation" action="{{route('vendor.auth.forgot-password.otp-verification')}}"
                              method="post">
                            @csrf
                            <div class="form-group">
                                <label>{{translate('enter_your_OTP')}}</label>
                                <div id="divOuter">
                                    <div id="divInner">
                                        <input id="partitioned" class="form-control" name="otp" type="text" maxlength="4"/>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn--primary" type="submit">{{translate('proceed')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/theme.min.js')}}"></script>
<script src="{{dynamicAsset(path: 'public/assets/back-end/js/toastr.js')}}"></script>
{!! Toastr::message() !!}

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
    var obj = document.getElementById('partitioned');
    obj.addEventListener('keydown', stopCarret);
    obj.addEventListener('keyup', stopCarret);

    function stopCarret() {
        if (obj.value.length > 3) {
            setCaretPosition(obj, 3);
        }
    }

    function setCaretPosition(elem, caretPos) {
        if (elem != null) {
            if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.move('character', caretPos);
                range.select();
            } else {
                if (elem.selectionStart) {
                    elem.focus();
                    elem.setSelectionRange(caretPos, caretPos);
                } else
                    elem.focus();
            }
        }
    }
</script>
</body>
</html>

