<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Enums\ViewPaths\Admin\Language;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\LanguageRequest;
use App\Services\LanguageService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LanguageController extends BaseController
{

    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
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
        return $this->getListView();
    }

    public function getListView(): View
    {
        return view(Language::LIST[VIEW]);
    }


    public function add(LanguageRequest $request, LanguageService $languageService): RedirectResponse
    {
        $language = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'language']);
        $dataArray = $languageService->getAddData(request: $request, language: $language);
        $this->businessSettingRepo->updateOrInsert(type: 'language', value: $dataArray['languages']);
        $this->businessSettingRepo->updateOrInsert(type: 'pnc_language', value: json_encode($dataArray['codes']));
        Toastr::success(translate('Language_Added'));
        return back();
    }

    public function updateStatus(Request $request, LanguageService $languageService): bool
    {
        $language = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'language']);
        $languageArray = $languageService->getStatusData(request: $request, language: $language);
        return $this->businessSettingRepo->updateOrInsert(type: 'language', value: $languageArray);
    }

    public function updateDefaultStatus(Request $request, LanguageService $languageService): JsonResponse
    {
        $language = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'language']);
        $languageArray = $languageService->getDefaultData(request: $request, language: $language);
        $this->businessSettingRepo->updateOrInsert(type: 'language', value: $languageArray);
        return response()->json(['message'=>translate('Default_Language_Changed')]);
    }

    public function update(LanguageRequest $request, LanguageService $languageService): RedirectResponse
    {
        $language = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'language']);
        $languageArray = $languageService->getUpdateData(request: $request, language: $language);
        $this->businessSettingRepo->updateOrInsert(type: 'language', value: $languageArray);
        Toastr::success(translate('Language_updated'));
        return back();
    }

    public function getTranslateView($lang): View
    {
        return view(Language::TRANSLATE_VIEW[VIEW], compact('lang'));
    }

    public function getTranslateList($lang): JsonResponse
    {
        $data = [];
        $path = base_path('resources/lang/' . $lang . '/messages.php');
        if (File::exists($path)) {
            $languageData = include(base_path('resources/lang/' . $lang . '/messages.php'));
            ksort($languageData);
            $index = 1;
            foreach ($languageData as $key => $value) {
                $data[] = [
                    'index' => $index++,
                    'key' => $key,
                    'value' => $value,
                    'encode' => !empty($key) ? base64_encode($key) : '',
                ];
            };
        }
        return response()->json($data);
    }

    public function deleteTranslateKey(Request $request, $lang): void
    {
        $fullData = include(base_path('resources/lang/' . $lang . '/messages.php'));
        unset($fullData[$request['key']]);
        $string = "<?php return " . var_export($fullData, true) . ";";
        file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $string);
    }

    public function updateTranslate(Request $request, $lang): void
    {
        $fullData = include(base_path('resources/lang/' . $lang . '/messages.php'));
        $dataFiltered = [];
        foreach ($fullData as $key => $data) {
            $dataFiltered[removeSpecialCharacters(text: $key)] = $data;
        }
        $dataFiltered[base64_decode($request['key'])] = $request['value'];
        $string = "<?php return " . var_export($dataFiltered, true) . ";";
        file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $string);
    }

    public function getAutoTranslate(Request $request, $lang): JsonResponse
    {
        $getKey = $request->has('key') && !empty($request['key']) ? base64_decode($request['key']) : '';
        if ($getKey) {
            $langCode = getLanguageCode($lang);
            $fullData = include(base_path('resources/lang/' . $lang . '/messages.php'));
            $dataFiltered = [];

            foreach ($fullData as $key => $data) {
                $dataFiltered[removeSpecialCharacters(text: $key)] = $data;
            }

            $translated = autoTranslator($getKey, 'en', $langCode);
            $dataFiltered[$getKey] = $translated;

            $string = "<?php return " . var_export($dataFiltered, true) . ";";
            file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $string);
            return response()->json(['translated_data' => $translated]);
        }
        return response()->json(['message' => 'empty_data']);
    }

    public function delete($lang, LanguageService $languageService): RedirectResponse
    {
        $language = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'language']);
        $languageArray = $languageService->getLangDelete(language: $language, code: $lang);
        $this->businessSettingRepo->updateOrInsert(type: 'language', value: $languageArray);

        $languages = array();
        $pncLanguage = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'pnc_language']);
        foreach (json_decode($pncLanguage['value'], true) as $key => $data) {
            if ($data != $lang) {$languages[] = $data;}
        }
        if (in_array('en', $languages)) {
            unset($languages[array_search('en', $languages)]);
        }
        array_unshift($languages, 'en');
        $this->businessSettingRepo->updateOrInsert(type: 'pnc_language', value: json_encode($languages));
        Toastr::success(translate('Removed_Successfully'));
        return back();
    }

}
