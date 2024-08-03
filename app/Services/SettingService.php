<?php

namespace App\Services;

class SettingService
{
    public function getVacationData(string $type): string
    {
        $url = '';
        foreach (config('addon_admin_routes') as $routeArray) {
            foreach ($routeArray as $route) {
                if ($route['name'] === $type) {
                    $url = $route['url'];
                    break 2;
                }
            }
        }
        return $url;
    }

    public function getSMSModuleValidationData(object $request): array
    {
        collect(['status'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $validation = [
            'gateway' => 'required|in:releans,twilio,nexmo,2factor,msg91,hubtel,paradox,signal_wire,019_sms,viatech,global_sms,akandit_sms,sms_to,alphanet_sms',
            'mode' => 'required|in:live,test'
        ];
        $additional_data = [];
        if ($request['gateway'] == 'releans') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'from' => 'required',
                'otp_template' => 'required'
            ];
        } elseif ($request['gateway'] == 'twilio') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'sid' => 'required',
                'messaging_service_sid' => 'required',
                'token' => 'required',
                'from' => 'required',
                'otp_template' => 'required'
            ];
        } elseif ($request['gateway'] == 'nexmo') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'api_secret' => 'required',
                'token' => 'required',
                'from' => 'required',
                'otp_template' => 'required'
            ];
        } elseif ($request['gateway'] == '2factor') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required'
            ];
        } elseif ($request['gateway'] == 'msg91') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'template_id' => 'required',
                'auth_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'hubtel') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'sender_id' => 'required',
                'client_id' => 'required',
                'client_secret' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'paradox') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'sender_id' => 'required',
            ];
        } elseif ($request['gateway'] == 'signal_wire') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'project_id' => 'required',
                'token' => 'required',
                'space_url' => 'required',
                'from' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == '019_sms') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'password' => 'required',
                'username' => 'required',
                'username_for_token' => 'required',
                'sender' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'viatech') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_url' => 'required',
                'api_key' => 'required',
                'sender_id' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'global_sms') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'user_name' => 'required',
                'password' => 'required',
                'from' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'akandit_sms') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'username' => 'required',
                'password' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'sms_to') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'sender_id' => 'required',
                'otp_template' => 'required',
            ];
        } elseif ($request['gateway'] == 'alphanet_sms') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'otp_template' => 'required',
            ];
        }

        return $request->validate(array_merge($validation, $additional_data));;
    }

}
