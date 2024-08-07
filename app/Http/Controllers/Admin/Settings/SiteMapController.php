<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Enums\ViewPaths\Admin\SiteMap;
use App\Enums\WebConfigKey;
use App\Http\Controllers\BaseController;
use App\Traits\FileManagerTrait;
use App\Utils\FileManagerLogic;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Sitemap\SitemapGenerator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Collection;

class SiteMapController extends BaseController
{
    use FileManagerTrait;

    public function __construct(
        private readonly ProductRepositoryInterface $productRepo,
        private readonly ShopRepositoryInterface    $shopRepo,
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
        return $this->getSitemapView();
    }

    public function getSitemapView(): View
    {
        if (!File::isDirectory(storage_path('app/public/sitemap'))) {
            File::makeDirectory(storage_path('app/public/sitemap'));
        }
        $siteMapList = self::getAllSitemapFiles(directory: storage_path('app/public/sitemap'));
        $collection = new Collection($siteMapList);
        $dataLimit = getWebConfig(name: WebConfigKey::PAGINATION_LIMIT);
        $currentPage = $offset ?? Paginator::resolveCurrentPage('page');
        $totalSize = $collection->count();
        $results = $collection->forPage($currentPage, $dataLimit);
        $paginatedData = new LengthAwarePaginator(items: $results, total: $totalSize, perPage: $dataLimit, currentPage: $currentPage, options: [
            'path' => Paginator::resolveCurrentPath(),
        ]);

        return view(SiteMap::SITEMAP[VIEW], [
            'siteMapList' => $paginatedData,
        ]);
    }

    public function getGenerateAndDownload(Request $request): JsonResponse
    {
        $filePath = self::processGenerateAndDownload(action: 'generate');
        return response()->json([
            'status' => 1,
            'filePath' => $filePath,
            'fileName' => $filePath,
        ]);
    }

