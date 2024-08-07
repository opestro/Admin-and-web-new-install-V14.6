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
        return response()->json(['message' => translate('Default_Language_Changed')]);
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
        $totalMessages = count(include(base_path('resources/lang/' . $lang . '/new-messages.php')));
        $messageGroup = 20;
        return view(Language::TRANSLATE_VIEW[VIEW], compact('lang', 'totalMessages', 'messageGroup'));
    }

    public function getTranslateList($lang, LanguageService $languageService): JsonResponse
    {
        $data = $languageService->getTranslateList(language: $lang);
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
        $translatedMessagesArray = include(base_path('resources/lang/' . $lang . '/messages.php'));
        $newMessagesArray = include(base_path('resources/lang/' . $lang . '/new-messages.php'));
        $textKey = $request->has('key') && !empty($request['key']) ? base64_decode($request['key']) : '';
        $dataFiltered = [];

        if (array_key_exists($textKey, $translatedMessagesArray)) {
            foreach ($translatedMessagesArray as $key => $data) {
                $dataFiltered[removeSpecialCharacters(text: $key)] = $data;
            }
            $dataFiltered[base64_decode($request['key'])] = removeSpecialCharacters($request['value']);
            $string = "<?php return " . var_export($dataFiltered, true) . ";";
            file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $string);
        } elseif (array_key_exists($textKey, $newMessagesArray)) {
            foreach ($newMessagesArray as $key => $data) {
                $dataFiltered[removeSpecialCharacters(text: $key)] = $data;
            }
            $dataFiltered[base64_decode($request['key'])] = removeSpecialCharacters($request['value']);
            $string = "<?php return " . var_export($dataFiltered, true) . ";";
            file_put_contents(base_path('resources/lang/' . $lang . '/new-messages.php'), $string);
        }
    }

    public function getAutoTranslate(Request $request, $lang): JsonResponse
    {
        $translatedMessagesArray = include(base_path('resources/lang/' . $lang . '/messages.php'));
        $newMessagesArray = include(base_path('resources/lang/' . $lang . '/new-messages.php'));
        $textKey = $request->has('key') && !empty($request['key']) ? base64_decode($request['key']) : '';
        $dataFiltered = [];

        if ($textKey) {
            $langCode = getLanguageCode($lang);
            if (array_key_exists($textKey, $translatedMessagesArray)) {

                foreach ($translatedMessagesArray as $key => $data) {
                    $dataFiltered[removeSpecialCharacters(text: $key)] = $data;
                }

                $translated = autoTranslator($textKey, 'en', $langCode);
                $dataFiltered[$textKey] = removeSpecialCharacters($translated);

                $string = "<?php return " . var_export($dataFiltered, true) . ";";
                file_put_contents(base_path('resources/lang/' . $lang . '/messages.php'), $string);
                return response()->json(['translated_data' => $translated]);
            } elseif (array_key_exists($textKey, $newMessagesArray)) {
                foreach ($newMessagesArray as $key => $data) {
                    $dataFiltered[removeSpecialCharacters(text: $key)] = $data;
                }

                $translated = autoTranslator($textKey, 'en', $langCode);
                $dataFiltered[$textKey] = removeSpecialCharacters($translated);

                $string = "<?php return " . var_export($dataFiltered, true) . ";";
                file_put_contents(base_path('resources/lang/' . $lang . '/new-messages.php'), $string);
                return response()->json(['translated_data' => $translated]);
            }
        }
        return response()->json(['message' => 'empty_data']);
    }

    public function getAutoTranslateAllMessages(Request $request, $lang, LanguageService $languageService): JsonResponse|RedirectResponse
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('This_option_is_disabled_for_demo'));
            return back();
        }

        $response = $languageService->getAllMessagesTranslateProcess(language: $lang, count: 20);
        return response()->json([
            'status' => $response['status'],
            'message' => $response['message'],
            'due_message' => $response['due_message'],
            'translate_success_message' => $response['translateCountSuccess'] > 0 ? translate('your') . ' ' . $response['translateCountSuccess'] . ' ' . translate('messages_successfully_translated') : translate('all_messages_are_in_translated'),
        ]);
    }

    public function delete($lang, LanguageService $languageService): RedirectResponse
    {
        $language = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'language']);
        $languageArray = $languageService->getLangDelete(language: $language, code: $lang);
        $this->businessSettingRepo->updateOrInsert(type: 'language', value: $languageArray);

        $languages = array();
        $pncLanguage = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'pnc_language']);
        foreach (json_decode($pncLanguage['value'], true) as $key => $data) {
            if ($data != $lang) {
                $languages[] = $data;
            }
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
