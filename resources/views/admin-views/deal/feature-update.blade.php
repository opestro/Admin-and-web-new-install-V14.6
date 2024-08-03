@extends('layouts.back-end.app')
@section('title', translate('feature_Deal_Update'))
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-center">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/featured_deal.png')}}" alt="">
                {{translate('update_feature_deal')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.deal.update',[$deal['id']])}}"
                              class="text-start onsubmit-disable-action-button"
                              method="post">
                            @csrf
                            @php($language = getWebConfig(name:'pnc_language'))
                            @php($defaultLanguage = 'en')
                            @php($defaultLanguage = $language[0])
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($language as $lang)
                                    <li class="nav-item text-capitalize">
                                        <a class="nav-link lang-link {{$lang == $defaultLanguage ? 'active':''}}"
                                           href="#"
                                           id="{{$lang}}-link">{{getLanguageName($lang).'('.strtoupper($lang).')'}}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="form-group">
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
                                    <div class="row {{$lang != $defaultLanguage ? 'd-none':''}} lang-form"
                                         id="{{$lang}}-form">
                                        <input type="text" name="deal_type" value="feature_deal" class="d-none">
                                        <div class="col-md-12">
                                            <label for="name"
                                                   class="title-color text-capitalize">{{ translate('title')}}
                                                ({{strtoupper($lang)}})</label>
                                            <input type="text" name="title[]" class="form-control" id="title"
                                                   value="{{$lang==$defaultLanguage?$deal['title']:($translate[$lang]['title']??'')}}"
                                                   placeholder="{{translate('ex').':'.translate('LUX')}}"
                                                    {{$lang == $defaultLanguage? 'required':''}}>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}" id="lang">
                                @endforeach
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label for="name"
                                               class="title-color text-capitalize">{{ translate('start_date')}}</label>
                                        <input type="date" value="{{date('Y-m-d',strtotime($deal['start_date']))}}"
                                               name="start_date" required id="start-date-time"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="name"
                                               class="title-color text-capitalize">{{ translate('end_date')}}</label>
                                        <input type="date" value="{{date('Y-m-d', strtotime($deal['end_date']))}}"
                                               name="end_date" required id="end-date-time"
                                               class="form-control">
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
