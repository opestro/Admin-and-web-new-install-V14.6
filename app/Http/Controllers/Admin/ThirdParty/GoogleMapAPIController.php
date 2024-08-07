<?php

namespace App\Http\Controllers\Admin\ThirdParty;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\GoogleMapAPI;
use App\Http\Controllers\BaseController;
use App\Utils\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GoogleMapAPIController extends BaseController
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
       $mapAPIKey=Helpers::get_business_settings('map_api_key');
       $mapAPIKeyServer=Helpers::get_business_settings('map_api_key_server');
       $mapAPIStatus = $this->businessSettingRepo->getFirstWhere(['type'=>'map_api_status']);
       return view(GoogleMapAPI::VIEW[VIEW],compact('mapAPIKey','mapAPIKeyServer','mapAPIStatus'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->businessSettingRepo->updateOrInsert(type: 'map_api_key', value: $request['map_api_key'] ?? '');
        $this->businessSettingRepo->updateOrInsert(type: 'map_api_key_server', value: $request['map_api_key_server'] ?? '');
        $this->businessSettingRepo->updateOrInsert(type: 'map_api_status', value: $request->get('status', 0));
        Toastr::success(translate('config_data_updated'));
        return back();
    }
}
