<div class="card mb-3">
    <div class="card-body">
        <form action="{{route('admin.business-settings.email-templates.update-status',[$template['template_name'],$template['user_type']])}}" method="post" id="email-template-status-form" enctype="multipart/form-data">
            @csrf
            <div class="border rounded border-color-c1 px-4 py-3 d-flex justify-content-between mb-1">
                <h5 class="mb-0 d-flex text-capitalize">{{translate('get_email_on_').translate(str_replace('-','_',$template['template_name'])).' ?'}}</h5>
                <div class="position-relative">
                    <label class="switcher" for="email-template-status">
                        <input type="checkbox" class="switcher_input toggle-switch-message"
                               name="status"
                               id="email-template-status"
                               value="1" {{ $template['status'] == 1 ? 'checked':'' }}
                               data-modal-id="toggle-status-modal"
                               data-toggle-id="email-template-status"
                               data-on-image="mail-status-on.png"
                               data-off-image="mail-status-off.png"
                               data-on-title="{{translate('want_to_Turn_OFF_this_mail').'?'}}"
                               data-off-title="{{translate('want_to_Turn_ON_this_mail').'?'}}"
                               data-on-message="<p>{{translate('if_disabled_users_would_not_receive_this_mail').'.'}}</p>"
                               data-off-message="<p>{{translate('if_enabled_users_will_receive_this_mail').'.'}}</p>">
                        <span class="switcher_control"></span>
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>
