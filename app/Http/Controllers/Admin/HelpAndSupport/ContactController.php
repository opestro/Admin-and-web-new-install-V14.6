<?php

namespace App\Http\Controllers\Admin\HelpAndSupport;

use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Enums\ViewPaths\Admin\Contact;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ContactRequest;
use App\Services\ContactService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ContactController extends BaseController
{
    /**
     * @param ContactRepositoryInterface $contactRepo
     */
    public function __construct(
        private readonly ContactRepositoryInterface         $contactRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return \Illuminate\Contracts\View\View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getListView($request);
    }

    public function getListView(Request $request): View
    {
        $contacts = $this->contactRepo->getListWhere(
            orderBy: ['id'=>'desc'],
            searchValue: $request->get('searchValue'),
            dataLimit: getWebConfig('pagination_limit')
        );
        return view(Contact::LIST[VIEW], compact('contacts'));
    }

    public function getListByFilter(Request $request):JsonResponse
    {
        $contacts = $this->contactRepo->getListWhere(
            orderBy: ['id'=>'desc'],
            searchValue: $request->get('searchValue'),
            filters: ['reply' => $request['status']],
            dataLimit: getWebConfig('pagination_limit')
        );
        return response()->json(
            [
                'view' => view(Contact::FILTER[VIEW] , compact('contacts'))->render(),
                'count' => count($contacts),
            ]
        );
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->contactRepo->update(id:$id, data: ['seen'=>1]);
        Toastr::success(translate('message_checked').'.');
        return redirect()->route('admin.contact.list');
    }

    public function getView($id): View
    {
        $contact = $this->contactRepo->getFirstWhere(params: ['id'=>$id]);
        return view(Contact::VIEW[VIEW], compact('contact'));
    }

    public function delete(Request $request): JsonResponse
    {
        $this->contactRepo->delete(params: ['id'=>$request['id']]);
        return response()->json([
            'message' => translate('Delete_successfully')
        ], 200);
    }

    public function add(ContactRequest $request, ContactService $contactService): JsonResponse
    {
        $dataArray = $contactService->getAddData(request: $request);
        $this->contactRepo->add(data: $dataArray);
        return response()->json(['success' => 'Your_Message_Send_Successfully']);
    }

    public function sendMail(Request $request, $id, ContactService $contactService): RedirectResponse
    {
        $contact = $this->contactRepo->getFirstWhere(params: ['id'=>$id]);
        $data = array('body' => $request['mail_body']);

        $emailServices_smtp = getWebConfig(name: 'mail_config');
        if ($emailServices_smtp['status'] == 0) {
            $emailServices_smtp = getWebConfig(name: 'mail_config_sendgrid');
        }

        if ($emailServices_smtp['status'] == 1) {
            try {
                $dataArray = $contactService->getMailData(request: $request, data: $data, contact: $contact, companyName: getWebConfig(name: 'company_name'));
                $this->contactRepo->update(id:$id, data: $dataArray);
                Toastr::success(translate('mail_sent_successfully'));
            } catch (Throwable $th) {
                Toastr::error(translate('This_Mail_Could_Not_be_Sent').'.');
                Toastr::info(translate('please_go_to_3rd_Party').' > '.translate('Mail_Config').','.translate('and_check_if_you’ve_enabled_and_saved_your_settings_properly'));
            }
        } else {
            Toastr::error(translate('Configure_your_mail_setup_first'));
        }
        return back();
    }
}
