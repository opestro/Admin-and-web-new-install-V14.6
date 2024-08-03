<?php

namespace App\Services;

use Brian2694\Toastr\Facades\Toastr;

class OfflinePaymentMethodService
{
    public function getMethodFieldsData($request):array
    {
        $methodFields = [];
        if($request->has('input_name'))
        {
            foreach ($request->input_name as $key => $field_name) {
                $methodFields[] = [
                    'input_name' => strtolower(str_replace("'", '', preg_replace('/[^a-zA-Z0-9\']/', '_', $request->input_name[$key]))),
                    'input_data' => $request->input_data[$key],
                ];
            }
        }
        return $methodFields;
    }
    public function getMethodInformationData($request):array|bool
    {
        $methodInformation = [];
        if($request->has('customer_input'))
        {
            foreach ($request->customer_input as $key => $field_name) {
                $input_key = strtolower(str_replace("'", '', preg_replace('/[^a-zA-Z0-9\']/', '_', $request->customer_input[$key])));

                $keyExists = false;
                foreach ($methodInformation as $info) {
                    if ($info['customer_input'] === $input_key) {
                        $keyExists = true;
                        break;
                    }
                }

                if (!$keyExists) {
                    if (!array_key_exists($request->customer_input[$key], $methodInformation)) {
                        $methodInformation[] = [
                            'customer_input' => $input_key,
                            'customer_placeholder' => $request->customer_placeholder[$key],
                            'is_required' => isset($request['is_required']) && isset($request['is_required'][$key]) ? 1 : 0,
                        ];
                    }
                }else {
                    Toastr::error(translate('information_Input_Field_Name_must_be_unique'));
                    return false;
                }
            }
        }
        return $methodInformation;
    }
    public function getOfflinePaymentMethodData(string $methodName,array $methodFields,array $methodInformation,string $addOrUpdate):array
    {
        $addOrUpdate = $addOrUpdate == 'add' ? 'created_at' : 'updated_at';
        return [
            'method_name' => $methodName,
            'method_fields' => $methodFields,
            'method_informations' => $methodInformation,
            $addOrUpdate => now(),
        ];
    }
}
