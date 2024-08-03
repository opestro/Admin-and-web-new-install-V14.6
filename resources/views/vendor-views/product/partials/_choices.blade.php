@foreach($choice_options as $key=>$choice)
    <div class="col-md-12 col-lg-6">
        <div class="row">
            <div class="col-md-3">
                <input type="hidden" name="choice_no[]" value="{{$choice_no[$key]??''}}">
                <input type="text" class="form-control" name="choice[]" value="{{$choice['title']}}"
                       placeholder="{{translate('choice_Title') }}" readonly>
            </div>
            <div class="col-lg-9">
                <input type="text" class="form-control call-update-sku"
                       name="choice_options_{{$choice_no[$key]??''}}[]"
                       data-role="tagsinput"
                       value="@foreach($choice['options'] as $c) {{$c.','}} @endforeach">
            </div>
        </div>
    </div>
@endforeach
