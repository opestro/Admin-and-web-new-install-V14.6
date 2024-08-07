<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Http\Controllers\Controller;
use App\Models\EmergencyContact;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmergencyContactController extends Controller
{
    public function list(Request $request){
        $seller = $request->seller;
        $contact_list = EmergencyContact::where('user_id', $seller->id)->latest()->get();

        $data = array();
        $data['contact_list'] = $contact_list;
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $seller = $request->seller;
        EmergencyContact::create([
            'user_id' => $seller->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'status' => 1
        ]);
        return response()->json(['message' => translate('emergency_contact_added_successfully!')], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $seller = $request->seller;
        $emergency_contact = EmergencyContact::where(['user_id'=>$seller->id])->find($request->id);
        if(!$emergency_contact){
            return response()->json(['message' => translate('invalid_emergency_contact!')], 403);
        }
        $emergency_contact->name = $request->name;
        $emergency_contact->phone = $request->phone;
        $emergency_contact->update();

        return response()->json(['message' => translate('emergency_contact_updated_successfully!')], 200);
    }

    public function status_update(Request $request)
    {
        $seller = $request->seller;
        $status = EmergencyContact::where(['user_id' => $seller->id, 'id' => $request->id])
            ->update(['status' => $request->status]);
        if ($status == true) {
            return response()->json(['message' => translate('contact_status_update_successfully!')], 200);
        } else {
            return response()->json(['message' => translate('contact_status_update_failed!')], 403);
        }
    }

    public function destroy(Request $request)
    {
        $seller = $request->seller;
        $delete = EmergencyContact::where(['user_id' => $seller->id, 'id' => $request->id])
            ->delete();
        if ($delete == true) {
            return response()->json(['message' => translate('emergency_contact_deleted_successfully!')], 200);
        } else {
            return response()->json(['message' => translate('emergency_contact_delete_failed!')], 403);
        }
    }
}
