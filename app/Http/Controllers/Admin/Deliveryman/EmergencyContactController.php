<?php

namespace App\Http\Controllers\Admin\Deliveryman;

use App\Contracts\Repositories\EmergencyContactRepositoryInterface;
use App\Enums\ViewPaths\Admin\EmergencyContact;
use App\Enums\WebConfigKey;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\EmergencyContactRequest;
use App\Services\EmergencyContactService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmergencyContactController extends BaseController
{
    /**
     * @param EmergencyContactRepositoryInterface $emergencyContactRepo
     */
    public function __construct(
        private readonly EmergencyContactRepositoryInterface    $emergencyContactRepo,
        private readonly EmergencyContactService    $emergencyContactService,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): \Illuminate\View\View
    {
        return $this->getListView($request);
    }

    public function getListView(Request $request): View
    {
        $contacts = $this->emergencyContactRepo->getListWhere(
            orderBy: ['id'=>'desc'],
            filters: ['user_id'=>0],
            dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT)
        );
        return view(EmergencyContact::LIST[VIEW], compact('contacts'));
    }

    public function add(EmergencyContactRequest $request): RedirectResponse
    {
        $this->emergencyContactRepo->add(data:$this->emergencyContactService->getEmergencyContactData(request:$request,id:0 ));
        Toastr::success(translate('emergency_contact_added_successfully'));
        return back();
    }
    public function getUpdateView($id):JsonResponse
    {
        $emergencyContact = $this->emergencyContactRepo->getFirstWhere(params: ['id'=>$id]);
        return response()->json(['view'=>view(EmergencyContact::UPDATE[VIEW],compact('emergencyContact'))->render()]);

    }
    public function update(EmergencyContactRequest $request,$id): RedirectResponse
    {
        $this->emergencyContactRepo->update(id:$id,data:$this->emergencyContactService->getEmergencyContactUpdateData(request:$request));
        Toastr::success(translate('emergency_contact_update_successfully'));
        return back();
    }

    public function delete(Request $request): RedirectResponse
    {
        $this->emergencyContactRepo->delete(params: ['user_id' => 0, 'id' => $request['id']]);
        Toastr::success(translate('emergency_contact_deleted_successfully'));
        return back();
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $this->emergencyContactRepo->updateWhere(params: ['user_id' => 0, 'id' => $request['id']], data: ['status' => $request->get('status', 0)]);
        Toastr::success(translate('contact_status_changed_successfully'));
        return response()->json([ 'message' => translate('contact_status_changed_successfully')]);
    }



}
