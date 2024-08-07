<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('vendor/report/all-product') ?'active':'' }}"><a href="{{route('vendor.report.all-product')}}">{{translate('all_Products')}}</a></li>
        <li class="{{ Request::is('vendor/report/stock-product-report') ?'active':'' }}"><a href="{{route('vendor.report.stock-product-report')}}">{{translate('products_Stock')}}</a></li>
    </ul>
</div>
