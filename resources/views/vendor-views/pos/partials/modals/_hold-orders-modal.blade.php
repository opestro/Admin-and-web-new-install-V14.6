<div class="modal fade" id="hold-orders-modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content h-90dvh">
            <div class="modal-header pt-3 flex-wrap gap-2">
                <h5 class="modal-title">{{ translate('list_of_hold_orders') }}</h5>
                <div class="flex-grow-1 max-w-283">
                    <div class="search-form">
                        <button type="button" class="btn">
                            <i class="tio-search"></i>
                        </button>
                        <input type="text" class="js-form-search form-control view_all_hold_orders_search"
                               placeholder="{{ translate('search_by_customer_name').'...'}}">
                    </div>
                </div>
            </div>
            <div class="modal-body pt-3" id="hold-orders-modal-content"></div>
        </div>
    </div>
</div>
