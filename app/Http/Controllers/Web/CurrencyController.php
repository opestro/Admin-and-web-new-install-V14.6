<?php

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\CurrencyRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepo
    )
    {
    }

    public function changeCurrency(Request $request): JsonResponse
    {
        session()->put('currency_code', $request['currency_code']);
        $currency = $this->currencyRepo->getFirstWhere(params: ['code'=>$request['currency_code']]);
        session()->put('currency_symbol', $currency['symbol']);
        session()->put('currency_exchange_rate', $currency['exchange_rate']);
        $message = translate('currency_changed_to').' '.$currency['name'];
        return response()->json(['message'=>$message]);
    }
}
