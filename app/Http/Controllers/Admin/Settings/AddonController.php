<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Enums\ViewPaths\Admin\AddonSetup;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AddonRequest;
use App\Services\AddonService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AddonController extends BaseController
{

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getView();
    }

    public function getView(): View
    {
        $addons = self::getDirectories();
        return view(AddonSetup::VIEW[VIEW], compact('addons'));
    }

    public function publish(Request $request, AddonService $addonService): JsonResponse|int
    {
        $data = $addonService->getPublishData(request: $request);
        return response()->json($data);
    }

    public function activation(Request $request, AddonService $addonService): Redirector|RedirectResponse|Application
    {
        $data = $addonService->getActivationData(request: $request);
        if ($data['status']) {
            Toastr::success(translate('activated_successfully'));
            return back();
        }
        return redirect($data['activationUrl']);
    }

    public function upload(AddonRequest $request, AddonService $addonService): JsonResponse
    {
        $data = $addonService->getUploadData(request: $request);
        return response()->json([
            'status' => $data['status'],
            'message'=> $data['message']
        ]);
    }

    public function delete(Request $request, AddonService $addonService): JsonResponse
    {
        $data = $addonService->deleteAddon(request: $request);
        return response()->json($data);
    }

    function getDirectories(): array
    {
        $scan = scandir(base_path('Modules/'));
        $addonsFolders = array_diff($scan, ['.', '..','.DS_Store']);
        $addons = [];
        foreach ($addonsFolders as $directory) {
            $addons[] = 'Modules/' . $directory;
        }
        return $addons;
    }
}
