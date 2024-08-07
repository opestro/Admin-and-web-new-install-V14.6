<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class SocialLoginService
{
    use FileManagerTrait;

    public function getUpdateData(object $request, object $socialLogin, string $service): array
    {
        $credentialArray = [];
        foreach (json_decode($socialLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'] ?? 0,
                ];
                $credentialArray[] = $cred;
            } else {
                $credentialArray[] = $data;
            }
        }
        return $credentialArray;
    }

    public function getAppleData(object $request, object $appleLogin, string $service): array
    {
        $credentialArray = [];
        if ($request->hasfile('service_file')) {
            $fileName = $this->fileUpload(dir: 'apple-login/', format: 'p8', file: $request->file('service_file'));
        }
        foreach (json_decode($appleLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'] ?? 0,
                    'team_id' => $request['team_id'],
                    'key_id' => $request['key_id'],
                    'service_file' => $fileName ?? $data['service_file'],
                    'redirect_url' => $request['redirect_url'],
                ];
                $credentialArray[] = $cred;
            } else {
                $credentialArray[] = $data;
            }
        }

        return $credentialArray;
    }

}
