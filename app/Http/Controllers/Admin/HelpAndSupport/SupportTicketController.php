<?php

namespace App\Http\Controllers\Admin\HelpAndSupport;

use App\Contracts\Repositories\SupportTicketConvRepositoryInterface;
use App\Contracts\Repositories\SupportTicketRepositoryInterface;
use App\Enums\ViewPaths\Admin\SupportTicket;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SupportTicketRequest;
use App\Repositories\SupportTicketRepository;
use App\Services\SupportTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Brian2694\Toastr\Facades\Toastr;

class SupportTicketController extends BaseController
{
    /**
     * @param SupportTicketRepository $supportTicketRepo
     */
    public function __construct(
        private readonly SupportTicketRepositoryInterface $supportTicketRepo,
        private readonly SupportTicketConvRepositoryInterface $supportTicketConvRepo,
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
        $tickets = $this->supportTicketRepo->getListWhere(
            orderBy: ['id'=>'desc'],
            searchValue: $request->get('searchValue'),
            filters: ['priority'=>$request['priority'], 'status'=>$request['status']],
            dataLimit: getWebConfig('pagination_limit')
        );
        return view(SupportTicket::LIST[VIEW], compact('tickets'));
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $ticket = $this->supportTicketRepo->getFirstWhere(params:['id'=>$request['id']]);
        $status = $ticket['status'] == 'open' ? 'close':'open';
        $this->supportTicketRepo->update(id:$ticket['id'], data: ['status' => $status]);
        return response()->json([
            'message' => translate('status_updated_successfully')
        ], 200);
    }

    public function getView($id): View
    {
        $supportTicket = $this->supportTicketRepo->getListWhere(filters: ['id'=>$id], relations: ['conversations'], dataLimit: 'all');
        return view(SupportTicket::VIEW[VIEW], compact('supportTicket'));
    }

    public function reply(SupportTicketRequest $request, SupportTicketService $supportTicketService): RedirectResponse
    {
        if ($request['image'] == null && $request['replay'] == null) {
            Toastr::warning(translate('type_something').'!');
            return back();
        }
        $dataArray = $supportTicketService->getAddData(request: $request);
        $this->supportTicketConvRepo->add(data: $dataArray);
        return back();
    }

}
