<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\SocialMediaRepositoryInterface;
use App\Enums\ViewPaths\Admin\SocialMedia;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SocialMediaRequest;
use App\Http\Requests\Admin\SocialMediaUpdateRequest;
use App\Services\SocialMediaService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SocialMediaSettingsController extends BaseController
{

    public function __construct(
        private readonly SocialMediaRepositoryInterface $socialMediaRepo,
    )
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
        return $this->getView();
    }

    public function getView(): View
    {
        return view(SocialMedia::VIEW[VIEW]);
    }

    public function getList(Request $request): JsonResponse
    {
        $data = $this->socialMediaRepo->getListWhere(orderBy: ['id' => 'desc'], filters: ['status' => 1], dataLimit: 'all');
        $data->map(function ($social_media) {
            $social_media['name'] = translate($social_media['name']);
        });
        return response()->json($data);
    }

    public function add(SocialMediaRequest $request, SocialMediaService $socialMediaService): JsonResponse
    {
        $this->socialMediaRepo->add(data: ['name' => $request['name'], 'link' => $request['link'], 'icon' => $socialMediaService->getIcon(request: $request)]);
        return response()->json(['status' => 'success']);
    }

    public function getUpdate(Request $request): JsonResponse
    {
        $data = $this->socialMediaRepo->getFirstWhere(params: ['id' => $request['id']]);
        return response()->json($data);
    }

    public function update(SocialMediaUpdateRequest $request, SocialMediaService $socialMediaService): JsonResponse
    {
        $this->socialMediaRepo->update(id: $request['id'], data: ['name' => $request['name'], 'link' => $request['link'], 'icon' => $socialMediaService->getIcon(request: $request)]);
        return response()->json(['status' => 'update']);
    }

    public function delete(Request $request): JsonResponse
    {
        $this->socialMediaRepo->delete(params: ['id' => $request['id']]);
        return response()->json();
    }

    public function updateStatus(Request $request): JsonResponse|RedirectResponse
    {
        $this->socialMediaRepo->update(id: $request['id'], data: ['active_status' => $request['status']]);
        Toastr::success(translate('status_updated_successfully'));
        return $request->ajax() ? response()->json(['success' => 1], 200) : back();
    }

}
