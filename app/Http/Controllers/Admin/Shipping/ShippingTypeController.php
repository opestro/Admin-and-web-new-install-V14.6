<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Contracts\Repositories\ShippingTypeRepositoryInterface;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ShippingTypeRequest;
use App\Services\ShippingTypeService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ShippingTypeController extends BaseController
{
    /**
     * @param ShippingTypeRepositoryInterface $shippingTypeRepo
     * @param ShippingTypeService $shippingTypeService
     */
    public function __construct(
        private readonly ShippingTypeRepositoryInterface $shippingTypeRepo,
        private readonly ShippingTypeService $shippingTypeService
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     */
    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return null ;
    }

    /**
     * @param ShippingTypeRequest $request
     * @return JsonResponse
     */
    public function addOrUpdate(ShippingTypeRequest $request) :JsonResponse
    {
        $shippingType = $this->shippingTypeRepo->getFirstWhere(['seller_id' => 0]);
        if (!empty($shippingType)) {
            $this->shippingTypeRepo->update(
                id: $shippingType['id'],
                data: $this->shippingTypeService->getShippingTypeDataForUpdate(request: $request)
            );
        } else {

            $this->shippingTypeRepo->add(
                data: $this->shippingTypeService->getShippingTypeDataForAdd(
                    request: $request,
                    id: 0)
            );
        }
        return response()->json();
    }
}
