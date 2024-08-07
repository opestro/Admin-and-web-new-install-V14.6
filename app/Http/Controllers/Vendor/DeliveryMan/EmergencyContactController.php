<?php

namespace App\Http\Controllers\Vendor\DeliveryMan;

use App\Contracts\Repositories\EmergencyContactRepositoryInterface;
use App\Enums\ViewPaths\Vendor\Dashboard;
use App\Enums\ViewPaths\Vendor\EmergencyContact;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Vendor\EmergencyContactRequest;
use App\Services\DeliveryManService;
use App\Services\EmergencyContactService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class EmergencyContactController extends BaseController
{
    /**
     * @param EmergencyContactRepositoryInterface $emergencyContactRepo
     * @param EmergencyContactService $emergencyContactService
     * @param DeliveryManService $deliveryManService
     */
    public function __construct(
        private readonly EmergencyContactRepositoryInterface $emergencyContactRepo,
        private readonly EmergencyContactService $emergencyContactService,
        private readonly DeliveryManService $deliveryManService,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return \Illuminate\Contracts\View\View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     */
    public function index(?Request $request, string $type = null): \Illuminate\Contracts\View\View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        if (!$this->deliveryManService->checkConditions()){
            return redirect()->route(Dashboard::INDEX[ROUTE]);
        }
        return $this->getListView();
    }

    /**
     * @return View|RedirectResponse
     */
    public function getListView():View|RedirectResponse
    {
        $contacts = $this->emergencyContactRepo->getListWhere(filters:['user_id'=>auth('seller')->id()],dataLimit: getWebConfig(name: 'pagination_limit'));
        return view(EmergencyContact::INDEX[VIEW],compact('contacts'));
    }

    /**
     * @param EmergencyContactRequest $request
     * @return RedirectResponse
     */
    public function add(EmergencyContactRequest $request):RedirectResponse
    {
        $this->emergencyContactRepo->add(data:$this->emergencyContactService->getEmergencyContactData(request:$request,id:auth('seller')->id() ));
        Toastr::success(translate('emergency_contact_added_successfully').'!');
        return redirect()->back();
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatus(Request $request):JsonResponse
    {
        $contact =  $this->emergencyContactRepo->getFirstWhere(params:['user_id' => auth('seller')->id(), 'id' => $request['id']]);
        $status = $this->emergencyContactRepo->update(id:$contact['id'],data: ['status' => $request['status'] ?? 0]);
        if ($status === true){
            return response()->json(['message' => translate('contact_status_changed_successfully').'!']);
        }else{
            return response()->json([
                'message' => translate('contact_status_change_failed').'!',
                'fail' => 1]);
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request):RedirectResponse
    {
        $delete =  $this->emergencyContactRepo->delete(params:['user_id' => auth('seller')->id(), 'id' => $request['id']]);
        if ($delete === true) {
            Toastr::success(translate('emergency_contact_deleted_successfully').'!');
        } else {
            Toastr::error(translate('emergency_contact_delete_failed').'!');
        }
        return redirect()->back();
    }
}
