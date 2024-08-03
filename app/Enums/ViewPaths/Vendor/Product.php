<?php

namespace App\Enums\ViewPaths\Vendor;

enum Product
{
    const ADD = [
        URI => 'add',
        VIEW => 'vendor-views.product.add-new'
    ];

    const LIST = [
        URI => 'list',
        VIEW => 'vendor-views.product.list'
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'vendor-views.product.edit'
    ];

    const VIEW = [
        URI => 'view',
        VIEW => 'vendor-views.product.view',
        ROUTE => 'vendor.products.view'
    ];

    const SKU_COMBINATION = [
        URI => 'sku-combination',
        VIEW => ''
    ];

    const UPDATE_STATUS = [
        URI => 'status-update',
        VIEW => ''
    ];

    const GET_CATEGORIES = [
        URI => 'get-categories',
        VIEW => ''
    ];

    const BARCODE_VIEW = [
        URI => 'barcode',
        VIEW => 'vendor-views.product.barcode'
    ];

    const BARCODE_GENERATE = [
        URI => 'barcode',
        VIEW => ''
    ];

    const EXPORT_EXCEL = [
        URI => 'export-excel',
        VIEW => ''
    ];

    const STOCK_LIMIT = [
        URI => 'stock-limit-list',
        VIEW => 'vendor-views.product.stock-limit-list'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const DELETE_IMAGE = [
        URI => 'delete-image',
        VIEW => ''
    ];

    const GET_VARIATIONS = [
        URI => 'get-variations',
        VIEW => 'vendor-views.product.partials._update_stock'
    ];

    const UPDATE_QUANTITY = [
        URI => 'update-quantity',
        VIEW => ''
    ];

    const BULK_IMPORT = [
        URI => 'bulk-import',
        VIEW => 'vendor-views.product.bulk-import'
    ];
    const SEARCH = [
        URI => 'search',
        VIEW => 'vendor-views.partials._search-product'

    ];
    const PRODUCT_GALLERY = [
        URI => 'product-gallery',
        VIEW => 'vendor-views.product.product-gallery'
    ];

    const STOCK_LIMIT_STATUS = [
        URI => 'stock-limit-status',
        VIEW => ''
    ];
}
