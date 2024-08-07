<?php

namespace App\Http\Controllers\Admin\Report;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\RefundTransactionRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Report;
use App\Enums\ViewPaths\Admin\RefundTransaction;
use App\Enums\WebConfigKey;
use App\Exports\RefundTransactionReportExport;
use App\Http\Controllers\BaseController;
use App\Services\RefundTransactionService;
use App\Traits\PdfGenerator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View as PdfView;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RefundTransactionController extends BaseController
{
    use PdfGenerator;
    public function __construct(
        private readonly RefundTransactionRepositoryInterface $refundTransactionRepo,
        private readonly RefundTransactionService $refundTransactionService,
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
    )
    {

    }
    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getListView($request);
    }
    public function getListView($request):View
    {
        $searchValue = $request['searchValue'];
        $paymentMethod = $request['payment_method'];
        $refundTransactions = $this->getRefundTransactionData($request);
        return view(RefundTransaction::INDEX[VIEW],compact('searchValue','paymentMethod','refundTransactions'));
    }
    public function exportRefundTransaction(Request $request):BinaryFileResponse
    {
        $transactions = $this->getRefundTransactionData($request);
        $data = [
            'searchValue' => $request['searchValue'],
            'paymentMethod' => $request['payment_method'],
            'transactions' => $transactions,
        ];
        return Excel::download(new RefundTransactionReportExport($data),Report::REFUND_TRANSACTION_REPORT_LIST);
    }
    public function getRefundTransactionPDF(Request $request):void
    {
        $companyPhone = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'company_phone'])->value;
        $companyEmail = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'company_email'])->value;
        $companyName = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'company_name'])->value;
        $companyWebLogo = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'company_web_logo'])->value;
        $refundTransactions = $this->getRefundTransactionData($request);
        $PDFData = $this->refundTransactionService->getPDFData(
            companyPhone:$companyPhone,
            companyEmail:$companyEmail,
            companyName:$companyName,
            companyWebLogo:$companyWebLogo,
            refundTransactions:$refundTransactions,
        );
        $mpdfView = PdfView::make(RefundTransaction::GENERATE_PDF[VIEW],compact($PDFData)
        );
        $this->generatePdf(view: $mpdfView, filePrefix: 'refund_transaction_summary_report_', filePostfix: data('Y'));
    }
    protected function getRefundTransactionData($request):Collection|LengthAwarePaginator
    {
        return $this->refundTransactionRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            searchValue: $request['searchValue'],
            filters: ['payment_method' => $request['payment_method'] == 'all' ? null : $request['payment_method']],
            relations: ['order.seller.shop', 'orderDetails.product'],
            dataLimit: getWebConfig(WebConfigKey::PAGINATION_LIMIT),
        );
    }


}
