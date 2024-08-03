<?php

namespace App\Http\Controllers\Payment_Methods;


use App\Models\PaymentRequest;
use App\Models\User;
use App\Traits\Processor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class FlutterwaveV3Controller extends Controller
{
    use Processor;

    private $config_values;

    private PaymentRequest $payment;
    private $user;

    public function __construct(PaymentRequest $payment, User $user)
    {
        $config = $this->payment_config('flutterwave', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $this->config_values = json_decode($config->live_values);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $this->config_values = json_decode($config->test_values);
        }
        $this->payment = $payment;
        $this->user = $user;
    }

    public function initialize(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
        }

        $data = $this->payment::where(['id' => $request['payment_id']])->where(['is_paid' => 0])->first();
        if (!isset($data)) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }

        if ($data['additional_data'] != null) {
            $business = json_decode($data['additional_data']);
            $business_name = $business->business_name ?? "my_business";
        } else {
            $business_name = "my_business";
        }
        $payer = json_decode($data['payer_information']);

        //* Prepare our rave request
        $request = [
            'tx_ref' => time(),
            'amount' => $data->payment_amount,
            'currency' => 'NGN',
            'payment_options' => 'card',
            'redirect_url' => route('flutterwave-v3.callback', ['payment_id' => $data->id]),
            'customer' => [
                'email' => $payer->email,
                'name' => $payer->name
            ],
            'meta' => [
                'price' => $data->payment_amount
            ],
            'customizations' => [
                'title' => $business_name,
                'description' => $data->id
            ]
        ];

        //http request
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.flutterwave.com/v3/payments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->config_values->secret_key,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($response);
        if ($res->status == 'success') {
            return redirect()->away($res->data->link);
        }

        return 'We can not process your payment';
    }

    public function callback(Request $request)
    {
        if ($request['status'] == 'successful') {
            $txid = $request['transaction_id'];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$txid}/verify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer " . $this->config_values->secret_key,
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            $res = json_decode($response);
            if ($res->status) {
                $amountPaid = $res->data->charged_amount;
                $amountToPay = $res->data->meta->price;
                if ($amountPaid >= $amountToPay) {

                    $this->payment::where(['id' => $request['payment_id']])->update([
                        'payment_method' => 'flutterwave',
                        'is_paid' => 1,
                        'transaction_id' => $txid,
                    ]);

                    $data = $this->payment::where(['id' => $request['payment_id']])->first();

                    if (isset($data) && function_exists($data->success_hook)) {
                        call_user_func($data->success_hook, $data);
                    }
                    return $this->payment_response($data,'success');
                }
            }
        }
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data,'fail');
    }
}
