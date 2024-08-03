<?php

namespace App\Services;

use App\Enums\ViewPaths\Admin\AddonSetup;
use App\Traits\FileManagerTrait;
use App\Traits\SettingsTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AddonService
{
    use SettingsTrait;
    use FileManagerTrait;

    public function getUploadData(object $request): array
    {
        $tempFolderPath = storage_path('app/temp/');
        if (!File::exists($tempFolderPath)) {
            File::makeDirectory($tempFolderPath);
        }

        $file = $request->file('file_upload');
        $filename = $file->getClientOriginalName();
        $tempPath = $file->storeAs('temp', $filename);

        $zip = new ZipArchive();
        if ($zip->open(storage_path('app/' . $tempPath)) === TRUE) {

            $genFolderName = explode('/', $zip->getNameIndex(0))[0];
            if ($genFolderName === "__MACOSX") {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    if (strpos($zip->getNameIndex($i), "__MACOSX") === false) {
                        $getAddonFolder = explode('/', $zip->getNameIndex($i))[0];
                        break;
                    }
                }
            }
            $getAddonFolder = explode('.', $genFolderName)[0];

            $zip->extractTo(storage_path('app/temp'));
            $infoPath = storage_path('app/temp/'.$getAddonFolder.'/Addon/info.php');

            if(File::exists($infoPath))
            {
                $extractPath = base_path('Modules');
                if (!File::exists($extractPath)) {
                    File::makeDirectory($extractPath);
                }
                if (File::exists($extractPath.'/'.$getAddonFolder)) {
                    $message = translate('already_installed');
                    $status = 'error';
                }else{
                    $zip->extractTo($extractPath);
                    $zip->close();
                    File::chmod($extractPath.'/'.$getAddonFolder.'/Addon', 0777);
                    $status = 'success';
                    $message = translate('upload_successfully');
                }
            }else{
                File::cleanDirectory(storage_path('app/temp'));
                $status = 'error';
                $message = translate('invalid_file!');
            }
        }else{
            $status = 'error';
            $message = translate('file_upload_fail!');
        }

        if (File::exists(base_path('Modules/__MACOSX'))) {
            File::deleteDirectory(base_path('Modules/__MACOSX'));
        }

        File::cleanDirectory(storage_path('app/temp'));

        return [
            'status' => $status,
            'message'=> $message
        ];
    }

    public function getPublishData(object $request): array
    {
        $fullData = include($request['path'] . '/Addon/info.php');
        $path = $request['path'];
        $addonName = $fullData['name'];
        if ($fullData['purchase_code'] == null || $fullData['username'] == null) {
            return [
                'flag' => 'inactive',
                'view' => view(AddonSetup::ACTIVE_MODAL[VIEW], compact('fullData', 'path', 'addonName'))->render(),
            ];
        }
        $fullData['is_published'] = $fullData['is_published'] ? 0 : 1;
        $str = "<?php return " . var_export($fullData, true) . ";";
        file_put_contents(base_path($request['path'] . '/Addon/info.php'), $str);

        return [
            'status' => 'success',
            'message'=> 'status_updated_successfully'
        ];
    }

    public function getActivationData(object $request): array
    {
        $remove = ["http://", "https://", "www."];
        $url = str_replace($remove, "", url('/'));
        $full_data = include($request['path'] . '/Addon/info.php');

        $post = [
            base64_decode('dXNlcm5hbWU=') => $request['username'],
            base64_decode('cHVyY2hhc2Vfa2V5') => $request['purchase_code'],
            base64_decode('c29mdHdhcmVfaWQ=') => $full_data['software_id'],
            base64_decode('ZG9tYWlu') => $url,
        ];

        $response = Http::post(base64_decode('aHR0cHM6Ly9jaGVjay42YW10ZWNoLmNvbS9hcGkvdjEvYWN0aXZhdGlvbi1jaGVjaw=='), $post)->json();
        $status = base64_decode($response['active']) ?? 1;

        if((int)$status){
            $full_data['is_published'] = 1;
            $full_data['username'] = $request['username'];
            $full_data['purchase_code'] = $request['purchase_code'];
            $str = "<?php return " . var_export($full_data, true) . ";";
            file_put_contents(base_path($request['path'] . '/Addon/info.php'), $str);
        }

        $activationUrl = base64_decode('aHR0cHM6Ly9hY3RpdmF0aW9uLjZhbXRlY2guY29t');
        $activationUrl .= '?username=' . $request['username'];
        $activationUrl .= '&purchase_code=' . $request['purchase_code'];
        $activationUrl .= '&domain=' . url('/') . '&';

        return [
            'status' => (int)$status,
            'activationUrl' => $activationUrl
        ];

    }


    public function deleteAddon(object $request): array
    {
        $path = $request['path'];
        $full_path = base_path($path);
        $old = base_path('app/Traits/Payment.php');
        $new = base_path('app/Traits/Payment.txt');
        copy($new, $old);

        if(File::deleteDirectory($full_path)){
            $status = 'success';
            $message = translate('file_delete_successfully');
        }else{
            $status = 'error';
            $message = translate('file_delete_fail');
        }

        return [
            'status' => $status,
            'message'=> $message
        ];
    }
}
