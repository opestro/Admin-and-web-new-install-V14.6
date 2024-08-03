<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Enums\ViewPaths\Admin\FileManager;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\FileManagerUploadRequest;
use App\Services\FileManagerService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileManagerController extends BaseController
{

    public function __construct(private readonly FileManagerService $fileManagerService)
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getFoldersView();
    }

    public function getFoldersView($folderPath = "cHVibGlj"): View
    {
        $file = Storage::files(base64_decode($folderPath));
        $directories = Storage::directories(base64_decode($folderPath));
        $folders = $this->fileManagerService->formatFileAndFolders(files: $directories, type: 'folder');
        $files = $this->fileManagerService->formatFileAndFolders(files: $file, type: 'file');
        $data = array_merge($folders, $files);
        $currentFolder = explode('/', base64_decode($folderPath));
        $previousFolder = str_replace('/'.end($currentFolder), '', base64_decode($folderPath));

        return view(FileManager::VIEW[VIEW], compact('data', 'folderPath', 'currentFolder', 'previousFolder'));
    }

    public function upload(FileManagerUploadRequest $request, FileManagerService $fileManagerService): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('This_option_is_disabled_for_demo'));
            return back();
        }
        $fileManagerService->uploadImages(request: $request);
        Toastr::success(translate('image_uploaded_successfully'));
        return back()->with('success', translate('image_uploaded_successfully'));
    }

    public function download($fileName): StreamedResponse
    {
        return Storage::download(base64_decode($fileName));
    }
}
