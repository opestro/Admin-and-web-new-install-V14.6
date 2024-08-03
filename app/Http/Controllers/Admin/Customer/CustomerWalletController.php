<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Contracts\Repositories\AddFundBonusCategoriesRepositoryInterface;
use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\WalletTransactionRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Customer as CustomerExport;
use App\Enums\ViewPaths\Admin\CustomerWallet;
use App\Enums\WebConfigKey;
use App\Events\AddFundToWalletEvent;
use App\Exports\CustomerTransactionsExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AddFundBonusCategoriesDeleteRequest;
use App\Http\Requests\Admin\AddFundBonusCategoriesUpdateRequest;
use App\Http\Requests\Admin\AddFundRequest;
use App\Http\Requests\Admin\BonusSetupRequest;
use App\Services\CustomerWalletService;
use App\Traits\PaginatorTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use function Laravel\Prompts\alert;

class CustomerWalletController extends BaseController
{
    use PaginatorTrait;

    public function __construct(
        private readonly CustomerRepositoryInterface               $customerRepo,
        private readonly BusinessSettingRepositoryInterface        $businessSettingRepo,
        private readonly WalletTransactionRepositoryInterface      $walletTransactionRepo,
        private readonly AddFundBonusCategoriesRepositoryInterface $addFundBonusCategoriesRepo,
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
        return $this->getListView($request);
    }

    public function addFund(AddFundRequest $request, CustomerWalletService $customerWalletService): JsonResponse
    {
        $walletTransaction = $this->walletTransactionRepo->addWalletTransaction(
            user_id: $request['customer_id'],
            amount: $request['amount'],
            transactionType: 'add_fund_by_admin',
            reference: $request['reference']);

        $customer = $this->customerRepo->getFirstWhere(params: ['id' => $request['customer_id']]);
        $customerWalletService->sendPushNotificationMessage(request: $request, customer: $customer);

        if ($walletTransaction) {
            $data = [
                'walletTransaction' => $walletTransaction,
                'userName' => $customer['f_name'],
                'userType' => 'customer',
                'templateName' => 'add-fund-to-wallet',
                'subject' => translate('add_fund_to_wallet'),
                'title' => translate('add_fund_to_wallet'),
            ];
            event(new AddFundToWalletEvent(email: $customer['email'], data: $data));
            return response()->json(['message' => translate('transaction_successful')], 200);
        }

        return response()->json(['errors' => [
            'message' => translate('failed_to_create_transaction')
        ]], 200);
    }

    public function getListView(Request $request): View
    {
        $customerStatus = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'wallet_status'])['value'];
        $filters = [
            'to' => $request['to'],
            'from' => $request['from'],
            'transaction_type' => $request['transaction_type'] ?? 'all',
            'customer_id' => $request['customer_id'],
        ];

        $data = $this->walletTransactionRepo->getListWhereSelect(filters: $filters, dataLimit: 'all');
        $transactions = $this->walletTransactionRepo->getListWhere(filters: $filters, dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        $customer = "all";
        if (isset($request['customer_id']) && $request['customer_id'] != 'all' && !is_null($request['customer_id']) && $request->has('customer_id')) {
            $customer = $this->customerRepo->getFirstWhere(params: ['id' => $request['customer_id']]);
        }
        return view(CustomerWallet::REPORT[VIEW], compact('data', 'transactions', 'customerStatus','customer'));
    }

    public function exportList(Request $request): BinaryFileResponse
    {
        $filters = [
            'to' => $request['to'],
            'from' => $request['from'],
            'transaction_type' => $request['transaction_type'],
            'customer_id' => $request['customer_id'],
        ];
        $summary = $this->walletTransactionRepo->getListWhereSelect(filters: $filters, dataLimit: 'all');
        $transactions = $this->walletTransactionRepo->getListWhere(filters: $filters, dataLimit: 'all');
        $data = [
            'type' => 'wallet',
            'transactions' => $transactions,
            'credit' => $summary[0]->total_credit,
            'debit' => $summary[0]->total_debit,
            'balance' => $summary[0]->total_credit - $summary[0]->total_debit,
            'transaction_type' => $request['transaction_type'],
            'to' => $request['to'],
            'from' => $request['from'],
            'customer' => $request['customer_id'] ? $this->customerRepo->getFirstWhere(params: ['id' => $request['customer_id']]) : "all_customers",
        ];
        return Excel::download(new CustomerTransactionsExport($data), CustomerExport::WALLET_TRANSACTIONS_LIST_XLSX);
    }

    public function getBonusSetupView(Request $request): View
    {
        $data = $this->addFundBonusCategoriesRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            searchValue: $request['search'],
            dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT),
        );
        return view(CustomerWallet::BONUS_SETUP[VIEW], compact('data'));
    }

    public function addBonusSetup(BonusSetupRequest $request): RedirectResponse
    {
        $data = [
            'title' => $request['title'],
            'description' => $request['description'],
            'bonus_type' => $request['bonus_type'],
            'bonus_amount' => $request['bonus_type'] == 'fixed' ? currencyConverter($request['bonus_amount']) : $request['bonus_amount'],
            'min_add_money_amount' => currencyConverter($request['min_add_money_amount']),
            'max_bonus_amount' => currencyConverter($request['max_bonus_amount']),
            'start_date_time' => $request['start_date_time'],
            'end_date_time' => $request['end_date_time'],
            'created_at' => now(),
        ];
        $this->addFundBonusCategoriesRepo->add(data: $data);
        Toastr::success(translate('wallet_Bonus_Added_Successfully'));
        return back();
    }

    public function updateStatus(AddFundBonusCategoriesUpdateRequest $request): JsonResponse|RedirectResponse
    {
        $this->addFundBonusCategoriesRepo->update(id: $request['id'], data: ['is_active' => $request->get('status', 0)]);
        return response()->json(['message'=> translate('update_successfully')]);
    }

    public function deleteBonus(AddFundBonusCategoriesDeleteRequest $request): JsonResponse|RedirectResponse
    {
        $this->addFundBonusCategoriesRepo->delete(params: ['id' => $request['id']]);
        if ($request->ajax()) {
            return response()->json([
                'status' => 1,
                'message' => translate('bonus_removed_Successfully'),
            ]);
        }
        Toastr::success(translate('bonus_removed_Successfully'));
        return back();
    }

    public function getUpdateView(Request $request): View
    {
        $data = $this->addFundBonusCategoriesRepo->getFirstWhere(params: ['id' => $request['id']]);
        return view(CustomerWallet::BONUS_SETUP_EDIT[VIEW], compact('data'));
    }

    public function update(BonusSetupRequest $request): RedirectResponse
    {
        $data = [
            'title' => $request['title'],
            'description' => $request['description'],
            'bonus_type' => $request['bonus_type'],
            'bonus_amount' => $request['bonus_type'] == 'fixed' ? currencyConverter($request['bonus_amount']) : $request['bonus_amount'],
            'min_add_money_amount' => currencyConverter($request['min_add_money_amount']),
            'max_bonus_amount' => currencyConverter($request['max_bonus_amount']),
            'start_date_time' => $request['start_date_time'],
            'end_date_time' => $request['end_date_time'],
        ];
        $this->addFundBonusCategoriesRepo->update(id: $request['id'], data: $data);
        Toastr::success(translate('wallet_Bonus_update_Successfully'));
        return back();
    }

}
