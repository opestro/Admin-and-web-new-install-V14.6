<?php

namespace App\Http\Controllers\Web\Shop;

use App\Contracts\Repositories\ShopFollowerRepositoryInterface;
use App\Http\Controllers\BaseController;
use App\Services\ShopFollowerService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopFollowerController extends BaseController
{
    /**
     * @param ShopFollowerRepositoryInterface $shopFollowerRepo
     * @param ShopFollowerService $shopFollowerService
     */
    public function __construct(
        private readonly ShopFollowerRepositoryInterface $shopFollowerRepo,
        private readonly ShopFollowerService $shopFollowerService,
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

    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function followOrUnfollowShop($request):JsonResponse
    {
        if (auth('customer')->check()) {
            $customerId = auth('customer')->id();
            $shopFollower = $this->shopFollowerRepo->getFirstWhere(params: ['user_id' => $customerId ,'shop_id' => $request['shop_id']]);
            if ($shopFollower) {
                $this->shopFollowerRepo->delete(params:['id' => $shopFollower['id']]);
                $followers = $this->shopFollowerRepo->getListWhere(filters: ['shop_id' => $request['shop_id']],dataLimit: 'all')->count();
                $text = translate("follow");
                $message = translate("unfollow_successfully")."!";
                $value = 2 ;
            }else{
                $this->shopFollowerRepo->add(
                  data: $this->shopFollowerService->getAddData($customerId,shopId:$request['shop_id'],)
                );
                $followers = $this->shopFollowerRepo->getListWhere(filters: ['shop_id' => $request['shop_id']],dataLimit: 'all')->count();
                $text = translate("Unfollow");
                $message = translate("follow_successfully")."!";
                $value = 1 ;
            }
            return response()->json([
                'text' => $text,
                'message' => $message,
                'value' => $value,
                'followers' => $followers,
            ]);
        }else{
            return response()->json([
                'message' => translate("login_first"),
                'value' => 0,
            ]);
        }
    }

}
