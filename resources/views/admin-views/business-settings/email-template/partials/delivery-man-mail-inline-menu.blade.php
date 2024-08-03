@php
    use App\Enums\EmailTemplateKey;
@endphp
<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        @foreach(EmailTemplateKey::DELIVERY_MAN_EMAIL_LIST as $key=>$value)
            <li class="{{ Request::is('admin/business-settings/email-templates/delivery-man/'.$value) ?'active':'' }}"><a
                    href="{{route('admin.business-settings.email-templates.view',['delivery-man',$value])}}" class="text-capitalize">{{translate(str_replace('-','_',$value))}}</a>
            </li>
        @endforeach
    </ul>
</div>
