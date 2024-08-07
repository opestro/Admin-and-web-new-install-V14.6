<?php

namespace App\Http\Controllers\Admin\Vendor;

use App\Contracts\Repositories\WithdrawalMethodRepositoryInterface;
use App\Enums\ViewPaths\Admin\WithdrawalMethod;
use App\Enums\WebConfigKey;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\WithdrawalMethodRequest;
use App\Services\WithdrawalMethodService;
use App\Traits\CommonTrait;
use App\Traits\PaginatorTrait;
use App\Traits\PushNotificationTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WithdrawalMethodController extends BaseController
{
    use PaginatorTrait;
    use CommonTrait;
    use PushNotificationTrait;

    public function __construct(
        private readonly WithdrawalMethodRepositoryInterface $withdrawalMethodRepo,
    )
    {
    }
    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getListView($request);
    }

    public function getListView(Request $request): View
    {
        $withdrawalMethods = $this->withdrawalMethodRepo->getListWhere(
            orderBy: ['id'=>'desc'],
            searchValue: $request['searchValue'],
            dataLimit:getWebConfig(name: WebConfigKey::PAGINATION_LIMIT)
        );

        return view(WithdrawalMethod::LIST[VIEW], compact('withdrawalMethods'));
    }

    public function getAddView(): View
    {
        return view(WithdrawalMethod::ADD[VIEW]);
    }

    public function getUpdateView($id): View
    {
        $withdrawalMethod = $this->withdrawalMethodRepo->getFirstWhere(params: ['id' => $id]);
        return View(WithdrawalMethod::UPDATE[VIEW], compact('withdrawalMethod'));
    }

    public function add(WithdrawalMethodRequest $request, WithdrawalMethodService $withdrawalMethodService): RedirectResponse
    {
        $dataCount = $this->withdrawalMethodRepo->getListWhere(dataLimit: 'all')->count();
        $withdrawalMethod = $this->withdrawalMethodRepo->getFirstWhere(params: ['method_name' => $request['method_name']]);
        $data = $withdrawalMethodService->getProcessedData(request:$request, dataCount:$dataCount);

        if ($withdrawalMethod) {
            $this->withdrawalMethodRepo->update(id: $withdrawalMethod['id'], data: $data);
            $withdrawalMethodID = $withdrawalMethod['id'];
        }else {
            $withdrawalMethodObject = $this->withdrawalMethodRepo->add(data: $data);
            $withdrawalMethodID = $withdrawalMethodObject['id'];
        }

        if ($request->has('is_default') && $request['is_default'] == '1') {
            $this->withdrawalMethodRepo->updateWhereNotIn(params:['id' => [$withdrawalMethodID]], data: ['is_default' => 0]);
        }

        Toastr::success(translate('withdrawal_method_added_successfully'));
        return redirect()->route('admin.vendors.withdraw-method.list');
    }

    public function delete($id): RedirectResponse
    {
        $this->withdrawalMethodRepo->delete(params:['id'=>$id]);
        Toastr::success(translate('withdraw_method_removed_successfully'));
        return back();
    }

    public function updateDefaultStatus(Request $request): JsonResponse
    {
        $withdrawalMethod = $this->withdrawalMethodRepo->getFirstWhere(params: ['id' => $request['id']]);
        $success = 0;
        if($withdrawalMethod['is_active'] && !$withdrawalMethod['is_default']) {
            $success = 1;
            $this->withdrawalMethodRepo->updateWhereNotIn(params:['id' => [$request['id']]], data: ['is_default' => $withdrawalMethod['is_default']]);
            $this->withdrawalMethodRepo->update(id:$request['id'], data: ['is_default' => !$withdrawalMethod['is_default']]);
        }
        return response()->json([
            'success' => $success,
        ], 200);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $withdrawalMethod = $this->withdrawalMethodRepo->getFirstWhere(params: ['id' => $request['id']]);
        $success = 0;
        if(!$withdrawalMethod['is_default']) {
            $success = 1;
            $this->withdrawalMethodRepo->update(
                id:$request['id'],data: ['is_active' => ($withdrawalMethod['is_active'] == 0 || $withdrawalMethod['is_active'] == null) ? 1 : 0]
            );
        }
        return response()->json([
            'success' => $success,
        ], 200);
    }

    public function update(WithdrawalMethodRequest $request, WithdrawalMethodService $withdrawalMethodService): RedirectResponse
    {
        $withdrawalMethod = $this->withdrawalMethodRepo->getFirstWhere(params: ['method_name' => $request['method_name']]);
        if(!isset($withdrawalMethod)) {
            Toastr::error(translate('withdrawal_method_not_found'));
            return back();
        }
        $count = $this->withdrawalMethodRepo->getListWhere(dataLimit: 'all')->count();
        $data = $withdrawalMethodService->getProcessedData(request:$request, dataCount:$count);
        $this->withdrawalMethodRepo->update(id: $withdrawalMethod['id'], data: $data);
        if ($request->has('is_default') && $request['is_default'] == '1') {
            $this->withdrawalMethodRepo->updateWhereNotIn(params:['id' => [$withdrawalMethod['id']]], data: ['is_default' => 0]);
            $this->withdrawalMethodRepo->update(id:$withdrawalMethod['id'], data: ['is_active' => 1]);
        }
        Toastr::success(translate('withdrawal_method_added_successfully'));
        return redirect()->route('admin.vendors.withdraw-method.list');
    }
}
