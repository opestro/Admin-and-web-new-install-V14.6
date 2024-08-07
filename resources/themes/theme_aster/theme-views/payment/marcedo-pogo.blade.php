@php use App\Utils\Helpers; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/css/bootstrap.min-v4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ dynamicAsset(path: 'public/assets/mercado_pogo/css/index.css') }}">
    <script src="{{ theme_asset('assets/js/jquery.min-3.4.1.js') }}"></script>
</head>
<body>
<main>
    <input type="hidden" id="mercado-pago-public-key" value="{{$data['public_key']}}">
    <section class="payment-form dark">
        <div class="container__payment">
            <div class="block-heading">
                <h2 class="text-capitalize">{{translate('card_payment')}}</h2>
            </div>
            <div class="form-payment">
                <div class="products">
                    <p class="alert alert-danger d-none" role="alert" id="error_alert"></p>
                    <div class="total">{{ translate('amount_to_be_paid')}}<span
                            class="price">{{Helpers::set_symbol($order_amount)}}</span></div>
                </div>
                <div class="payment-details">
                    <form id="form-checkout">
                        <h3 class="title text-capitalize">{{translate('buyer_details')}}</h3>
                        <div class="row">
                            <div class="form-group col">
                                <input id="form-checkout__cardholderEmail" name="cardholderEmail" type="email"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-5">
                                <select id="form-checkout__identificationType" name="identificationType"
                                        class="form-control"></select>
                            </div>
                            <div class="form-group col-sm-7">
                                <input id="form-checkout__identificationNumber" name="docNumber" type="text"
                                       class="form-control"/>
                            </div>
                        </div>
                        <br>
                        <h3 class="title text-capitalize">{{translate('card_details')}}</h3>
                        <div class="row">
                            <div class="form-group col-sm-8">
                                <input id="form-checkout__cardholderName" name="cardholderName" type="text"
                                       class="form-control"/>
                            </div>
                            <div class="form-group col-sm-4">
                                <div class="input-group expiration-date">
                                    <input id="form-checkout__cardExpirationMonth" name="cardExpirationMonth"
                                           type="text" class="form-control"/>
                                    <span class="date-separator">/</span>
                                    <input id="form-checkout__cardExpirationYear" name="cardExpirationYear" type="text"
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group col-sm-8">
                                <input id="form-checkout__cardNumber" name="cardNumber" type="text"
                                       class="form-control"/>
                            </div>
                            <div class="form-group col-sm-4">
                                <input id="form-checkout__securityCode" name="securityCode" type="text"
                                       class="form-control"/>
                            </div>
                            <div id="issuerInput" class="form-group col-sm-12 hidden">
                                <select id="form-checkout__issuer" name="issuer" class="form-control"></select>
                            </div>
                            <div class="form-group col-sm-12">
                                <select id="form-checkout__installments" name="installments" type="text"
                                        class="form-control"></select>
                            </div>
                            <div class="form-group col-sm-12">
                                <br>
                                <button id="form-checkout__submit" type="submit"
                                        class="btn btn--primary btn-block">{{translate('pay')}}</button>
                                <br>
                                <p id="loading-message">{{translate('loading_please wait').'...'}}</p>
                                <br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
</body>
    <script>
        'use strict';
        const publicKey = document.getElementById("mercado-pago-public-key").value;
        const mercadopago = new MercadoPago(publicKey);
        loadCardForm();

        function loadCardForm() {
            const productCost = '{{$order_amount}}';
            const cardForm = mercadopago.cardForm({
                amount: productCost,
                autoMount: true,
                form: {
                    id: "form-checkout",
                    cardholderName: {
                        id: "form-checkout__cardholderName",
                        placeholder: "{{translate('card_holder_name')}}",
                    },
                    cardholderEmail: {
                        id: "form-checkout__cardholderEmail",
                        placeholder: "{{translate('card_holder_email')}}",
                    },
                    cardNumber: {
                        id: "form-checkout__cardNumber",
                        placeholder: "{{translate('card_number')}}",
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
                        placeholder: "{{translate('security_code')}}",
                    },
                    installments: {
                        id: "form-checkout__installments",
                        placeholder: "{{translate('installments')}}",
                    },
                    identificationType: {
                        id: "form-checkout__identificationType",
                    },
                    identificationNumber: {
                        id: "form-checkout__identificationNumber",
                        placeholder: "{{translate('identification_number')}}",
                    },
                    issuer: {
                        id: "form-checkout__issuer",
                        placeholder: "{{translate('issuer')}}",
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
                        fetch("{{route('mercadopago.make_payment')}}", {
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
                                if (result.status === 'success') {
                                    if (result.user === 'web') {
                                        location.href = '{{route('order-placed')}}';
                                    } else {
                                        location.href = '{{route('payment-success')}}';
                                    }
                                } else if (result.status === 'error') {
                                    document.getElementById("loading-message").style.display = "none";
                                    document.getElementById("error_alert").innerText = result.message;
                                    document.getElementById("error_alert").style.display = "block";
                                    return false;
                                }

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
</html>
