<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Enums\ViewPaths\Admin\SiteMap;
use App\Http\Controllers\BaseController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SiteMapController extends BaseController
{

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getView();
    }


    public function getView(): View
    {
        return view(SiteMap::VIEW[VIEW]);
    }

    public function getFile(): BinaryFileResponse
    {
        SitemapGenerator::create(url('/'))->writeToFile('public/sitemap.xml');
        return response()->download(public_path('sitemap.xml'));
    }
}
