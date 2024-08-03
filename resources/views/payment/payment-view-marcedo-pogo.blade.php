<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ dynamicAsset(path: 'public/assets/back-end/libs/bootstrap-5/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ dynamicAsset(path: 'public/assets/modules/payment/mercado_pogo/css/index.css') }}">
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/jquery.js') }}"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
<main>
    <input type="hidden" id="mercado-pago-public-key" value="{{ $config->public_key }}">

    <section class="payment-form dark">
        <div class="container__payment">
            <div class="block-heading">
                <h2>{{ "Card Payment" }}</h2>
            </div>
            <div class="form-payment">
                <div class="products">
                    <p class="alert alert-danger d-none" role="alert" id="error_alert"></p>
                    <div class="total">
                        {{ "Amount to be paid" }} {{ $data->currency_code }}
                        <span class="price">{{ $data->payment_amount }}</span>
                    </div>
                </div>
                <div class="payment-details">
                    <form id="form-checkout">
                        <h3 class="title">{{ "Buyer Details" }}</h3>
                        <div class="row">
                            <div class="form-group col">
                                <input id="form-checkout__cardholderEmail" name="cardholderEmail" type="email" class="form-control"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-5">
                                <select id="form-checkout__identificationType" name="identificationType" class="form-control"></select>
                            </div>
                            <div class="form-group col-sm-7">
                                <input id="form-checkout__identificationNumber" name="docNumber" type="text" class="form-control"/>
                            </div>
                        </div>
                        <br>
                        <h3 class="title">{{ "Card Details" }}</h3>
                        <div class="row">
                            <div class="form-group col-sm-8">
                                <input id="form-checkout__cardholderName" name="cardholderName" type="text" class="form-control"/>
                            </div>
                            <div class="form-group col-sm-4">
                                <div class="input-group expiration-date">
                                    <input id="form-checkout__cardExpirationMonth" name="cardExpirationMonth" type="text" class="form-control"/>
                                    <span class="date-separator">/</span>
                                    <input id="form-checkout__cardExpirationYear" name="cardExpirationYear" type="text" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group col-sm-8">
                                <input id="form-checkout__cardNumber" name="cardNumber" type="text" class="form-control"/>
                            </div>
                            <div class="form-group col-sm-4">
                                <input id="form-checkout__securityCode" name="securityCode" type="text" class="form-control"/>
                            </div>
                            <div id="issuerInput" class="form-group col-sm-12 hidden">
                                <select id="form-checkout__issuer" name="issuer" class="form-control"></select>
                            </div>
                            <div class="form-group col-sm-12">
                                <select id="form-checkout__installments" name="installments" type="text" class="form-control"></select>
                            </div>
                            <div class="form-group col-sm-12">
                                <br>
                                <button id="form-checkout__submit" type="submit" class="btn btn--primary btn-block">
                                    {{ "Pay" }}
                                </button>
                                <br>
                                <p id="loading-message">{{ "Loading, please wait..." }}</p>
                                <br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    "use strict";

    const publicKey = document.getElementById("mercado-pago-public-key").value;
    const mercadopago = new MercadoPago(publicKey);

    loadCardForm();
    function loadCardForm() {
        const productCost = '{{$data->payment_amount}}';

        const cardForm = mercadopago.cardForm({
            amount: productCost,
            autoMount: true,
            form: {
                id: "form-checkout",
                cardholderName: {
                    id: "form-checkout__cardholderName",
                    placeholder: "Card holder name",
                },
                cardholderEmail: {
                    id: "form-checkout__cardholderEmail",
                    placeholder: "Card holder email",
                },
                cardNumber: {
                    id: "form-checkout__cardNumber",
                    placeholder: "Card number",
                },
                cardExpirationMonth: {
                    id: "form-checkout__cardExpirationMonth",
                    placeholder: "MM",
                },
                cardExpirationYear: {
                    id: "form-checkout__cardExpirationYear",
                    placeholder: "YY",
                },
                securityCode: {
                    id: "form-checkout__securityCode",
                    placeholder: "Security code",
                },
                installments: {
                    id: "form-checkout__installments",
                    placeholder: "Installments",
                },
                identificationType: {
                    id: "form-checkout__identificationType",
                },
                identificationNumber: {
                    id: "form-checkout__identificationNumber",
                    placeholder: "Identification number",
                },
                issuer: {
                    id: "form-checkout__issuer",
                    placeholder: "Issuer",
                },
            },
            callbacks: {
                onFormMounted: error => {
                    if (error)
                        return console.warn("Form Mounted handling error: ", error);
                    console.log("Form mounted");
                },
                onSubmit: event => {
                    event.preventDefault();
                    document.getElementById("loading-message").style.display = "block";

                    const {
                        paymentMethodId,
                        issuerId,
                        cardholderEmail: email,
                        amount,
                        token,
                        installments,
                        identificationNumber,
                        identificationType,
                    } = cardForm.getCardFormData();

                    fetch("{{route('mercadopago.make_payment', ['payment_id' => $data->id])}}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{csrf_token()}}"
                        },
                        body: JSON.stringify({
                            token,
                            issuerId,
                            paymentMethodId,
                            transactionAmount: Number(amount),
                            installments: Number(installments),
                            payer: {
                                email,
                                identification: {
                                    type: identificationType,
                                    number: identificationNumber,
                                },
                            },
                        }),
                    })
                        .then(response => {
                            return response.json();
                        })
                        .then(result => {
                            if(result.error)
                            {
                                document.getElementById("loading-message").style.display = "none";
                                document.getElementById("error_alert").innerText = result.error;
                                document.getElementById("error_alert").style.display = "block";
                                return false;
                            }
                            location.href = '{{route('payment-success')}}';
                        })
                        .catch(error => {
                            document.getElementById("loading-message").style.display = "none";
                            document.getElementById("error_alert").innerHtml = error;
                            document.getElementById("error_alert").style.display = "block";
                        });
                },
                onFetching: (resource) => {
                    console.log("Fetching resource: ", resource);
                    const payButton = document.getElementById("form-checkout__submit");
                    payButton.setAttribute('disabled', true);
                    return () => {
                        payButton.removeAttribute("disabled");
                    };
                },
            },
        });
    }
</script>

</body>
</html>
