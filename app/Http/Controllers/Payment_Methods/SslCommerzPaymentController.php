<?php

namespace App\Http\Controllers\Payment_Methods;

use App\Models\PaymentRequest;
use App\Models\User;
use App\Traits\Processor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;

class SslCommerzPaymentController extends Controller
{
    use Processor;

    private $store_id;
    private $store_password;
    private bool $host;
    private string $direct_api_url;
    private PaymentRequest $payment;
    private $user;

    public function __construct(PaymentRequest $payment, User $user)
    {
        $config = $this->payment_config('ssl_commerz', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $values = json_decode($config->live_values);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $values = json_decode($config->test_values);
        }

        if ($config) {
            $this->store_id = $values->store_id;
            $this->store_password = $values->store_password;

            # REQUEST SEND TO SSLCOMMERZ
            $this->direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
            $this->host = true;

            if ($config->mode == 'live') {
                $this->direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v4/api.php";
                $this->host = false;
            }
        }
        $this->payment = $payment;
        $this->user = $user;
    }

    public function index(Request $request)
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

        $payment_amount = $data['payment_amount'];

        $payer_information = json_decode($data['payer_information']);

        $post_data = array();
        $post_data['store_id'] = $this->store_id;
        $post_data['store_passwd'] = $this->store_password;
        $post_data['total_amount'] = round($payment_amount, 2);
        $post_data['currency'] = $data['currency_code'];
        $post_data['tran_id'] = uniqid();

        $post_data['success_url'] = url('/') . '/payment/sslcommerz/success?payment_id=' . $data['id'];
        $post_data['fail_url'] = url('/') . '/payment/sslcommerz/failed?payment_id=' . $data['id'];
        $post_data['cancel_url'] = url('/') . '/payment/sslcommerz/canceled?payment_id=' . $data['id'];

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $payer_information->name;
        $post_data['cus_email'] = $payer_information->email && $payer_information->email != '' ? $payer_information->email : 'example@example.com';
        $post_data['cus_add1'] = 'N/A';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "";
        $post_data['cus_phone'] = $payer_information->phone ?? '0000000000';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "N/A";
        $post_data['ship_add1'] = "N/A";
        $post_data['ship_add2'] = "N/A";
        $post_data['ship_city'] = "N/A";
        $post_data['ship_state'] = "N/A";
        $post_data['ship_postcode'] = "N/A";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "N/A";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "N/A";
        $post_data['product_category'] = "N/A";
        $post_data['product_profile'] = "service";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $this->direct_api_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, $this->host); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

        $content = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($code == 200 && !(curl_errno($handle))) {
            curl_close($handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close($handle);
            return back();
        }

        $sslcz = json_decode($sslcommerzResponse, true);

        if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
            echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
            exit;
        } else {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }
    }

    # FUNCTION TO CHECK HASH VALUE
    protected function SSLCOMMERZ_hash_verify($store_passwd, $post_data)
    {
        if (isset($post_data) && isset($post_data['verify_sign']) && isset($post_data['verify_key'])) {
            # NEW ARRAY DECLARED TO TAKE VALUE OF ALL POST
            $pre_define_key = explode(',', $post_data['verify_key']);

            $new_data = array();
            if (!empty($pre_define_key)) {
                foreach ($pre_define_key as $value) {
                    if (isset($post_data[$value])) {
                        $new_data[$value] = ($post_data[$value]);
                    }
                }
            }
            # ADD MD5 OF STORE PASSWORD
            $new_data['store_passwd'] = md5($store_passwd);

            # SORT THE KEY AS BEFORE
            ksort($new_data);

            $hash_string = "";
            foreach ($new_data as $key => $value) {
                $hash_string .= $key . '=' . ($value) . '&';
            }
            $hash_string = rtrim($hash_string, '&');

            if (md5($hash_string) == $post_data['verify_sign']) {

                return true;
            } else {
                $this->error = "Verification signature not matched";
                return false;
            }
        } else {
            $this->error = 'Required data mission. ex: verify_key, verify_sign';
            return false;
        }
    }

    public function success(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        if ($request['status'] == 'VALID' && $this->SSLCOMMERZ_hash_verify($this->store_password, $request)) {

            $this->payment::where(['id' => $request['payment_id']])->update([
                'payment_method' => 'ssl_commerz',
                'is_paid' => 1,
                'transaction_id' => $request->input('tran_id')
            ]);

            $data = $this->payment::where(['id' => $request['payment_id']])->first();

            if (isset($data) && function_exists($data->success_hook)) {
                call_user_func($data->success_hook, $data);
            }
            return $this->payment_response($data, 'success');
        }
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data, 'fail');
    }

    public function failed(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data, 'fail');
    }

    public function canceled(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data, 'cancel');
    }
}
