<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\SEOSettings;
use App\Http\Controllers\BaseController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SEOSettingsController extends BaseController
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
        return $this->getWebMasterToolView();
    }

    public function getWebMasterToolView(): View
    {
        $webMasterToolData = [
            'google_search_console_code' =>$this->businessSettingRepo->getFirstWhere(params:['type'=>'google_search_console_code'])->value ?? null,
            'bing_webmaster_code' => $this->businessSettingRepo->getFirstWhere(params:['type'=>'bing_webmaster_code'])->value?? null,
            'baidu_webmaster_code' => $this->businessSettingRepo->getFirstWhere(params:['type'=>'baidu_webmaster_code'])->value?? null,
            'yandex_webmaster_code' => $this->businessSettingRepo->getFirstWhere(params:['type'=>'yandex_webmaster_code'])->value?? null,

        ];
        return view(SEOSettings::WEB_MASTER_TOOL[VIEW], compact('webMasterToolData'));
    }

    public function updateWebMasterTool(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::error(translate('you_can_not_update_this_on_demo_mode'));
            return redirect()->back();
        }
        $this->businessSettingRepo->updateOrInsert(type: 'google_search_console_code', value: $request['google_search_console_code'] ?? '');
        $this->businessSettingRepo->updateOrInsert(type: 'bing_webmaster_code', value: $request['bing_webmaster_code'] ?? '');
        $this->businessSettingRepo->updateOrInsert(type: 'baidu_webmaster_code', value: $request['baidu_webmaster_code'] ?? '');
        $this->businessSettingRepo->updateOrInsert(type: 'yandex_webmaster_code', value: $request['yandex_webmaster_code'] ?? '');
        Toastr::success(translate('updated_successfully'));
        return redirect()->back();
    }

    public function getRobotTxtView(): View
    {
        $path = DOMAIN_POINTED_DIRECTORY == 'public' ? public_path('robots.txt') : base_path('robots.txt');
        $content = File::exists($path) ? File::get($path) : '';
        return view(SEOSettings::ROBOT_TXT[VIEW], compact('content', 'path'));
    }

    public function updateRobotText(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::error(translate('you_can_not_update_this_on_demo_mode'));
            return redirect()->back();
        }
        $content = $request->input('robot_text');
        $path = DOMAIN_POINTED_DIRECTORY == 'public' ? public_path('robots.txt') : base_path('robots.txt');
        if (!File::exists($path)) {
            File::put($path, '');
        }
        File::put($path, $content);
        Toastr::success(translate('updated_successfully'));
        return redirect()->back();
    }
}
