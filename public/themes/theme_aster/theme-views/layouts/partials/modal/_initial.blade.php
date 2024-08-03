@if($web_config['popup_banner'])
<div class="modal fade" id="initialModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered justify-content-center">
        <div class="modal-content border-0 max-width--600">
            <div class="modal-body p-0">
                <button type="button" class="btn-close outside" data-bs-dismiss="modal" aria-label="Close"></button>
                <div onclick="location.href='{{$web_config['popup_banner']['url']}}'">
                    <img src="{{ getValidImage(path: 'storage/app/public/banner/'.$web_config['popup_banner']['photo'], type:'banner') }}"
                         class="dark-support rounded img-fit" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
@endif
