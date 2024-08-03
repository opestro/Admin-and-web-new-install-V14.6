<?php

namespace App\Http\Controllers\Admin\Product;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Category as CategoryExport;
use App\Enums\ViewPaths\Admin\Category;
use App\Exports\CategoryListExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\CategoryAddRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Traits\PaginatorTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CategoryController extends BaseController
{
    use PaginatorTrait;

    public function __construct(
        private readonly CategoryRepositoryInterface        $categoryRepo,
        private readonly ProductRepositoryInterface        $productRepo,
        private readonly ProductService        $productService,
        private readonly TranslationRepositoryInterface     $translationRepo,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getAddView($request);
    }

    public function getAddView(Request $request): View
    {
        $categories = $this->categoryRepo->getListWhere(orderBy: ['id'=>'desc'], searchValue: $request->get('searchValue'), filters: ['position' => 0], dataLimit: getWebConfig(name: 'pagination_limit'));
        $languages = getWebConfig(name: 'pnc_language') ?? null;
        $defaultLanguage = $languages[0];
        return view(Category::LIST[VIEW], [
            'categories' => $categories,
            'languages' => $languages,
            'defaultLanguage' => $defaultLanguage,
        ]);
    }

    public function getUpdateView(string|int $id): View|RedirectResponse
    {
        $category = $this->categoryRepo->getFirstWhere(params:['id'=>$id], relations: ['translations']);
        $languages = getWebConfig(name: 'pnc_language') ?? null;
        $defaultLanguage = $languages[0];
        return view(Category::UPDATE[VIEW], [
            'category' => $category,
            'languages' => $languages,
            'defaultLanguage' => $defaultLanguage,
        ]);
    }

    public function add(CategoryAddRequest $request, CategoryService $categoryService): RedirectResponse
    {
        $dataArray = $categoryService->getAddData(request:$request);
        $savedCategory = $this->categoryRepo->add(data:$dataArray);
        $this->translationRepo->add(request:$request, model:'App\Models\Category', id:$savedCategory->id);
        Toastr::success(translate('category_added_successfully'));
        return back();
    }

    public function update(CategoryUpdateRequest $request, CategoryService $categoryService): RedirectResponse
    {
        $category = $this->categoryRepo->getFirstWhere(params:['id'=>$request['id']]);
        $dataArray = $categoryService->getUpdateData(request:$request, data: $category);
        $this->categoryRepo->update(id:$request['id'], data:$dataArray);
        $this->translationRepo->update(request:$request, model:'App\Models\Category', id:$request['id']);

        Toastr::success(translate('category_updated_successfully'));
        return back();
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $data = [
            'home_status' => $request->get('home_status', 0),
        ];
        $this->categoryRepo->update(id: $request['id'], data:$data);
        return response()->json(['success' => 1,], 200);
    }

    public function delete(Request $request, CategoryService $categoryService): RedirectResponse
    {
        $this->productRepo->updateByParams(params:['category_id'=>$request['id']],data:['category_ids'=>json_encode($this->productService->getCategoriesArray(request: $request)),'category_id' =>$request['category_id'],'sub_category_id'=>null,'sub_sub_category_id'=>null]);
        $category = $this->categoryRepo->getFirstWhere(params: ['id'=>$request['id']], relations: ['childes.childes']);
        $categoryService->deleteImages(data:$category);
        $this->categoryRepo->delete(params: ['id'=>$request['id']]);
        Toastr::success(translate('deleted_successfully'));
        return redirect()->back();
    }

    public function getExportList(Request $request): BinaryFileResponse
    {
        $categories = $this->categoryRepo->getListWhere(orderBy: ['id'=>'desc'], searchValue: $request->get('searchValue'), filters: ['position' => 0], dataLimit: 'all');
        $active = $categories->where('home_status',1)->count();
        $inactive = $categories->where('home_status',0)->count();
        return Excel::download(new CategoryListExport([
            'categories' => $categories,
            'title' => 'category',
            'search' => $request['searchValue'],
            'active' => $active,
            'inactive' => $inactive,
        ]), CategoryExport::CATEGORY_LIST_XLSX
        );
    }
}
