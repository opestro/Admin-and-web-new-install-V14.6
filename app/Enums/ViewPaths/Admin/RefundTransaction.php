<?php

namespace App\Enums\ViewPaths\Admin;

enum RefundTransaction
{
    const INDEX = [
        URI => 'refund-transaction-list',
        VIEW => 'admin-views.refund-transaction.list'
    ];
    const EXPORT = [
        URI => 'refund-transaction-export',
        VIEW => ''
    ];
    const GENERATE_PDF = [
        URI => 'refund-transaction-summary-pdf',
        VIEW => 'admin-views.refund_transaction_summary_report_pdf'
    ];
}
