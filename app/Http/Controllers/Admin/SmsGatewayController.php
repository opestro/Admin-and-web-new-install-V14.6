<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmsGatewayController extends Controller
{
    public function index()
    {
        return view('admin-views.business-settings.sms-gateway.index');
    }

    public function update(Request $request, $name)
    {
        if ($name == 'sms_nexmo') {
            $sms = BusinessSetting::where('type', 'sms_nexmo')->first();
            if (isset($sms) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'sms_nexmo',
                    'value' => json_encode([
                        'status' => 1,
                        'nexmo_key' => '',
                        'nexmo_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['type' => 'sms_nexmo'])->update([
                    'type' => 'sms_nexmo',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'nexmo_key' => $request['nexmo_key'],
                        'nexmo_secret' => $request['nexmo_secret'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        }

        return back();
    }
}
