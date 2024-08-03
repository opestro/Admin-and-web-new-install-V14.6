@php
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app')

@section('title', translate('DB_clean'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    @php($direction = Session::get('direction'))
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/system-setting.png')}}" alt="">
                {{translate('system_Settings')}}
            </h2>
        </div>
        @include('admin-views.business-settings.system-settings-inline-menu')
        <div class="row">
            <div class="col-12 mb-3">
                <div class="alert badge-soft-danger mb-0 mx-sm-2 {{ $direction === 'rtl' ? 'text-right' : 'text-left' }}" role="alert">
                    {{translate('this_page_contains_sensitive_information.Make_sure_before_changing.')}}
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.business-settings.web-config.clean-db')}}" method="post" class="form-submit"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                @foreach($tables as $key=>$table)
                                    <div class="col-sm-6 col-xl-3">
                                        <div class="form-group form-check {{ $direction === 'rtl' ? 'text-right' : 'text-left' }}">
                                            <input type="checkbox" name="tables[]" value="{{$table}}"
                                                class="form-check-input"
                                                id="business_section_{{$key}}">
                                            <label class="form-check-label text-dark cursor-pointer user-select-none"
                                                style="{{$direction === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                for="business_section_{{$key}}">{{translate(str_replace('_',' ',Str::limit($table, 20)))}}</label>
                                            <span class="badge-pill badge-secondary mx-2">{{$rows[$key]}}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-end gap-10 flex-wrap mt-3">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    class="btn btn--primary {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('clear')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    'use strict';
    $('.form-submit').on('submit',function(e) {
        e.preventDefault();
        Swal.fire({
            title: "{{translate('are_you_sure').'?'}}",
            text: "{{translate('sensitive_data').'!'.translate('make_sure_before_changing').'.'}}",
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '{{$web_config['primary_color']}}',
            cancelButtonText: '{{ translate("no") }}',
            confirmButtonText: '{{ translate("yes") }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                this.submit();
            }else{
                e.preventDefault();
                toastr.success("{{translate('cancelled')}}");
                location.reload();
            }
        })
    });
</script>
@endpush
