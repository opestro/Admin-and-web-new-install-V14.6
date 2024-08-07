<?php

namespace App\Traits;

use App\Models\Setting;
use SimpleXMLElement;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as HpptClient;

trait  SmsGateway
{
    public static function send($receiver, $otp): string
    {
        $config = self::get_settings('twilio');
        if (isset($config) && $config['status'] == 1) {
            return self::twilio($receiver, $otp);
        }

        $config = self::get_settings('nexmo');
        if (isset($config) && $config['status'] == 1) {
            return self::nexmo($receiver, $otp);
        }

        $config = self::get_settings('2factor');
        if (isset($config) && $config['status'] == 1) {
            return self::two_factor($receiver, $otp);
        }

        $config = self::get_settings('msg91');
        if (isset($config) && $config['status'] == 1) {
            return self::msg_91($receiver, $otp);
        }

        $config = self::get_settings('releans');
        if (isset($config) && $config['status'] == 1) {
            return self::releans($receiver, $otp);
        }

        $config = self::get_settings('hubtel');
        if (isset($config) && $config['status'] == 1) {
            return self::hubtel($receiver, $otp);
        }

        $config = self::get_settings('paradox');
        if (isset($config) && $config['status'] == 1) {
            return self::paradox($receiver, $otp);
        }

        $config = self::get_settings('signal_wire');
        if (isset($config) && $config['status'] == 1) {
            return self::signal_wire($receiver, $otp);
        }

        $config = self::get_settings('019_sms');
        if (isset($config) && $config['status'] == 1) {
            return self::sms_019($receiver, $otp);
        }

        $config = self::get_settings('viatech');
        if (isset($config) && $config['status'] == 1) {
            return self::viatech($receiver, $otp);
        }

        $config = self::get_settings('global_sms');
        if (isset($config) && $config['status'] == 1) {
            return self::global_sms($receiver, $otp);
        }

        $config = self::get_settings('akandit_sms');
        if (isset($config) && $config['status'] == 1) {
            return self::akandit_sms($receiver, $otp);
        }

        $config = self::get_settings('sms_to');
        if (isset($config) && $config['status'] == 1) {
            return self::sms_to($receiver, $otp);
        }

        $config = self::get_settings('alphanet_sms');
        if (isset($config) && $config['status'] == 1) {
            return self::alphanet_sms($receiver, $otp);
        }



        return 'not_found';
    }

