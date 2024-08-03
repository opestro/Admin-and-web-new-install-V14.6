<div class="p-3 px-xl-4 py-sm-5">
    <div class="text-center">
        <img width="100" class="mb-4" id="view-mail-icon"
             src="{{$template['image'] ? dynamicStorage('storage/app/public/email-template/'.$template['image']) : dynamicAsset(path: 'public/assets/back-end/img/email-template/customer-registration.png')}}"
             alt="">
        <h3 class="mb-3 view-mail-title text-capitalize">
            {{$title?? translate('registration_Complete')}}
        </h3>
    </div>
    <div class="view-mail-body">
        {!! $body !!}
    </div>
    <div class="mt-2">
        <a href="{{$data['resetPassword'] ?? 'javascript:'}}" target="_blank"
           class="btn btn-primary view-button-content view-button-link {{isset($data['resetPassword']) ? '' : 'cursor-default'}}">{{$buttonName??translate('reset_Password')}}</a>
    </div>
    <br>
    <div>
        <p>
            {{translate('meanwhile_click_here_to_visit_').$companyName.translate('_website')}}
            <br>
            <a href="{{route('home')}}" target="_blank">{{url('/')}}</a>
        </p>
    </div>
    <hr>
    @include('admin-views.business-settings.email-template.partials-design.footer')
</div>
