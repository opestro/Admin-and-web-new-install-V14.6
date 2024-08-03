<div class="modal fade" id="add-discount" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('update_discount') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="title-color">{{ translate('type') }}</label>
                    <select name="type" id="type_ext_dis" class="form-control">
                        <option value="amount" {{ isset($discount_type) && $discount_type == 'amount' ? 'selected' : '' }}>
                            {{ translate('amount') }}
                        </option>
                        <option
                            value="percent" {{ isset($discount_type) && $discount_type == 'percent' ? 'selected' : '' }}>
                            {{ translate('percent') }}(%)
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="title-color">{{ translate('discount') }}</label>
                    <input type="number" id="dis_amount" class="form-control" name="discount" placeholder="{{translate('ex').':500'}}">
                </div>
                <div class="form-group">
                    <button class="btn btn--primary action-extra-discount" data-error-message="{{translate('please_enter_discount_amount')}}">
                        {{ translate('submit') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
