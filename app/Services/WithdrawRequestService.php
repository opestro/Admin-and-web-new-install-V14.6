<?php

namespace App\Services;

class WithdrawRequestService
{
    /**
     * @return array[seller_id: int|string, amount: float|int, transaction_note: null, withdrawal_method_id: mixed, withdrawal_method_fields: false|string, approved: int, created_at: \Illuminate\Support\Carbon, updated_at: \Illuminate\Support\Carbon]
     */
    public function getWithdrawRequestData(object $withdrawMethod, object $request , string $addedBy, int|string $vendorId):array
    {
        return [
            'seller_id' => $addedBy === 'vendor' ? $vendorId : '',
            'amount' => currencyConverter($request['amount']),
            'transaction_note' => null,
            'withdrawal_method_id' => $request['withdraw_method'],
            'withdrawal_method_fields' => json_encode($this->getWithdrawMethodFields(request:$request,withdrawMethod:$withdrawMethod)),
            'approved' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    /**
     * @param object $request
     * @param object $withdrawMethod
     * @return array
     */
    public function getWithdrawMethodFields(object $request, object $withdrawMethod):array
    {
        $inputFields = array_column($withdrawMethod['method_fields'], 'input_name');
        $method['method_name'] = $withdrawMethod['method_name'];
        $values = $request->all();
        foreach ($inputFields as $field) {
            if(key_exists($field, $values)) {
                $method[$field] = $values[$field];
            }
        }
        return $method;
    }

}
