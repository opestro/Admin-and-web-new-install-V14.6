<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Contracts\Repositories\AdminRepositoryInterface;
use App\Contracts\Repositories\AdminRoleRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Customer as CustomerExport;
use App\Enums\ViewPaths\Admin\Employee;
use App\Enums\WebConfigKey;
use App\Exports\EmployeeListExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AdminAddRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Services\AdminService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Traits\PaginatorTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EmployeeController extends BaseController
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
        return $this->getListView($request);
    }

    public function getListView(Request $request): View
    {
        $employee_roles = $this->adminRoleRepo->getEmployeeRoleList(dataLimit: 'all');
        $employees = $this->adminRepo->getEmployeeListWhere(
            orderBy:['id'=>'desc'],
            searchValue: $request['searchValue'],
            filters:['admin_role_id' => $request['admin_role_id'] ?? 'all'] ,
            relations: ['role'],
            dataLimit:getWebConfig(name: WebConfigKey::PAGINATION_LIMIT)
        );
        return view(Employee::LIST[VIEW], compact('employees','employee_roles'));
    }

    public function getAddView(): View
    {
        $employee_roles = $this->adminRoleRepo->getEmployeeRoleList(dataLimit: 'all');
        return view(Employee::ADD[VIEW], compact('employee_roles'));
    }

    public function add(AdminAddRequest $request, AdminService $adminService): RedirectResponse
    {
        if ($request['role_id'] == 1) {
            Toastr::warning(translate('access_denied'));
            return back();
        }

        $data = [
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'admin_role_id' => $request['role_id'],
            'identify_type' => $request['identify_type'],
            'identify_number' => $request['identify_number'],
            'identify_image' => $adminService->getIdentityImages(request: $request),
            'password' => bcrypt($request['password']),
            'status'=>1,
            'image' => $adminService->getProceedImage(request:$request),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $this->adminRepo->add(data: $data);
        Toastr::success(translate('employee_added_successfully'));
        return redirect()->route('admin.employee.list');
    }

    public function exportList(Request $request): BinaryFileResponse
    {
        $employees = $this->adminRepo->getEmployeeListWhere(
            searchValue: $request['searchValue'],
            filters: ['admin_role_id' => $request['role']],
            relations: ['role'],
            dataLimit: 'all');
        $active = $employees->where('status',1)->count();
        $inactive = $employees->where('status',0)->count();

        $filter = 'all';
        if($request->has('role') &&  $request['role'] != 'all') {
            $filter = $this->adminRoleRepo->getFirstWhere(params:['id'=>$request['role']])['name'];
        }

        $data = [
            'employees' => $employees,
            'search' => $request['searchValue'],
            'active' => $active,
            'inactive' => $inactive,
            'filter' => $filter
        ];

        return Excel::download(new EmployeeListExport($data), CustomerExport::EMPLOYEES_LIST_XLSX);
    }

    public function getView(Request $request): View
    {
        $employee = $this->adminRepo->getFirstWhere(params:['id' => $request['id']], relations:['role']);
        return view(Employee::VIEW[VIEW], compact('employee'));
    }

    public function getUpdateView($id): View
    {
        $employee = $this->adminRepo->getFirstWhere(params:['id' => $id]);
        $adminRoles = $this->adminRoleRepo->getEmployeeRoleList(dataLimit: 'all');
        return view(Employee::UPDATE[VIEW], compact('adminRoles', 'employee'));
    }

    public function update(AdminUpdateRequest $request, AdminService $adminService): RedirectResponse
    {
        if ($request['role_id'] == 1) {
            Toastr::warning(translate('access_denied'));
            return back();
        }
        $employee = $this->adminRepo->getFirstWhere(params:['id' => $request['id']]);
        $identity_image = [];
        if ($request->file('identity_image')) {
            $identity_image = $adminService->getIdentityImages(request: $request, oldImages: $employee);
        }

        $data = [
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'admin_role_id' => $request['role_id'],
            'password' => $request['password'] ? bcrypt($request['password']) : $employee['password'],
            'image' => $request->file('image') ? $adminService->getProceedImage(request:$request, oldImage:$employee['image']) : $employee['image'],
            'identify_image' => $request->file('identity_image') ? $identity_image : $employee['identify_image'],
            'identify_type' => $request['identify_type'],
            'identify_number' => $request['identify_number'],
            'updated_at' => now(),
        ];

        $this->adminRepo->update(id:$request['id'], data: $data);
        Toastr::success(translate('employee_updated_successfully'));
        return redirect()->route('admin.employee.list');
    }

    public function updateStatus(Request $request): RedirectResponse|JsonResponse
    {
        $this->adminRepo->update(id:$request['id'], data:['status'=> $request->get('status', 0)]);
        if($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => translate('Status_Updated'),
            ]);
        }
        Toastr::success(translate('Status_Updated'));
        return back();
    }
}
