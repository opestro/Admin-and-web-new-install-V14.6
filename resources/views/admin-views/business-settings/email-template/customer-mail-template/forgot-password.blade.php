<div class="p-3 px-xl-4 py-sm-5">
    <div class="text-center">
        <img width="160" class="mb-4" id="view-mail-icon" src="{{$template['image'] ? dynamicStorage('storage/app/public/email-template/'.$template['image']) : dynamicAsset(path: 'public/assets/back-end/img/email-template/change-pass.png')}}" alt="">
        <h3 class="mb-3 view-mail-title text-capitalize">
            {{$title}}
        </h3>
    </div>
    <div class="view-mail-body">
        {!! $body !!}
    </div>
    <div>
        <p>{{translate('click_here')}} <br> <a class="{{isset($data['passwordResetURL']) ? '' : 'cursor-default'}}" href="{{$data['passwordResetURL'] ?? 'javascript:'}}">{{translate('change_password')}}</a>
    </div>
    <hr>
    @include('admin-views.business-settings.email-template.partials-design.footer')
</div>
