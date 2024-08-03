<p class="view-footer-text">
    {{$footerText}}
</p>
<p>{{translate('Thanks_&_Regards')}}, <br> {{getWebConfig('company_name')}}</p>
<div class="d-flex justify-content-center mb-3 ">
    <img width="76" class="mx-auto" id="view-mail-logo" src="{{$template['logo'] ? dynamicStorage('storage/app/public/email-template/'.$template['logo']) : getValidImage(path: "storage/app/public/company/".$companyLogo, type:'backend-logo')}}" alt="">
</div>
<div class="d-flex justify-content-center gap-2">
    <ul class="email-list-inline gap-3 mx-auto" id="selected-pages">
        @if(!empty($template['pages']) && in_array('privacy_policy',$template['pages']))
            <li class="privacy-policy"><a href="{{route('privacy-policy') }}" class="text-dark">{{translate('privacy_Policy')}}</a></li>
        @endif
        @if(!empty($template['pages']) && in_array('refund_policy',$template['pages']))
            <li class="refund-policy"><a href="{{route('refund-policy')}}" class="text-dark">{{translate('refund_Policy')}}</a></li>
        @endif
        @if(!empty($template['pages']) && in_array('cancellation_policy',$template['pages']))
            <li class="cancellation-policy"><a href="{{route('cancellation-policy')}}" class="text-dark">{{translate('cancellation_Policy')}}</a></li>
        @endif
        @if(!empty($template['pages']) && in_array('contact_us',$template['pages']))
            <li class="contact-us"><a href="{{route('contacts') }}" class="text-dark">{{translate('contact_Us')}}</a></li>
        @endif
        @if(empty($template['pages']))
            <li class="privacy-policy"><a href="{{route('privacy-policy') }}" class="text-dark">{{translate('privacy_Policy')}}</a></li>
            <li class="refund-policy"><a href="{{route('refund-policy') }}" class="text-dark">{{translate('refund_Policy')}}</a></li>
            <li class="cancellation-policy"><a href="{{route('cancellation-policy')}}" class="text-dark">{{translate('cancellation_Policy')}}</a></li>
            <li class="contact-us"><a href="{{route('contacts') }}" class="text-dark">{{translate('contact_Us')}}</a></li>
        @endif

    </ul>
</div>
<div class="d-flex gap-4 justify-content-center align-items-center mb-3 fz-16 social-media-icon" id="selected-social-media">
    <div class="mx-auto">
    @foreach($socialMedia as $key=>$media)
        @if(!empty($template['social_media']))
            <a class="{{$media['name']}} {{in_array($media['name'],$template['social_media']) ? '' : 'd-none'}}" href="{{$media['link']}}" target="_blank">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/'.$media['name'].'.png') }}"
                     width="16" alt="">
            </a>
        @else
            <a class="{{$media['name']}}" href="{{$media['link']}}" target="_blank">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/'.$media['name'].'.png') }}"
                     width="16" alt="">
            </a>
        @endif
    @endforeach
    </div>
</div>
<p class="text-center view-copyright-text">
    {{$copyrightText}}
</p>
