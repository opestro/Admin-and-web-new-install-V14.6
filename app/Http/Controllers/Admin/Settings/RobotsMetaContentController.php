<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\RobotsMetaContentRepositoryInterface;
use App\Enums\ViewPaths\Admin\RobotsMetaContent;
use App\Enums\WebConfigKey;
use App\Http\Controllers\BaseController;
use App\Services\SEOSettingsService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RobotsMetaContentController extends BaseController
{
    public function __construct(
        private readonly RobotsMetaContentRepositoryInterface $robotsMetaContentRepo,
        private readonly SEOSettingsService                   $SEOSettingsService,
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
        return $this->getRobotsMetaContentView();
    }

    public function getRobotsMetaContentView(): View
    {
        $pageListArray = $this->SEOSettingsService->getRobotsMetaContentPages();
        ksort($pageListArray);
        $pageListData = $this->robotsMetaContentRepo->getListWhereNotIn(whereNotIn: ['page_name' => ['default']], dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        $defaultPageData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'default']);
        return view(RobotsMetaContent::ROBOTS_META_CONTENT[VIEW], [
            'pageList' => $pageListArray,
            'pageListData' => $pageListData,
            'defaultPageData' => $defaultPageData,
        ]);
    }

    public function addPage(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::error(translate('you_can_not_update_this_on_demo_mode'));
            return redirect()->route('admin.seo-settings.robots-meta-content.index');
        }
        $getPageInfo = $this->SEOSettingsService->getRobotsMetaContentPageName(name: $request['page_name']);
        if (!empty($getPageInfo)) {
            $robotsMetaContent = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => $request['page_name']]);
            if ($robotsMetaContent) {
                $this->robotsMetaContentRepo->updateByParams(params: ['page_name' => $request['page_name']], data: [
                    'page_title' => $getPageInfo['title'],
                    'page_name' => $request['page_name'],
                    'page_url' => $getPageInfo['route'],
                    "created_at" => now(),
                ]);
            } else {
                $this->robotsMetaContentRepo->add(data: [
                    'page_title' => $getPageInfo['title'],
                    'page_name' => $request['page_name'],
                    'page_url' => $getPageInfo['route'],
                    'canonicals_url' => $getPageInfo['route'],
                    "created_at" => now(),
                ]);
            }
            Toastr::success(translate('successfully_add'));
        }
        return redirect()->route('admin.seo-settings.robots-meta-content.index');
    }

    public function getPageDelete(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::error(translate('you_can_not_update_this_on_demo_mode'));
            return redirect()->back();
        }
        $this->robotsMetaContentRepo->delete(params: ['id' => $request['id']]);
        Toastr::success(translate('successfully_delete'));
        return redirect()->route('admin.seo-settings.robots-meta-content.index');
    }

    public function getPageAddContentView(Request $request): View
    {
        $pageData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => $request['page_name']]);
        $pageName = $request['page_name'];
        return view(RobotsMetaContent::PAGE_CONTENT_VIEW[VIEW], [
            'pageData' => $pageData,
            'pageName' => $pageName,
        ]);
    }

    public function getPageContentUpdate(Request $request): RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::error(translate('you_can_not_update_this_on_demo_mode'));
            return redirect()->route('admin.seo-settings.robots-meta-content.index');
        }
        $getOldData = $this->robotsMetaContentRepo->getFirstWhere(params:['page_name' => $request['page_name']]);
        $getContentData = $this->SEOSettingsService->getRobotsMetaContentData(request: $request,oldData:$getOldData ?? null );
        $this->robotsMetaContentRepo->updateOrInsert(params: ['page_name' => $request['page_name']], data: $getContentData);
        Toastr::success(translate('successfully_update'));
        return redirect()->route('admin.seo-settings.robots-meta-content.index');
    }

}
