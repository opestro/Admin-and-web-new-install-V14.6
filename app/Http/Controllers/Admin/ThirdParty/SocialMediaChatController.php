<?php

namespace App\Http\Controllers\Admin\ThirdParty;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\SocialMediaChat;
use App\Http\Controllers\BaseController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SocialMediaChatController extends BaseController
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
        return view(SocialMediaChat::VIEW[VIEW]);
    }

    public function update(Request $request, $service): RedirectResponse
    {
        if($service == 'messenger'){
            $value = json_encode(['status' => $request->get('status', 0), 'script' => $request['script']]);
            $this->businessSettingRepo->updateOrInsert(type: 'messenger', value: $value);
        }elseif($service == 'whatsapp'){
            $value = json_encode(['status' => $request->get('status', 0), 'phone' => $request['phone']]);
            $this->businessSettingRepo->updateOrInsert(type: 'whatsapp', value: $value);
        }else{
            Toastr::warning(translate($service . '_information_update_fail'));
            return back();
        }

        Toastr::success(translate($service . '_information_update_successfully'));
        return back();
    }
}
