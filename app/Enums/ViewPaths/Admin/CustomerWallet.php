<?php

namespace App\Enums\ViewPaths\Admin;

enum CustomerWallet
{
    const ADD = [
        URI => 'add-fund',
        VIEW => ''
    ];

    const REPORT = [
        URI => 'report',
        VIEW => 'admin-views.customer.wallet.report'
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const BONUS_SETUP = [
        URI => 'bonus-setup',
        VIEW => 'admin-views.customer.wallet.wallet-bonus-setup'
    ];

    const BONUS_SETUP_UPDATE = [
        URI => 'bonus-setup-update',
        VIEW => ''
    ];

    const BONUS_SETUP_STATUS = [
        URI => 'bonus-setup-status',
        VIEW => ''
    ];

    const BONUS_SETUP_EDIT = [
        URI => 'bonus-setup/edit',
        VIEW => 'admin-views.customer.wallet.wallet-bonus-edit'
    ];

    const BONUS_SETUP_DELETE = [
        URI => 'bonus-setup-delete',
        VIEW => ''
    ];
}
