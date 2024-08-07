<?php

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\LoyaltyPointTransactionRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\LoyaltyExchangeCurrencyRequest;
use App\Mail\AddFundToWallet;
use App\Traits\CustomerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserLoyaltyController extends Controller
{
    use CustomerTrait;

    public function __construct(private readonly LoyaltyPointTransactionRepositoryInterface $loyaltyPointTransactionRepo)
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $loyaltyPointStatus = getWebConfig(name: 'loyalty_point_status');
        if ($loyaltyPointStatus == 1) {
            $walletStatus = getWebConfig(name: 'wallet_status');
            $totalLoyaltyPoint = auth('customer')->user()->loyalty_point;
            $loyaltyPointMinimumPoint = getWebConfig(name: 'loyalty_point_minimum_point');
            $loyaltyPointExchangeRate = getWebConfig(name: 'loyalty_point_exchange_rate');
            $loyaltyPointList = $this->loyaltyPointTransactionRepo->getListWhere(
                orderBy: ['id' => 'desc'],
                filters: ['customer_id' => auth('customer')->id(), 'transaction_type' => $request['type']],
                dataLimit: getWebConfig(name: 'pagination_limit')
            );
            return view(VIEW_FILE_NAMES['user_loyalty'], compact('totalLoyaltyPoint', 'loyaltyPointStatus', 'walletStatus', 'loyaltyPointList', 'loyaltyPointMinimumPoint', 'loyaltyPointExchangeRate'));
        }else{
            Toastr::warning(translate('access_denied'));
            return redirect()->route('home');
        }

    }

    public function getLoyaltyExchangeCurrency(LoyaltyExchangeCurrencyRequest $request): RedirectResponse
    {
        if (getWebConfig(name: 'wallet_status') != 1 || getWebConfig(name: 'loyalty_point_status') != 1) {
            Toastr::warning(translate('transfer_loyalty_point_to_currency_is_not_possible_at_this_moment!'));
            return redirect()->route('home');
        }

        $user = auth('customer')->user();
        if ($request['point'] < (int)getWebConfig(name: 'loyalty_point_minimum_point') || $request['point'] > $user['loyalty_point']) {
            Toastr::warning(translate('exchange_requirements_not_matched'));
            return back();
        }

        $walletTransaction = $this->createWalletTransaction(user_id: $user['id'], amount: $request['point'], transaction_type: 'loyalty_point', reference: 'point_to_wallet');
        $this->loyaltyPointTransactionRepo->addLoyaltyPointTransaction(userId: $user['id'], reference: $walletTransaction['transaction_id'], amount: $request['point'], transactionType: 'point_to_wallet');

        try {
            Mail::to($user['email'])->send(new AddFundToWallet($walletTransaction));
        } catch (Exception $ex) {
        }

        Toastr::success(translate('point_to_wallet_transfer_successfully'));
        return back();
    }

    public function getLoyaltyCurrencyAmount(Request $request): JsonResponse
    {
        return response()->json(webCurrencyConverter(amount: $request['amount']));
    }
}
