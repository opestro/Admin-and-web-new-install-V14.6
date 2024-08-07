<?php

namespace App\Services;

class WithdrawalMethodService
{
    /**
     * @param object $request
     * @param int $dataCount
     * @return array
     */
    public function getProcessedData(object $request, int $dataCount): array
    {
        $method_fields = [];
        foreach ($request['field_name'] as $key => $field_name) {
            $method_fields[] = [
                'input_type' => $request['field_type'][$key],
                'input_name' => strtolower(str_replace(' ', "_", $request['field_name'][$key])),
                'placeholder' => $request['placeholder_text'][$key],
                'is_required' => isset($request['is_required'][$key]) ? 1 : 0,
            ];
        }
        return [
            'method_name' => $request['method_name'],
            'method_fields' => $method_fields,
            'is_default' => ($request->has('is_default') && $request['is_default'] || $dataCount == 0) == '1' ? 1 : 0,
        ];
    }

}
