<form action="{{route('admin.currency.status')}}" method="post" data-from="currency"
      id="currency-status{{$currency['id']}}-form" class="currency_status_form">
    @csrf
    <input type="hidden" name="id" value="{{$currency['id']}}">
    <label class="switcher" for="currency-status{{$currency['id']}}">
        <input type="checkbox" class="switcher_input toggle-switch-message"
               id="currency-status{{$currency['id']}}" name="status" value="1"
               {{$currency->status?'checked':''}}
               data-modal-id = "toggle-status-modal"
               data-toggle-id = "currency-status{{$currency['id']}}"
               data-on-image = "currency-on.png"
               data-off-image = "currency-off.png"
               data-on-title = "{{translate('Want_to_Turn_ON_Currency_Status').'?'}}"
               data-off-title = "{{translate('Want_to_Turn_OFF_Currency_Status').'?'}}"
               data-on-message = "<p>{{translate('if_enabled_this_currency_will_be_available_throughout_the_entire_system')}}</p>"
               data-off-message = "<p>{{translate('if_disabled_this_currency_will_be_hidden_from_the_entire_system')}}</p>">
        <span class="switcher_control"></span>
    </label>
</form>
