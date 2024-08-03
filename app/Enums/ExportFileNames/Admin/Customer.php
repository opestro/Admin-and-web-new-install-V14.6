<?php

namespace App\Enums\ExportFileNames\Admin;

enum Customer
{
    const CUSTOMER_ORDER_LIST = 'Customer-Order-List.xlsx';
    const EXPORT_XLSX = 'Customers.xlsx';
    const SUBSCRIBER_LIST_XLSX = 'Subscriber-list.xlsx';
    const LOYALTY_TRANSACTIONS_LIST_XLSX = 'Loyalty-Transactions-Report.xlsx';
    const WALLET_TRANSACTIONS_LIST_XLSX = 'Wallet-Transactions-Report.xlsx';
    const EMPLOYEES_LIST_XLSX = 'Employees.xlsx';
}
