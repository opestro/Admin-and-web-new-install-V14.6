<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/transaction/order-transaction-list') ?'active':'' }}"><a href="{{route('admin.transaction.order-transaction-list')}}">{{translate('order_Transactions')}}</a></li>
        <li class="{{ Request::is('admin/transaction/expense-transaction-list') ?'active':'' }}"><a href="{{route('admin.transaction.expense-transaction-list')}}">{{translate('expense_Transactions')}}</a></li>
        <li class="{{ Request::is('admin/report/transaction/refund-transaction-list') ?'active':'' }}"><a href="{{ route('admin.report.transaction.refund-transaction-list') }}">{{translate('refund_Transactions')}}</a></li>
    </ul>
</div>