    public static function twilio($receiver, $otp): string
    {
        $config = self::get_settings('twilio');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $message = str_replace("#OTP#", $otp, $config['otp_template']);
            $sid = $config['sid'];
            $token = $config['token'];
            try {
                $twilio = new Client($sid, $token);
                $twilio->messages
                    ->create($receiver, // to
                        array(
                            "messagingServiceSid" => $config['messaging_service_sid'],
                            "body" => $message
                        )
                    );
                $response = 'success';
            } catch (\Exception $exception) {
                $response = 'error';
            }
        }
        return $response;
    }

    public static function nexmo($receiver, $otp): string
    {
        $config = self::get_settings('nexmo');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $message = str_replace("#OTP#", $otp, $config['otp_template']);
            try {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://rest.nexmo.com/sms/json');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "from=".$config['from']."&text=".$message."&to=".$receiver."&api_key=".$config['api_key']."&api_secret=".$config['api_secret']);

                $headers = array();
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                $response = 'success';
            } catch (\Exception $exception) {
                $response = 'error';
            }
        }
        return $response;
    }

    public static function two_factor($receiver, $otp): string
    {
        $config = self::get_settings('2factor');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $api_key = $config['api_key'];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://2factor.in/API/V1/" . $api_key . "/SMS/" . $receiver . "/" . $otp . "",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if (!$err) {
                $response = 'success';
            } else {
                $response = 'error';
            }
        }
        return $response;
    }

    public static function msg_91($receiver, $otp): string
    {
        $config = self::get_settings('msg91');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $receiver = str_replace("+", "", $receiver);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=" . $config['template_id'] . "&mobile=" . $receiver . "&authkey=" . $config['auth_key'] . "",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "{\"OTP\":\"$otp\"}",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if (!$err) {
                $response = 'success';
            } else {
                $response = 'error';
            }
        }
        return $response;
    }

    public static function releans($receiver, $otp): string
    {
        $config = self::get_settings('releans');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $curl = curl_init();
            $from = $config['from'];
            $to = $receiver;
            $message = str_replace("#OTP#", $otp, $config['otp_template']);

            try {
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.releans.com/v2/message",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "sender=$from&mobile=$to&content=$message",
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer " . $config['api_key']
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $response = 'success';
            } catch (\Exception $exception) {
                $response = 'error';
            }

        }
        return $response;
    }

    public static function hubtel($receiver, $otp)
    {
        $config = self::get_settings('hubtel');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
                $receiver = str_replace("+", "", $receiver);
                $message = urlencode(str_replace("#OTP#", $otp, $config['otp_template']));
                $client_id = $config['client_id'];
                $client_secret = $config['client_secret'];
                $sender_id = $config['sender_id'];

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://sms.hubtel.com/v1/messages/send?clientsecret=".$client_secret."&clientid=".$client_id."&from=".$sender_id."&to=".$receiver."&content=".$message."",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/json"
                    ),
                ));
                $response = curl_exec($curl);
                $error = curl_error($curl);

                curl_close($curl);

                if (!$error) {
                    $response = 'success';
                } else {
                    $response = 'error';
                }
        }
        return $response;

    }

    public static function paradox($receiver, $otp)
    {
        $config = self::get_settings('paradox');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {

            $receiver = str_replace("+", "", $receiver);
            $message = str_replace("#OTP#",$otp,"Your otp is #OTP#.");

            $postRequest = array(
                "sender" => $config['sender_id'],
                "message" => $message,
                "phone" => $receiver,
            );

            $cURLConnection = curl_init('http://portal.paradox.co.ke/api/v1/send-sms');
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($postRequest, true));
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                "Content-type: application/json",
                "Accept: application/json",
                "Authorization: Bearer ".$config['api_key']
            ));

            $response = curl_exec($cURLConnection);
            $err = curl_error($cURLConnection);
            curl_close($cURLConnection);
            if (!$err) {
                $response = 'success';
            } else {
                $response = 'error';
            }
        }
        return $response;
    }

    public static function signal_wire($receiver, $otp)
    {
        $config = self::get_settings('signal_wire');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {

            $message = str_replace("#OTP#",$otp,"Your otp is #OTP#.");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://".$config['space_url']."/api/laml/2010-04-01/Accounts/".$config['project_id']."/Messages");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $config['project_id']. ':' .$config['token']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "From=".$config['from']."&To=".$receiver."&Body=".$message);

            $response = curl_exec($ch);
            $error = curl_error($ch);

            curl_close($ch);

            if (!$error) {
                $response = 'success';
            } else {
                $response = 'error';
            }

        }
        return $response;
    }

    public static function sms_019($receiver, $otp)
    {
        $config = self::get_settings('019_sms');
        if(isset($config['api']['expiration_date']) && strtotime($config['api']['expiration_date']) <= strtotime("now")) {
            self::generate_019_api();
            $config = self::get_settings('019_sms');
        }
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $curl = curl_init();
            $key = $config['api']['key'];
            $message = str_replace("#OTP#", $otp, $config['otp_template']);
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.019sms.co.il/api",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "<?xml version='1.0' encoding='UTF-8'?>
                        \r\n <sms>\r\n <user>\r\n <username>my_username</username>
                        \r\n </user>\r\n <source>{$config['sender']}</source>
                        \r\n <destinations>\r\n <phone id='someid1'>" . $receiver . "</phone>
                        \r\n </destinations>\r\n <message>" . $message . "</message>\r\n </sms>",
                CURLOPT_HTTPHEADER => array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/xml",
                    "Authorization: Bearer " . $key

                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $response = 'error';
            } else {
                $response = 'success';
            }
        }
        return $response;
    }

    public static function generate_019_api()
    {
        $config = self::get_settings('019_sms');

        $xml="<?xml version='1.0' encoding='UTF-8'?>
        <getApiToken>
        <user>
        <username>{$config['username']}</username>
        <password>{$config['password']}</password>
        </user>
        <username>{$config['username_for_token']}</username>
        <action>new</action>
        </getApiToken>";


        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8'
            ],
            'body' => $xml
        ];
        try {
            $client = new HpptClient();

            $response = $client->request('POST', "https://www.019sms.co.il/api", $options);

            $data = (Array)new SimpleXMLElement($response->getBody()->getContents());
            if(isset($data['status']) && $data['status']==0) {
                $config['api']=[
                    'key'=>$data['message'],
                    'expiration_date'=>$data['expiration_date']
                ];
                Setting::where('key_name', '019_sms')
                ->where('settings_type', 'sms_config')->update([
                        'test_values'=>json_encode($config),
                        'live_values'=>json_encode($config)
                    ]);
                return true;
            }
            info($data);
        }catch(\Exception $ex) {
            info($ex);
        }
        return false;
    }

    public static function viatech($receiver, $otp) {
        $config = self::get_settings('viatech');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $message = str_replace("#OTP#", $otp, $config['otp_template']);
            $api_key = $config['api_key'];
            $sender_id = $config['sender_id'];
            $url = $config['api_url'];
            $data = [
            "api_key" => $api_key,
            "type" => "text",
            "contacts" => $receiver,
            "senderid" => $sender_id,
            "msg" => $message,
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            if(!is_numeric($response) && substr($response, 0, 13) == "SMS SUBMITTED")
            {
                $response = 'success';
            }
            else{
                $response = 'error';
            }

        }
        return $response;
    }

    public static function global_sms($receiver, $otp)
    {
        $config = self::get_settings('global_sms');
        $response = 'error';

        if (isset($config) && $config['status'] == 1) {
            $message = urlencode(urlencode(urlencode(str_replace("#OTP#", $otp, $config['otp_template']))));
            $user = $config['user_name'];
            $password = $config['password'];
            $from = $config['from'];

            try {
              $res= Http::get("https://api.smsglobal.com/http-api.php?action=sendsms&user=".$user."&password=".$password."&from=".$from."&to=".$receiver."&text=".$message);
               // $response = 'success';
               if($res->successful()) $response = 'success';
               else $response = 'error';
            } catch (\Exception $exception) {
                $response = 'error';
            }
        }
        return $response;
    }

    public static function akandit_sms($receiver, $otp)
    {
        $config = self::get_settings('akandit_sms');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $message = str_replace("#OTP#", $otp, $config['otp_template']);
            $username = $config['username'];
            $password = $config['password'];
            try {
                $url = "http://66.45.237.70/api.php";
                $number = $receiver;
                $text = $message;
                $data = array(
                    'username'=> $username,
                    'password'=> $password,
                    'number'=>"$number",
                    'message'=>"$text"
                );

                $ch = curl_init(); // Initialize cURL
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $smsresult = curl_exec($ch);
                $p = explode("|",$smsresult);
                $sendstatus = $p[0];

                if ($sendstatus == "1101") {
                    $response = 'success';
                } else {
                    $response = 'error';
                }

            } catch (\Exception $exception) {
                $response = 'error';
            }
        }
        return $response;
    }

    public static function sms_to($receiver, $otp)
    {
        $config = self::get_settings('sms_to');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $message = str_replace("#OTP#", $otp, $config['otp_template']);
            $sender_id = $config['sender_id'];
            $api_key = $config['api_key'];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.sms.to/sms/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS =>"{\n    \"message\": \"$message\",\n    \"to\": \"$receiver\",\n    \"sender_id\": \"$sender_id\"    \n}",
                CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Accept: application/json",
                        "Authorization: Bearer " . $api_key
                    ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if (!$err) {
                $response = 'success';
            } else {
                $response = 'error';
            }
        }
        return $response;
    }

    public static function alphanet_sms($receiver, $otp)
    {
        $config = self::get_settings('alphanet_sms');
        $response = 'error';
        if (isset($config) && $config['status'] == 1) {
            $receiver = str_replace("+", "", $receiver);
            $message = str_replace("#OTP#", $otp, $config['otp_template']);
            $api_key = $config['api_key'];

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.sms.net.bd/sendsms',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('api_key' => $api_key,'msg' => $message ,'to' => $receiver),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if (!$err) {
                $response = 'success';
            } else {
                $response = 'error';
            }
        }
        return $response;
    }

    public static function get_settings($name)
    {
        $data = config_settings($name, 'sms_config');
        if (isset($data) && !is_null($data->live_values)) {
            return json_decode($data->live_values, true);
        }
        return null;
    }
}
