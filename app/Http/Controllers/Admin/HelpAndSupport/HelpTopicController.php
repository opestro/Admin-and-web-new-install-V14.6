<?php

namespace App\Http\Controllers\Admin\HelpAndSupport;

use App\Contracts\Repositories\HelpTopicRepositoryInterface;
use App\Enums\ViewPaths\Admin\HelpTopic;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\HelpTopicAddRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HelpTopicController extends BaseController
{

    public function __construct(private readonly HelpTopicRepositoryInterface $helpTopicRepo)
    {
    }

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getListView();
    }

    public function getListView(): View
    {
        $helps = $this->helpTopicRepo->getListWhere(orderBy: ['id' => 'desc'], filters: ['type' => 'default'],dataLimit: 'all');
        return view(HelpTopic::LIST[VIEW], compact('helps'));
    }

    public function add(HelpTopicAddRequest $request): RedirectResponse
    {
        $this->helpTopicRepo->add(data: [
            'type' => $request['type'] ?? 'default',
            'question' => $request['question'],
            'answer' => $request['answer'],
            'status' => $request->get('status', 0),
            'ranking' => $request['ranking'],
        ]);
        Toastr::success(translate('FAQ_added_successfully'));
        return back();
    }

    public function updateStatus($id): JsonResponse
    {
        $helpTopic = $this->helpTopicRepo->getFirstWhere(params: ['id'=>$id]);
        $this->helpTopicRepo->update(id: $id, data: [
            'status' => $helpTopic['status'] ? 0:1,
        ]);
        return response()->json(['success' => translate('status_change_successfully')]);
    }

    public function getUpdateResponse($id): JsonResponse
    {
        $helpTopic = $this->helpTopicRepo->getFirstWhere(params: ['id'=>$id]);
        return response()->json($helpTopic);
    }

    public function update(HelpTopicAddRequest $request, $id): RedirectResponse
    {
        $this->helpTopicRepo->update(id: $id, data: [
            'question' => $request['question'],
            'answer' => $request['answer'],
            'ranking' => $request['ranking'],
            'status' => $request->get('status', 0),
        ]);
        Toastr::success(translate('FAQ_Update_successfully'));
        return back();
    }

    public function delete(Request $request): JsonResponse
    {
        $this->helpTopicRepo->delete(params: ['id'=>$request['id']]);
        return response()->json();
    }

}
