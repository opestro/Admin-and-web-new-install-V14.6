<?php

namespace App\Http\Controllers\Admin\ThirdParty;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\Recaptcha;
use App\Http\Controllers\BaseController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecaptchaController extends BaseController
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
        $config = $this->businessSettingRepo->getFirstWhere(params: ['type'=>'recaptcha']);
        return view(Recaptcha::VIEW[VIEW], compact('config'));
    }
    public function update(Request $request): RedirectResponse
    {
        $value = json_encode(['status' => $request['status'] ?? 0, 'site_key' => $request['site_key'], 'secret_key' => $request['secret_key']]);
        $this->businessSettingRepo->updateOrInsert(type: 'recaptcha', value: $value);
        Toastr::success(translate('Updated_Successfully'));
        return back();
    }
}
