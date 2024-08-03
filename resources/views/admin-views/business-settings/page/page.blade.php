@extends('layouts.back-end.app')

@section('title', translate(str_replace('-',' ',$page)))

@push('css_or_js')
    <link href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/summernote/summernote.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/Pages.png')}}" alt="">
                {{translate('pages')}}
            </h2>
        </div>
        @include('admin-views.business-settings.pages-inline-menu')
        @php( $page_data= json_decode($data->value, true))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{route('admin.business-settings.page-update', [$page])}}" method="post">
                        @csrf

                        <div class="card-header">
                            <h5 class="mb-0">{{translate(str_replace('-',' ',$page))}}</h5>

                            <label class="switcher show-status-text justify-content-end" for="page-status">
                                <input type="checkbox" class="switcher_input toggle-switch-message" value="1" name="status"
                                   id="page-status"  {{ $page_data['status'] == 1 ? 'checked':'' }}
                                   data-modal-id = "toggle-modal"
                                   data-toggle-id = "page-status"
                                   data-on-image = ""
                                   data-off-image = ""
                                   data-on-title = "{{translate('want_to_Turn_ON').' '.translate(str_replace('-','_',$page)).' '.translate('status')}}"
                                   data-off-title = "{{translate('want_to_Turn_OFF').' '.translate(str_replace('-','_',$page)) .' '. translate('status') }}"
                                   data-on-message = "<p>{{translate('if_you_enable_this_option_'.str_replace('-','_',$page).'_page_will_be_shown_in_the_user_app_and_website')}}</p>"
                                   data-off-message = "<p>{{translate('if_you_disable_this_option_'.str_replace('-','_',$page).'_page_will_not_be_shown_in_the_user_app_and_website')}}</p>">
                                <span class="switcher_control" data-ontitle="{{ translate('on') }}" data-offtitle="{{ translate('off') }}"></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <textarea class="form-control summernote" id="editor"
                                    name="value">{{ $page_data['content'] }}</textarea>
                            </div>
                            <div class="form-group">
                                <input class="form-control btn--primary" type="submit" value="{{translate('submit')}}" name="btn">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/summernote/summernote.min.js') }}"></script>
    <script>
        'use strict';
        $(document).on('ready', function () {
            $('.summernote').summernote({
                'height': 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                ]
            });
        });
    </script>
@endpush
