<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\StorageConnectionSettings;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\S3CredentialAddOrUpdateRequest;
use App\Traits\FileManagerTrait;
use Aws\S3\S3Client;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class StorageConnectionSettingsController extends BaseController
{
    use FileManagerTrait;
    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
    )
    {
    }

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getView();
    }

    private function getView(): View
    {
        $storageConnectionType = getWebConfig(name: 'storage_connection_type');
        if (!$storageConnectionType) {
            $this->businessSettingRepo->updateOrInsert(type: 'storage_connection_type', value: 'local');
            $storageConnectionType = getWebConfig(name: 'storage_connection_type');
        }
        $storageConnectionS3Credential = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'storage_connection_s3_credential']);
        $storageConnectionS3Credential = $storageConnectionS3Credential ? json_decode($storageConnectionS3Credential['value'], true) : [];

        return view(StorageConnectionSettings::INDEX[VIEW], [
            'storageConnectionType' => $storageConnectionType,
            'storageConnectionS3Credential' => $storageConnectionS3Credential,
        ]);
    }

    public function updateStorageType(Request $request): JsonResponse
    {
        if (env('APP_MODE') == 'demo') {
            return response()->json([
                'message' => translate('you_can_not_update_this_on_demo_mode') . '.'
            ]);
        }

       $type =$request['status'] == "1" ? $request['type'] : ($request['type'] == 'public' ? 's3' : 'public' ) ;

       $this->updateStorageConnectionType($type);
        if($type == 's3') {
            try {
                $this->checkS3Credential();
            } catch (\Exception $exception) {
                $this->updateStorageConnectionType('public');
                return response()->json([
                    'message' => translate('storage_connection_type_unable_to_changed_due_to_s3_wrong_credential') . '.'
                ]);
            }
        }
        return response()->json([
            'status' => 1,
            'message' => translate('storage_connection_type_successfully_changed').'.'
        ]);
    }

    private function updateStorageConnectionType($type):void
    {

        $storageConnectionType = $type;
        $this->businessSettingRepo->updateOrInsert(type: 'storage_connection_type', value: $storageConnectionType);
        Session::forget('storage_connection_type');
    }
    private function checkS3Credential(): void
    {
        $this->setStorageConnectionEnvironment();
        $content = "This is a test file uploaded to S3.";
        $fileName = 'test_file.txt';
        Storage::disk('s3')->put($fileName, $content);
        if (Storage::disk('s3')->exists($fileName)) {
            Storage::disk('s3')->delete($fileName);
        }
    }

    public function updateS3Credential(S3CredentialAddOrUpdateRequest $request): JsonResponse
    {
        if (env('APP_MODE') == 'demo') {
            return response()->json([
                'status' => 0,
                'error' => translate('you_can_not_update_this_on_demo_mode').'.'
            ]);
        }
        $data = [
            'driver' => 's3',
            'key' => $request['s3_key'],
            'secret' => $request['s3_secret'],
            'region' => $request['s3_region'],
            'bucket' => $request['s3_bucket'],
            'url' => $request['s3_url'],
            'visibility' => 'public',
            'endpoint' => $request['s3_endpoint'],
        ];
        $credentials = [
            'key' => $data['key'],
            'secret' => $data['secret'],
            'region' => $data['region'],
        ];
        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => $data['region'],
            'credentials' => $credentials,
            'endpoint' => $data['endpoint'],
        ]);
        try {
            $s3Client->listBuckets();
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 0,
                'error' => translate('s3_wrong_credentials_are_not_acceptable').'.'
            ]);
        }
        $this->businessSettingRepo->updateOrInsert(type: 'storage_connection_s3_credential', value: json_encode($data));
        Toastr::success(translate('Credential_update_successfully'));
        return response()->json([
            'status' => 1,
            'message' => translate('Credential_update_successfully').'.'
        ]);


    }
}
