<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Enums\ViewPaths\Admin\ErrorLogs;
use App\Enums\WebConfigKey;
use App\Http\Controllers\BaseController;
use App\Repositories\ErrorLogsRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ErrorLogsController extends BaseController
{
    public function __construct(
        private readonly ErrorLogsRepository $errorLogsRepo,
    )
    {
    }

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getView();
    }

    private function getView(): View
    {
        $errorLogs = $this->errorLogsRepo->getListWhere(orderBy: ['id' => 'desc'], dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        return view(ErrorLogs::INDEX[VIEW], compact('errorLogs'));
    }

    public function update(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::error(translate('you_can_not_update_this_on_demo_mode'));
            return redirect()->back();
        }
        $this->errorLogsRepo->update(id: $request['id'], data: [
            'redirect_url' => $request['redirect_url'],
            'redirect_status' => $request->get('redirect_status', '301')
        ]);
        Toastr::success(translate('updated_successfully'));
        return redirect()->back();
    }

    public function delete(Request $request): RedirectResponse
    {
        $this->errorLogsRepo->delete(params: ['id' => $request['id']]);
        Toastr::success(translate('deleted_successfully'));
        return redirect()->back();
    }

    public function deleteSelectedErrorLogs(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::error(translate('you_can_not_update_this_on_demo_mode'));
            return redirect()->back();
        }
        if ($request->has('selected-ids') && count($request['selected-ids']) > 0) {
            foreach ($request['selected-ids'] as $id) {
                $this->errorLogsRepo->delete(params: ['id' => $id]);
            }
            Toastr::success(translate('selected_error_logs_deleted_successfully'));
        } else {
            Toastr::warning(translate('please_select_logs_for_delete'));
        }
        return redirect()->back();
    }

}
