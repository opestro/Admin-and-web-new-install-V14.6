<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\FeaturesSection;
use App\Http\Controllers\BaseController;
use App\Services\FeaturesSectionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FeaturesSectionController extends BaseController
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
        return $this->getView();
    }

    public function getView(): View
    {
        $featuresSectionTop = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'features_section_top']);
        $featuresSectionMiddle = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'features_section_middle']);
        $featuresSectionBottom = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'features_section_bottom']);
        return view(FeaturesSection::VIEW[VIEW], compact('featuresSectionTop','featuresSectionMiddle','featuresSectionBottom'));
    }

    public function update(Request $request, FeaturesSectionService $featuresSectionService): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'features_section_top', value: json_encode($request['features_section_top']));
        $featuresBottomSection = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'features_section_bottom']);
        $section_middle = [];
        if($request['features_section_middle']) {
            foreach($request['features_section_middle']['title'] as $key => $value){
                $section_middle[] = [
                    'title' => $request['features_section_middle']['title'][$key] ?? '',
                    'subtitle' => $request['features_section_middle']['subtitle'][$key] ?? '',
                ];
            }
        }
        $this->businessSettingRepo->updateOrInsert(type: 'features_section_middle', value: json_encode($section_middle));
        if($request['features_section_bottom']) {
            $section_bottom = $featuresSectionService->getBottomSectionData(request: $request, featuresBottomSection: $featuresBottomSection);
            $this->businessSettingRepo->updateOrInsert(type: 'features_section_bottom', value: json_encode($section_bottom));
        }
        return back();
    }

    public function delete(Request $request, FeaturesSectionService $featuresSectionService): JsonResponse
    {
        $featuresData = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'features_section_bottom']);
        if($featuresData){
            $newArray = $featuresSectionService->getDeleteData(request: $request, data: $featuresData);
            $this->businessSettingRepo->updateOrInsert(type: 'features_section_bottom', value: json_encode($newArray));
        }
        return response()->json(['status'=>'success']);
    }

    public function getCompanyReliabilityView(): View
    {
        $companyReliabilityData = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'company_reliability']);
        return view(FeaturesSection::COMPANY_RELIABILITY[VIEW], compact('companyReliabilityData'));
    }

    public function updateCompanyReliability(Request $request, FeaturesSectionService $featuresSectionService): RedirectResponse
    {
        $data = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'company_reliability']);
        $item = $featuresSectionService->getReliabilityUpdateData(request: $request, data: $data);
        $this->businessSettingRepo->updateOrInsert(type: 'company_reliability', value: json_encode($item));
        return back();
    }


}
