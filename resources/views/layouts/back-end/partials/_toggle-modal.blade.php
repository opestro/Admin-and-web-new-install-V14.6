<div class="modal fade" id="toggle-modal" tabindex="-1" aria-labelledby="toggle-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
            </div>
            <div class="modal-body px-4 px-sm-5 pt-0">
                <div class="d-flex flex-column align-items-center text-center gap-2 mb-2">
                    <img src="" width="70" class="mb-3 mb-20" id="toggle-modal-image" alt="">
                    <h5 class="modal-title" id="toggle-modal-title"></h5>
                    <div class="text-center" id="toggle-modal-message"></div>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn--primary min-w-120" id="toggle-modal-ok-button" data-dismiss="modal">{{translate('ok')}}</button>
                    <button type="button" class="btn btn-danger-light min-w-120" data-dismiss="modal">{{ translate('cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="toggle-status-modal" tabindex="-1" aria-labelledby="toggle-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
            </div>
            <div class="modal-body px-4 px-sm-5 pt-0">
                <div class="d-flex flex-column align-items-center text-center gap-2 mb-2">
                    <div class="toggle-modal-img-box d-flex flex-column justify-content-center align-items-center mb-3 position-relative">
                        <img src="" class="status-icon"  alt="" width="30"/>
                        <img src="" id="toggle-status-modal-image" alt="" />
                    </div>
                    <h5 class="modal-title" id="toggle-status-modal-title"></h5>
                    <div class="text-center" id="toggle-status-modal-message"></div>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn--primary min-w-120" id="toggle-status-modal-ok-button" data-dismiss="modal">{{translate('ok')}}</button>
                    <button type="button" class="btn btn-danger-light min-w-120" data-dismiss="modal">{{ translate('cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
