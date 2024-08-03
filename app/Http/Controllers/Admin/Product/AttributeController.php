<?php

namespace App\Http\Controllers\Admin\Product;

use App\Contracts\Repositories\AttributeRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ViewPaths\Admin\Attribute;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Traits\PaginatorTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttributeController extends BaseController
{
    use PaginatorTrait;

    public function __construct(
        private readonly AttributeRepositoryInterface       $attributeRepo,
        private readonly TranslationRepositoryInterface     $translationRepo,
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

    public function getList(): JsonResponse
    {
        $attributes = $this->attributeRepo->getList(dataLimit: 'all');
        return response()->json(AttributeResource::collection($attributes));
    }

    public function getAddView(Request $request): View
    {
        $attributes = $this->attributeRepo->getListWhere(searchValue: $request->get('searchValue'), dataLimit: getWebConfig(name: 'pagination_limit'));
        $language = getWebConfig(name: 'pnc_language') ?? null;
        $defaultLanguage = $language[0];
        return view(Attribute::LIST[VIEW], compact('attributes', 'language', 'defaultLanguage'));
    }

    public function getUpdateView(string|int $id): View
    {
        $attribute = $this->attributeRepo->getFirstWhere(params:['id'=>$id], relations: ['translations']);
        $language = getWebConfig(name: 'pnc_language') ?? null;
        $defaultLanguage = $language[0];
        return view(Attribute::UPDATE[VIEW], compact('attribute', 'language', 'defaultLanguage'));
    }

    public function add(AttributeRequest $request): JsonResponse|RedirectResponse
    {
        $dataArray = [
            'name' => $request['name'][array_search('en', $request['lang'])],
        ];

        $savedAttributes = $this->attributeRepo->add(data:$dataArray);
        $this->translationRepo->add(request:$request, model:'App\Models\Attribute', id:$savedAttributes->id);

        Toastr::success(translate('attribute_added_successfully'));
        return back();
    }

    public function update(AttributeRequest $request): RedirectResponse
    {
        $dataArray = [
            'name' => $request['name'][array_search('en', $request['lang'])],
        ];

        $this->attributeRepo->update(id:$request['id'], data:$dataArray);
        $this->translationRepo->update(request:$request, model:'App\Models\Attribute', id:$request['id']);

        Toastr::success(translate('attribute_updated_successfully'));
        return back();
    }

    public function delete(Request $request): JsonResponse
    {
        $this->attributeRepo->delete(params:['id'=>$request['id']]);
        $this->translationRepo->delete(model:'App\Models\Attribute', id:$request['id']);
        return response()->json(['message'=> translate('attribute_deleted_successfully')]);
    }

}
