<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Enums\ViewPaths\Admin\EnvironmentSettings;
use App\Http\Controllers\BaseController;
use App\Traits\SettingsTrait;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnvironmentSettingsController extends BaseController
{
    use SettingsTrait;

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
        return view(EnvironmentSettings::VIEW[VIEW]);
    }

    public function update(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('you_can_not_update_this_on_demo_mode'));
            return back();
        }

        try {
            $this->setEnvironmentValue(envKey: 'APP_DEBUG', envValue: $request['app_debug'] ?? env('APP_DEBUG'));
            $this->setEnvironmentValue(envKey: 'APP_MODE', envValue: $request['app_mode'] ?? env('APP_MODE'));
            Toastr::success(translate('environment_variables_updated_successfully'));
        } catch (Exception $exception) {
            Toastr::error(translate('environment_variables_updated_failed'));
        }
        return back();
    }
}
