@extends('layouts.back-end.app')

@section('title', translate('announcement'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/announcement.png')}}" alt="">
                {{translate('announcement_setup')}}
            </h2>
        </div>
        <form action="{{ route('admin.business-settings.announcement') }}" method="post" enctype="multipart/form-data">
            @csrf
            @if (isset($announcement))
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('announcement_Setup')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-10 align-items-center mb-2">
                            <input type="radio" name="announcement_status"
                                    value="1" {{$announcement['status']==1?'checked':''}}>
                            <label class="title-color mb-0">{{translate('active')}}</label>
                        </div>
                        <div class="d-flex gap-10 align-items-center mb-4">
                            <input type="radio" name="announcement_status"
                                    value="0" {{$announcement['status']==0?'checked':''}}>
                            <label class="title-color mb-0">{{translate('inactive')}}</label>
                        </div>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="form-group text-center">
                                <label class="title-color">{{translate('background_color')}}</label>
                                <input type="color" name="announcement_color"
                                        value="{{ $announcement['color'] }}" id="background-color"
                                        class="form-control form-control_color">
                                <div class="title-color mb-4 mt-3" id="background-color-set">{{ $announcement['color'] }}</div>
                            </div>
                            <div class="form-group text-center">
                                <label class="title-color">{{translate('text_color')}}</label>
                                <input type="color" name="text_color" id="text-color" value="{{ $announcement['text_color'] }}"
                                        class="form-control form-control_color">
                                <div class="title-color mb-4 mt-3" id="text-color-set">{{ $announcement['text_color'] }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="title-color d-flex">{{translate('text')}}</label>
                            <input class="form-control" type="text" name="announcement"
                                    value="{{ $announcement['announcement'] }}">
                        </div>
                        <div class="justify-content-end d-flex">
                            <button type="submit" class="btn btn--primary px-4">{{translate('publish')}}</button>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/business-setting.js')}}"></script>
@endpush
