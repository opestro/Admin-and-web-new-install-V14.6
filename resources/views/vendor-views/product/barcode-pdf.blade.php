<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ translate("product_Barcode") }}</title>
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/bootstrap.css') }}" />
</head>

<body>
    @if ($quantity)
        <div class="container">
            <div class="row">
                @for ($i = 0; $i < $quantity; $i++)
                    @if ($i % 3 == 0 && $i != 0)
            </div>
            <div class="row">
    @endif
    <div align="center" class="col-xs-4" style="border: 1px dotted #CCC; margin: 5px; width: 27%;">
        <span
            class="text-capitalize text-bold">{{ getWebConfig(name: 'company_name') }}</span>
        <span class="product-name" style="display: block">{{ Str::limit($product->name, 30) }}</span>
        <span class="currency">
            {{ $product['selling_price'] . ' ' . setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->unit_price), currencyCode: getCurrencyCode())  }}</span>
        <br>
        <div class="bar-code" style="margin-left: 10px !important; font-weight:bold">{!! DNS1D::getBarcodeHTML($product->code, 'C128') !!}</div>
        <p class="">{{ translate('code') }} :
            {{ $product->code }}</p>
    </div>
    @endfor
    </div>
    </div>
    @endif
</body>

</html>
