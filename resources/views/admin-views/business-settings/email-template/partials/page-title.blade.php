@php
    use App\Enums\EmailTemplateKey;
@endphp
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-2">
    <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/system-setting.png')}}" alt="">
        {{translate('email_template')}}
    </h2>
    <div>
        <div class="dropdown">
            <button class="dropdown-toggle form-control pe--4" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{translate($template['user_type'].'_mail_template')}}
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item {{$template['user_type']=='admin' ? 'active' : ''}}" href="{{route('admin.business-settings.email-templates.view',['admin',EmailTemplateKey::ADMIN_EMAIL_LIST[0]])}}">{{translate('admin_mail_template')}}</a>
                <a class="dropdown-item {{$template['user_type']=='vendor' ? 'active' : ''}}" href="{{route('admin.business-settings.email-templates.view',['vendor',EmailTemplateKey::VENDOR_EMAIL_LIST[0]])}}">{{translate('vendor_mail_template')}}</a>
                <a class="dropdown-item {{$template['user_type']=='customer' ? 'active' : ''}}" href="{{route('admin.business-settings.email-templates.view',['customer',EmailTemplateKey::CUSTOMER_EMAIL_LIST[0]])}}">{{translate('customer_mail_template')}}</a>
                <a class="dropdown-item {{$template['user_type']=='delivery-man' ? 'active' : ''}}" href="{{route('admin.business-settings.email-templates.view',['delivery-man',EmailTemplateKey::DELIVERY_MAN_EMAIL_LIST[0]])}}">{{translate('delivery_Man_mail_template')}}</a>
            </div>
        </div>
    </div>
</div>
