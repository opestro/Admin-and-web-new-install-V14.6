<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Http\Controllers\BaseController;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SystemController extends BaseController
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
    )
    {
    }

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        // TODO: Implement index() method.
    }

    public function getOrderData(): JsonResponse
    {
        $newOrder = $this->orderRepo->getListWhere(
            filters: ['seller_is' => 'seller', 'seller_id' => auth('seller')->id(), 'checked' => 0],
            dataLimit: 'all'
        )->count();

        return response()->json([
            'success' => 1,
            'data' => ['new_order' => $newOrder]
        ]);
    }

}
