<?php

namespace App\Http\Controllers\Payment_Methods;


use App\Models\PaymentRequest;
use App\Models\User;
use App\Traits\Processor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class PaytmController extends Controller
{
    use Processor;

    private PaymentRequest $payment;
    private $user;
    private mixed $config_values;

    public function __construct(PaymentRequest $payment, User $user)
    {
        $config = $this->payment_config('paytm', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $this->config_values = json_decode($config->live_values);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $this->config_values = json_decode($config->test_values);
        }
        if (isset($config)) {

            $PAYTM_STATUS_QUERY_NEW_URL = 'https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
            $PAYTM_TXN_URL = 'https://securegw-stage.paytm.in/theia/processTransaction';
            if ( $config->mode == 'live') {
                $PAYTM_STATUS_QUERY_NEW_URL = 'https://securegw.paytm.in/merchant-status/getTxnStatus';
                $PAYTM_TXN_URL = 'https://securegw.paytm.in/theia/processTransaction';
            }

            $config = array(
                'PAYTM_ENVIRONMENT' => ($config->mode == 'test') ? 'TEST' : 'PROD',
                'PAYTM_MERCHANT_KEY' => env('PAYTM_MERCHANT_KEY', $this->config_values->merchant_key),
                'PAYTM_MERCHANT_MID' => env('PAYTM_MERCHANT_MID', $this->config_values->merchant_id),
                'PAYTM_MERCHANT_WEBSITE' => env('PAYTM_MERCHANT_WEBSITE', $this->config_values->merchant_website_link),
                'PAYTM_REFUND_URL' => env('PAYTM_REFUND_URL', $this->config_values->refund_url ?? ''),
                'PAYTM_STATUS_QUERY_URL' => env('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL),
                'PAYTM_STATUS_QUERY_NEW_URL' => env('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL),
                'PAYTM_TXN_URL' => env('PAYTM_TXN_URL', $PAYTM_TXN_URL),
            );

            //config_paytm
            Config::set('paytm_config', $config);
        }
        $this->payment = $payment;
        $this->user = $user;
    }

    function encrypt_e($input, $ky): bool|string
    {
        $key = html_entity_decode($ky);
        $iv = "@@@@&&&&####$$$$";
        $data = openssl_encrypt($input, "AES-128-CBC", $key, 0, $iv);
        return $data;
    }

    function decrypt_e($crypt, $ky): bool|string
    {
        $key = html_entity_decode($ky);
        $iv = "@@@@&&&&####$$$$";
        $data = openssl_decrypt($crypt, "AES-128-CBC", $key, 0, $iv);
        return $data;
    }

    function generateSalt_e($length): string
    {
        $random = "";
        srand((double)microtime() * 1000000);

        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data .= "0FGH45OP89";

        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }

        return $random;
    }

    function checkString_e($value)
    {
        if ($value == 'null')
            $value = '';
        return $value;
    }

    function getChecksumFromArray($arrayList, $key, $sort = 1): bool|string
    {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getArray2Str($arrayList);
        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function getChecksumFromString($str, $key): bool|string
    {

        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function verifychecksum_e($arrayList, $key, $checksumvalue): string
    {
        $arrayList = $this->removeCheckSumParam($arrayList);
        ksort($arrayList);
        $str = $this->getArray2StrForVerify($arrayList);
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);

        $finalString = $str . "|" . $salt;

        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;

        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }

    function verifychecksum_eFromStr($str, $key, $checksumvalue): string
    {
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);

        $finalString = $str . "|" . $salt;

        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;

        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }

    function getArray2Str($arrayList): string
    {
        $findme = 'REFUND';
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pos = strpos($value, $findme);
            $pospipe = strpos($value, $findmepipe);
            if ($pos !== false || $pospipe !== false) {
                continue;
            }

            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    function getArray2StrForVerify($arrayList): string
    {
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    function redirect2PG($paramList, $key)
    {
        $hashString = $this->getchecksumFromArray($paramList);
        $checksum = $this->encrypt_e($hashString, $key);
    }

    function removeCheckSumParam($arrayList)
    {
        if (isset($arrayList["CHECKSUMHASH"])) {
            unset($arrayList["CHECKSUMHASH"]);
        }
        return $arrayList;
    }

    function getTxnStatus($requestParamList)
    {
        return $this->callAPI("PAYTM_STATUS_QUERY_URL", $requestParamList);
    }

    function getTxnStatusNew($requestParamList)
    {
        return $this->callNewAPI("PAYTM_STATUS_QUERY_NEW_URL", $requestParamList);
    }

    function initiateTxnRefund($requestParamList)
    {
        $CHECKSUM = $this->getRefundChecksumFromArray($requestParamList, "PAYTM_MERCHANT_KEY", 0);
        $requestParamList["CHECKSUM"] = $CHECKSUM;
        return $this->callAPI("PAYTM_REFUND_URL", $requestParamList);
    }

    function callAPI($apiURL, $requestParamList)
    {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse, true);
        return $responseParamList;
    }

    function callNewAPI($apiURL, $requestParamList)
    {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse, true);
        return $responseParamList;
    }

    function getRefundChecksumFromArray($arrayList, $key, $sort = 1)
    {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getRefundArray2Str($arrayList);
        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function getRefundArray2Str($arrayList)
    {
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pospipe = strpos($value, $findmepipe);
            if ($pospipe !== false) {
                continue;
            }

            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    function callRefundAPI($refundApiURL, $requestParamList)
    {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($refundApiURL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $refundApiURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse, true);
        return $responseParamList;
    }

    //payment functions
    public function payment(Request $request): View|Factory|JsonResponse|Application
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
        $payer = json_decode($data['payer_information']);

        $paramList = array();
        $ORDER_ID = time();
        $CUST_ID = $data['payer_id'];
        $INDUSTRY_TYPE_ID = $request["INDUSTRY_TYPE_ID"];
        $CHANNEL_ID = $request["CHANNEL_ID"];
        $TXN_AMOUNT = round($data->payment_amount, 2);

        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = Config::get('paytm_config.PAYTM_MERCHANT_MID');
        $paramList["ORDER_ID"] = $ORDER_ID;
        $paramList["CUST_ID"] = $data['payer_id'];
        $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = $CHANNEL_ID;
        $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
        $paramList["WEBSITE"] = Config::get('paytm_config.PAYTM_MERCHANT_WEBSITE');

        $paramList["CALLBACK_URL"] = route('paytm.response', ['payment_id' => $data->id]);
        $paramList["MSISDN"] = $payer->phone; //Mobile number of customer
        $paramList["EMAIL"] = $payer->email; //Email ID of customer
        $paramList["VERIFIED_BY"] = "EMAIL"; //
        $paramList["IS_USER_VERIFIED"] = "YES"; //

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = $this->getChecksumFromArray($paramList, Config::get('paytm_config.PAYTM_MERCHANT_KEY'));

        return view('payment.paytm', compact('checkSum', 'paramList'));
    }

    public function callback(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

        //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
        $isValidChecksum = $this->verifychecksum_e($paramList, Config::get('paytm_config.PAYTM_MERCHANT_KEY'), $paytmChecksum); //will return TRUE or FALSE string.

        if ($isValidChecksum == "TRUE") {
            if ($request["STATUS"] == "TXN_SUCCESS") {

                $this->payment::where(['id' => $request['payment_id']])->update([
                    'payment_method' => 'paytm',
                    'is_paid' => 1,
                    'transaction_id' => $request['TXNID'],
                ]);

                $data = $this->payment::where(['id' => $request['payment_id']])->first();

                if (isset($data) && function_exists($data->success_hook)) {
                    call_user_func($data->success_hook, $data);
                }
                return $this->payment_response($data,'success');
            }
        }
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data,'fail');
    }
}
