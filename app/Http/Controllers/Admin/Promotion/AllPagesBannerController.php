<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\GlobalConstant;
use App\Enums\ViewPaths\Admin\AllPagesBanner;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AllPagesBannerAddRequest;
use App\Http\Requests\Admin\AllPagesBannerUpdateRequest;
use App\Services\AllPagesBannerService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AllPagesBannerController extends BaseController
{
    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
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
        return $this->getListView($request);
    }

    public function getListView(Request $request): View
    {
        $bannerTypes = ['banner_privacy_policy', 'banner_terms_conditions', 'banner_refund_policy', 'banner_return_policy',
                        'banner_cancellation_policy', 'banner_about_us', 'banner_faq_page' ];

        if (theme_root_path() == GlobalConstant::THEME_LIFESTYLE) {
            $bannerTypes = array_merge(['banner_product_list_page'], $bannerTypes);
        }

        $pageBanners = $this->businessSettingRepo->getListWhereIn(
            orderBy: ['id'=>'desc'],
            searchValue: $request['searchValue'],
            whereInFilters: ['type'=>$bannerTypes],
            dataLimit: getWebConfig(name: 'pagination_limit'),
        );

        return view(AllPagesBanner::LIST[VIEW], compact('pageBanners'));
    }

    public function add(AllPagesBannerAddRequest $request, AllPagesBannerService $allPagesBannerService): RedirectResponse
    {
        $dataArray = $allPagesBannerService->getProcessedData(request: $request);
        $this->businessSettingRepo->add(data: $dataArray);
        Toastr::success(translate('banner_added_successfully'));
        return redirect()->back();
    }

    public function getUpdateView(string|int $id): View
    {
        $banner = $this->businessSettingRepo->getFirstWhere(params: ['id'=>$id]);
        return view(AllPagesBanner::UPDATE[VIEW], compact('banner'));
    }

    public function update(AllPagesBannerUpdateRequest $request, AllPagesBannerService $allPagesBannerService): RedirectResponse
    {
        $banner = $this->businessSettingRepo->getFirstWhere(params: ['id'=>$request['id']]);
        $dataArray = $allPagesBannerService->getProcessedData(request: $request, banner: $banner);
        $this->businessSettingRepo->update(id: $banner['id'],data: $dataArray);
        Toastr::success(translate('banner_update_successfully') );
        return redirect()->back();
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $banner = $this->businessSettingRepo->getFirstWhere(params: ['id'=>$request['id']]);
        if($request['status'] == 1) {
            $othersBanners = $this->businessSettingRepo->getListWhereIn(filters: ['type'=>$banner['type']], dataLimit: 'all');
            foreach ($othersBanners as $banner) {
                $this->businessSettingRepo->updateWhere(params: ['id'=>$banner['id'],'type'=>$banner['type']], data: [
                    'value' => json_encode([
                        'status'=>0,
                        'image'=>json_decode($banner['value'])->image,
                    ]),
                ]);
            }
        }

        $this->businessSettingRepo->update(id: $request['id'], data: [
            'value' => json_encode([
                'status'=>$request->get('status', 0),
                'image'=>json_decode($banner['value'])->image,
            ]),
        ]);

        return response()->json([
            'status' => $request->get('status', 0),
            'message' => $request['status'] ? translate('Banner_published_successfully') : translate('Banner_unpublished_successfully'),
        ]);
    }

    public function delete(Request $request, AllPagesBannerService $allPagesBannerService): JsonResponse
    {
        $banner = $this->businessSettingRepo->getFirstWhere(params: ['id'=>$request['id']]);
        $allPagesBannerService->deleteImage(image: json_decode($banner['value'])->image);
        $this->businessSettingRepo->delete(params: ['id'=>$request['id']]);
        return response()->json(['message'=>translate('Banner_deleted_successfully')]);
    }

}
