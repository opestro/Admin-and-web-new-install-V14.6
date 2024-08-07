<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Utils\Helpers;
use App\Models\BusinessSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class SharedController extends Controller
{
    public function changeLanguage(Request $request):JsonResponse
    {
        $direction = 'ltr';
        $language = getWebConfig('language');
        foreach ($language as $data) {
            if ($data['code'] == $request['language_code']) {
                $direction = $data['direction'] ?? 'ltr';
            }
        }
        session()->forget('language_settings');
        Helpers::language_load();
        session()->put('local', $request['language_code']);
        Session::put('direction', $direction);
        return response()->json(['message'=> translate('language_change_successfully').'.']);
    }
}
