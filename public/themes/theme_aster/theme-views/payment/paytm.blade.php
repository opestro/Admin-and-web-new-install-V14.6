<html>
<head>
    <title>{{translate('merchant_Check_Out_Page')}}</title>
</head>
<body>
<center><h1>{{translate('Please_do_not_refresh_this_page').'...'}}</h1></center>
<form method="post" action="<?php echo \Illuminate\Support\Facades\Config::get('config_paytm.PAYTM_TXN_URL') ?>" name="f1">
    <table border="1">
        <tbody>
        @foreach($paramList as $name => $value)
            <input type="hidden" name="{{$name}}" value="{{$value}}">
        @endforeach
        <input type="hidden" name="CHECKSUMHASH" value="{{$checkSum}}">
        </tbody>
    </table>
    <script type="text/javascript">
        'use strict';
        document.f1.submit();
    </script>
</form>
</body>
</html>
