<div class="modal-header border-0 pb-0 d-flex justify-content-end">
    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close">
        <i class="tio-clear"></i>
    </button>
</div>
<div class="modal-body px-4 px-sm-5">
    <div class="mb-4 text-center">
        <img width="200"
             src="{{ getValidImage(path: 'storage/app/public/company/'.$web_config['web_logo']->value, type: 'backend-basic') }}"
             alt="" class="dark-support"/>
    </div>
    <h2 class="text-center mb-4">{{ ucfirst(str_replace('_', ' ', $themeInfo['name'])) }}</h2>
    <form action="{{route('admin.business-settings.web-config.theme.activation')}}" method="post">
        @csrf
        <div class="form-group mb-4">
            <label for="username">{{ translate('codecanyon_username') }}</label>
            <input name="username" id="username" class="form-control"
                placeholder="{{translate('ex').':'.translate('riad_uddin')}}: " required />
        </div>
        <div class="form-group mb-6">
            <label for="purchase_code">{{ translate('purchase_code') }}</label>
            <input name="purchase_code" id="purchase_code" class="form-control"
                placeholder="{{translate('ex').':'.'987652'}}" required />
        </div>
        <div class="d-flex justify-content-center gap-3 mb-3">
            <input type="hidden" value="{{ $theme }}" name="theme">
            <button type="button" class="fs-16 btn btn-secondary flex-grow-1" data-dismiss="modal">{{ translate('cancel') }}</button>
            <button type="submit" class="fs-16 btn btn--primary flex-grow-1">{{ translate('activate') }}</button>
        </div>
    </form>
</div>

<script>
    onErrorImage();
</script>
