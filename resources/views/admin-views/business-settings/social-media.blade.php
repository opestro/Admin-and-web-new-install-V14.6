@php
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app')
@section('title', translate('social_media'))
@section('content')
    @php($direction = Session::get('direction'))
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/social media.png')}}" width="20" alt="">
                {{translate('social_media')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ translate('social_media_form')}}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.social-media-store') }}" method="post" style="text-align: {{$direction === "rtl" ? 'right' : 'left'}};" id="social-media-links">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name" class="title-color">{{translate('name')}}</label>
                                        <select class="form-control w-100" name="name" id="name" required>
                                            <option value="">{{'---'.translate('select').'---'}}</option>
                                            <option value="instagram">{{translate('instagram')}}</option>
                                            <option value="facebook">{{translate('facebook')}}</option>
                                            <option value="twitter">{{translate('twitter')}}</option>
                                            <option value="linkedin">{{translate('linkedIn')}}</option>
                                            <option value="pinterest">{{translate('pinterest')}}</option>
                                            <option value="google-plus">{{translate('google_plus')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <input type="hidden" id="id" name="id">
                                        <label for="link" class="title-color">{{ translate('social_media_link')}}</label>
                                        <input type="url" name="link" class="form-control" id="link"
                                               placeholder="{{translate('enter_Social_Media_Link')}}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" id="id">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-10 justify-content-end flex-wrap">
                                <button type="submit" id="actionBtn" class="btn btn--primary px-4">{{ translate('save')}}</button>
                                <a id="update" class="btn btn--primary px-4 d--none">{{ translate('update')}}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <h5 class="mb-0 d-flex">{{ translate('social_media_table')}}</h5>
                    </div>
                    <div class="pb-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100" id="dataTable"
                                   style="text-align: {{$direction === "rtl" ? 'right' : 'left'}};">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{ translate('sl')}}</th>
                                        <th>{{ translate('name')}}</th>
                                        <th>{{ translate('link')}}</th>
                                        <th class="text-center">{{ translate('status')}}</th>
                                        <th>{{ translate('action')}}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="get-update" data-text="{{translate('update')}}" data-action="{{route('admin.business-settings.social-media-update')}}"></span>
    <span id="get-update-view" data-text="{{translate('edit')}}" data-action="{{route('admin.business-settings.social-media-edit')}}"></span>
    <span id="get-delete"
          data-confirm="{{translate('are_you_sure_delete_this_social_media').'?'}}"
          data-success="{{translate('social_media_deleted_successfully').'.'}}"
          data-action="{{route('admin.business-settings.social-media-delete')}}">
    </span>
    <span id="get-social-media-links-data"
        data-success = "{{translate('social_Media_inserted_Successfully')}}"
        data-info = "{{translate('social_info_updated_successfully')}}"
        data-save = "{{translate('save')}}"
        data-action="{{route('admin.business-settings.social-media-store')}}">
    </span>
    <span id="get-fetch-route" data-action="{{route('admin.business-settings.fetch')}}"></span>
    <span id="get-toggle-status-text"
          data-action="{{route('admin.business-settings.social-media-status-update')}}"
          data-turn-on-text="{{translate('Want_to_Turn_ON').'?'}}"
          data-turn-off-text="{{translate('Want_to_Turn_OFF').'?'}}"
          data-status="{{translate('status')}}"
          data-on-message="{{translate('if_enabled_this_icon_will_be_available_on_the_website_and_customer_app').'.'}}"
          data-off-message="{{translate('if_disabled_this_icon_will_be_hidden_from_the_website_and_customer_app').'.'}}">
    </span>
@endsection
@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/social-media.js')}}"></script>
@endpush