    public function getGenerateAndUpload(Request $request): BinaryFileResponse|RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::error(translate('you_can_not_update_this_on_demo_mode'));
            return redirect()->back();
        }
        self::processGenerateAndDownload(action: 'upload');
        return redirect()->route('admin.seo-settings.sitemap');
    }

    public function getAllSitemapFiles($directory): array
    {
        $files = scandir($directory);
        $fileDetails = [];

        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $filePath = $directory . '/' . $file;
                $createdTime = Carbon::createFromTimestamp(filectime($filePath));
                $modifiedTime = Carbon::createFromTimestamp(filemtime($filePath));
                $fileDetails[] = [
                    'name' => $file,
                    'size' => FileManagerLogic::formatBytes(filesize($filePath)),
                    'path' => asset('storage/app/public/sitemap/' . $file),
                    'created_at' => $createdTime,
                    'modified_at' => $modifiedTime,
                ];
            }
        }
        usort($fileDetails, function($a, $b) {
            return $b['modified_at'] <=> $a['modified_at'];
        });
        return $fileDetails;
    }

    public function processGenerateAndDownload(string $action)
    {
        Artisan::call('file:permission');

        $currentTime = Carbon::now();
        $generator = SitemapGenerator::create(url('/'));
        $generator->shouldCrawl(function ($url) {
            return strpos($url, '/products') === false
                && strpos($url, '/product') === false
                && strpos($url, '/shopView') === false;
        });

        $productsUrl = $this->productRepo->getWebListWithScope(scope: 'active', dataLimit: 'all')->pluck('slug');
        foreach ($productsUrl as $productSingleUrl) {
            $urlObject = Url::create(route('product', ['slug' => $productSingleUrl]))
                ->setLastModificationDate($currentTime)
                ->setChangeFrequency('weekly')
                ->setPriority(0.8);
            $generator->getSitemap()->add($urlObject);
        }

        $urlObject = Url::create(route('vendors'))
            ->setLastModificationDate($currentTime)
            ->setChangeFrequency('weekly')
            ->setPriority(0.8);
        $generator->getSitemap()->add($urlObject);

        $urlObject = Url::create(route('shopView', ['id' => 0]))
            ->setLastModificationDate($currentTime)
            ->setChangeFrequency('weekly')
            ->setPriority(0.8);
        $generator->getSitemap()->add($urlObject);

        $shopsUrl = $this->shopRepo->getListWithScope(scope: 'active', dataLimit: 'all')->pluck('id');
        foreach ($shopsUrl as $shopSingleUrl) {
            $urlObject = Url::create(route('shopView', ['id' => $shopSingleUrl]))
                ->setLastModificationDate($currentTime)
                ->setChangeFrequency('weekly')
                ->setPriority(0.8);
            $generator->getSitemap()->add($urlObject);
        }

        $policyPagesUrl = [
            route('brands'),
            route('vendors'),
            route('home'),
            route('categories'),
            route('about-us'),
            route('contacts'),
            route('helpTopic'),
            route('refund-policy'),
            route('return-policy'),
            route('privacy-policy'),
            route('cancellation-policy'),
            route('terms'),
            route('products'),
            route('discounted-products'),
            route('track-order.index'),
        ];
        foreach ($policyPagesUrl as $policyPageSingleUrl) {
            $urlObject = Url::create($policyPageSingleUrl)
                ->setLastModificationDate($currentTime)
                ->setChangeFrequency('weekly')
                ->setPriority(0.8);
            $generator->getSitemap()->add($urlObject);
        }

        $generator->hasCrawled(function (Url $url) use ($currentTime) {
            $url->setPriority(0.8)
                ->setLastModificationDate($currentTime)
                ->setChangeFrequency('weekly');
            return $url;
        });

        $directory = storage_path('app/public/sitemap');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }

        $fileName = 'sitemap-' . Str::slug(Carbon::now()) . '.xml';
        $generator->writeToFile(storage_path('app/public/sitemap/' . $fileName));

        if ($action == 'upload') {
            $generator->writeToFile(public_path('sitemap.xml'));
            $generator->writeToFile(base_path('sitemap.xml'));
        }else{
            return dynamicStorage('storage/app/public/sitemap/' . $fileName);
        }
    }

    public function getDownload(Request $request): BinaryFileResponse|RedirectResponse
    {
        if ($request['path']) {
            $fileName = base64_decode($request['path']);
            if (File::exists(storage_path('app/public/sitemap/' . $fileName))) {
                return response()->download(storage_path('app/public/sitemap/' . $fileName));
            }
        }
        return redirect()->route('admin.seo-settings.sitemap');
    }

    public function getUpload(Request $request): RedirectResponse
    {
        if ($request->file('xml_file')->getClientOriginalExtension() != 'xml') {
            Toastr::error(translate('Please_upload_a_xml_file'));
        } elseif ($request->file('xml_file')) {
            $fileName = 'sitemap-' . Str::slug(Carbon::now()) . '.xml';
            $request->file('xml_file')->storeAs('public/sitemap', $fileName);
            if (File::exists(public_path('sitemap.xml'))) {
                File::delete(public_path('sitemap.xml'));
            }
            if (File::exists(base_path('sitemap.xml'))) {
                File::delete(base_path('sitemap.xml'));
            }
            File::put(public_path('sitemap.xml'), $request->file('xml_file'));
            File::put(base_path('sitemap.xml'), $request->file('xml_file'));
            Toastr::success(translate('successfully_upload'));
        }
        return redirect()->route('admin.seo-settings.sitemap');
    }

    public function getDelete(Request $request): RedirectResponse
    {
        if ($request['path']) {
            $fileName = base64_decode($request['path']);
            if (File::exists(storage_path('app/public/sitemap/' . $fileName))) {
                File::delete(storage_path('app/public/sitemap/' . $fileName));
                Toastr::success(translate('successfully_delete'));
            }
        }
        return redirect()->route('admin.seo-settings.sitemap');
    }
}
