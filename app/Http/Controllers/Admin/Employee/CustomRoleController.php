<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Contracts\Repositories\AdminRepositoryInterface;
use App\Contracts\Repositories\AdminRoleRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Employee;
use App\Enums\ViewPaths\Admin\CustomRole;
use App\Exports\EmployeeRoleListExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\CustomRoleRequest;
use App\Traits\PaginatorTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Facades\Excel;

class CustomRoleController extends BaseController
{
    use PaginatorTrait;

    public function __construct(
        private readonly AdminRepositoryInterface $adminRepo,
        private readonly AdminRoleRepositoryInterface $adminRoleRepo,
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
        return $this->getAddView($request);
    }

    public function getAddView(Request $request): View
    {
        $roles = $this->adminRoleRepo->getEmployeeRoleList(
            orderBy: ['id'=>'desc'],
            searchValue: $request['searchValue'],
            filters: ['admin_role_id' => $request['role']],
            dataLimit: 'all');
        return view(CustomRole::ADD[VIEW], compact('roles'));
    }

    public function add(CustomRoleRequest $request): RedirectResponse
    {
        $data = [
            'name' => $request['name'],
            'module_access' => json_encode($request['modules']),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $this->adminRoleRepo->add(data: $data);
        Toastr::success(translate('role_added_successfully'));
        return back();
    }

    public function getUpdateView($id): View
    {
        $role = $this->adminRoleRepo->getFirstWhere(params: ['id'=>$id]);
        return view(CustomRole::UPDATE[VIEW], compact('role'));
    }

    public function update(CustomRoleRequest $request): RedirectResponse
    {
        $data = [
            'name' => $request['name'],
            'module_access' => json_encode($request['modules']),
        ];
        $this->adminRoleRepo->update(id:$request['id'], data: $data);
        Toastr::success(translate('role_updated_successfully'));
        return back();
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $this->adminRoleRepo->update(id:$request['id'], data: ['status'=>$request->get('status', 0)]);
        return response()->json([
            'success' => 1,
            'message' => translate('status_updated_successfully'),
        ], 200);

    }

    public function exportList(Request $request): BinaryFileResponse
    {
        $roles = $this->adminRoleRepo->getEmployeeRoleList(
            orderBy: ['id'=>'desc'],
            searchValue: $request['searchValue'],
            filters: ['admin_role_id' => $request['role']],
            dataLimit: 'all');

        return Excel::download(new EmployeeRoleListExport([
            'roles' => $roles,
            'searchValue' => $request['searchValue'],
            'active' => count($roles->where('status',1)),
            'inActive' => count($roles->where('status',0)),
        ]), Employee::EMPLOYEE_ROLE_LIST);
    }

    public function delete(Request $request): JsonResponse
    {
        $this->adminRoleRepo->delete(params:['id'=>$request['id']]);
        return response()->json([
            'success' => 1,
            'message' => translate('role_deleted_successfully')
        ], 200);
    }

}
