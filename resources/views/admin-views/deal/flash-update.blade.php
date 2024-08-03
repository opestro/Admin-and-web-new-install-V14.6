@extends('layouts.back-end.app')

@section('title', translate('flash_Deal_Update'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/flash_deal.png')}}" alt="">
                {{translate('flash_deals_update')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.deal.update',[$deal['id']])}}" method="post"
                              class="text-start onsubmit-disable-action-button"
                              enctype="multipart/form-data">
                            @csrf
                            @php($language = getWebConfig(name:'pnc_language'))
                            @php($defaultLanguage = 'en')
                            @php($defaultLanguage = $language[0])
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($language as $lang)
                                    <li class="nav-item text-capitalize">
                                        <a class="nav-link lang-link {{$lang == $defaultLanguage ? 'active':''}}"
                                           href="javascript:"
                                           id="{{$lang}}-link">{{getLanguageName($lang).'('.strtoupper($lang).')'}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col-lg-6">
                                    @foreach($language as $lang)
                                            <?php
                                            if (count($deal['translations'])) {
                                                $translate = [];
                                                foreach ($deal['translations'] as $t) {
                                                    if ($t->locale == $lang && $t->key == "title") {
                                                        $translate[$lang]['title'] = $t->value;
                                                    }
                                                }
                                            }
                                            ?>
                                        <div class="form-group {{$lang != $defaultLanguage ? 'd-none':''}} lang-form"
                                             id="{{$lang}}-form">
                                            <input type="text" name="deal_type" value="flash_deal" class="d-none">
                                            <label for="name" class="title-color">{{ translate('title')}}
                                                ({{strtoupper($lang)}})</label>
                                            <input type="text" name="title[]" class="form-control" id="title"
                                                   value="{{$lang==$defaultLanguage?$deal['title']:($translate[$lang]['title']??'')}}"
                                                   placeholder="{{translate('ex').':'.' '.translate('LUX')}}"
                                                    {{$lang == $defaultLanguage? 'required':''}}>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{$lang}}" id="lang">
                                    @endforeach
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{ translate('start_date')}}</label>
                                        <input type="date" value="{{date('Y-m-d',strtotime($deal['start_date']))}}"
                                               name="start_date" id="start-date-time" required
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{ translate('end_date')}}</label>
                                        <input type="date" value="{{date('Y-m-d', strtotime($deal['end_date']))}}"
                                               name="end_date" id="end-date-time" required
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div>
                                        <div class="form-group">
                                            <div class="d-flex justify-content-center">
                                                <img class="border radius-10 ratio-4:1 max-w-655px img-fit" id="viewer"
                                                     src="{{ getValidImage(path: 'storage/app/public/deal/'. $deal['banner'] , type: 'backend-basic') }}"
                                                     alt="{{translate('banner_image')}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="title-color">{{translate('upload_Image')}}</label>
                                            <span class="text-info ml-0">( {{translate('ratio').' '.'5:1'}})</span>
                                            <div class="custom-file text-left">
                                                <input type="file" name="image" id="custom-file-upload"
                                                       class="custom-file-input image-input" data-image-id="viewer"
                                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label"
                                                       for="custom-file-upload">{{translate('choose_File')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" id="reset"
                                        class="btn btn-secondary">{{ translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/deal.js')}}"></script>
@endpush
