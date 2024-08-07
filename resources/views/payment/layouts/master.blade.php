<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ "Payment" }}</title>
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/libs/bootstrap-5/bootstrap.min.css') }}">

    @stack('script')
</head>
<body>
    @yield('content')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/libs/bootstrap-5/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
