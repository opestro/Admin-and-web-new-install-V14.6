<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{dynamicStorage(path: 'storage/app/public/company/'.getWebConfig(name: 'company_fav_icon'))}}">
        <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/toastr.css') }}">
        <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/bootstrap.min.css') }}">

        <style>
            :root {
                --blue: {{ $web_config['primary_color'] }};
                --primary: {{ $web_config['primary_color'] }};
                --bs-direction: {{ Session::get('direction') }};
                --theme--text-light: #FFFFFF;
            }
            .btn--primary,
            .btn--primary:hover,
            .btn--primary:focus {
                background-color: var(--primary) !important;
                color: var(--theme--text-light) !important;
            }

            .btn-outline-primary {
                color: var(--primary) !important;
                border-color: var(--primary) !important;
            }
            .text-primary {
                color: var(--primary) !important;
            }
        </style>

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="code">
                @yield('code')
            </div>

            <div class="message">
                @yield('message')
            </div>
        </div>
    </body>
</html>
