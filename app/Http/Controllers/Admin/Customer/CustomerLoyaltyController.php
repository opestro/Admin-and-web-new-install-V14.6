<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\LoyaltyPointTransactionRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Customer as CustomerExport;
use App\Enums\ViewPaths\Admin\Customer;
use App\Enums\WebConfigKey;
use App\Exports\CustomerTransactionsExport;
use App\Http\Controllers\BaseController;
use App\Traits\PaginatorTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomerLoyaltyController extends BaseController
{
    use PaginatorTrait;

    public function __construct(
        private readonly CustomerRepositoryInterface                    $customerRepo,
        private readonly LoyaltyPointTransactionRepositoryInterface     $loyaltyPointTransactionRepo,
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

    public function getListView(Request $request): View
    {
        $filters = [
            'to' => $request['to'],
            'from' => $request['from'],
            'transaction_type' => $request['transaction_type'],
            'customer_id' => $request['customer_id'],
        ];
        $data = $this->loyaltyPointTransactionRepo->getListWhereSelect(filters:$filters, dataLimit:'all');
        $transactions = $this->loyaltyPointTransactionRepo->getListWhere(
            orderBy: ['id'=>'desc'],
            filters:$filters,
            dataLimit:getWebConfig(name: WebConfigKey::PAGINATION_LIMIT)
        );
        $customer = "all";
        if (isset($request['customer_id']) && $request['customer_id'] != 'all' && !is_null($request['customer_id']) && $request->has('customer_id')) {
            $customer = $this->customerRepo->getFirstWhere(params: ['id' => $request['customer_id']]);
        }
        return view(Customer::LOYALTY_REPORT[VIEW], compact('data','transactions','customer'));
    }

    public function exportList(Request $request): BinaryFileResponse
    {
        $filters = [
            'to' => $request['to'],
            'from' => $request['from'],
            'transaction_type' => $request['transaction_type'],
            'customer_id' => $request['customer_id'],
        ];
        $summary = $this->loyaltyPointTransactionRepo->getListWhereSelect(filters:$filters, dataLimit:'all');
        $transactions = $this->loyaltyPointTransactionRepo->getListWhere(orderBy: ['id'=>'desc'], filters:$filters, dataLimit:'all');
        $data = [
            'type'=>'loyalty',
            'transactions'=> $transactions,
            'credit' => $summary[0]->total_credit,
            'debit' => $summary[0]->total_debit,
            'balance' => $summary[0]->total_credit - $summary[0]->total_debit,
            'transaction_type' =>$request['transaction_type'],
            'to' => $request['to'],
            'from' => $request['from'],
            'customer' => $request['customer_id'] ? $this->customerRepo->getFirstWhere(params:['id'=>$request['customer_id']]) : "all_customers",
        ];
        return Excel::download(new CustomerTransactionsExport($data), CustomerExport::LOYALTY_TRANSACTIONS_LIST_XLSX);
    }

}
