@php
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app')

@section('title', translate('language_Translate'))

@push('css_or_js')
    <link href="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    @php($direction = Session::get('direction') === "rtl" ? 'right' : 'left')
    <div class="content container-fluid">

        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/language.png')}}" alt="">
                {{translate('language')}}
            </h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card" style="text-align: {{$direction}};">
                    <div class="card-header">
                        <h4 class="m-0">{{translate('language_content_table')}}</h4>
                        <div class="d-flex align-items-center gap-2">
                            @if(env('APP_MODE') != 'demo')
                                <button class="btn btn-primary" data-toggle="modal" data-target="#warning-modal">
                                    {{ translate('Translate_All') }}
                                </button>
                            @else
                                <button class="btn btn-primary call-demo">
                                    {{ translate('Translate_All') }}
                                </button>
                            @endif

                            <a href="{{route('admin.business-settings.language.index')}}"
                               class="btn btn-danger btn-icon-split float-right">
                                <i class="tio-first-page"></i>
                                <span class="text text-capitalize">{{translate('back')}}</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead class="bg-white">
                                <tr>
                                    <th class="max-width-100px border-0">{{translate('SL')}}</th>
                                    <th class="width-400px border-0">{{translate('key')}}</th>
                                    <th class="max-width-300px border-0">{{translate('value')}}</th>
                                    <th class="max-width-150px border-0">{{translate('auto_translate')}}</th>
                                    <th class="max-width-150px border-0">{{translate('update')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <i class="fa fa-spinner fa-spin"></i> {{ translate('Loading') }}...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade language-complete-modal" id="complete-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="py-5">
                        <div class="mb-4">
                            <img src="{{dynamicAsset('/public/assets/back-end/img/language-complete.png')}}" alt="">
                        </div>
                        <h4 class="mb-3">
                            {{ translate('Your_file_has_been_successfully_translated') }}
                        </h4>
                        <p class="mb-4 text-9EADC1 max-w-362px mx-auto translateCountSuccess"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="warning-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex gap-3 align-items-center mb-3">
                        <div>
                            <img src="{{ dynamicAsset('public/assets/back-end/img/invalid-icon.png') }}" alt="">
                        </div>
                        <div class="w-0 flex-grow-1">
                            <h3>{{ translate('warning') }}!</h3>
                            <p class="mb-0">
                                {{ translate('are_you_sure,_want_to_start_auto_translation') }} ?
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-3">
                        @if(env('APP_MODE') != 'demo')
                            <button type="button" class="btn btn--primary" data-dismiss="modal" id="translating-modal-start">
                                {{ translate('Continue') }}
                            </button>
                        @else
                            <button type="button" class="btn btn-primary call-demo" data-dismiss="modal">
                                {{ translate('Continue') }}
                            </button>
                        @endif

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ translate('cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade language-complete-modal" id="translating-modal" data-backdrop="static"
         data-keyboard="false" aria-labelledby="languageCompleteModalLabel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="py-5 px-sm-2">
                        <div class="progress-circle-container mb-4">
                            <svg class="progress-circle spinner-border border-0 w-43px h-100" width="100" height="100" viewBox="0 0 100 100">
                                <circle class="bg" cx="50" cy="50" r="45"></circle>
                                <circle class="progress" cx="50" cy="50" r="45"></circle>
                            </svg>
                        </div>
                        <h4 class="mb-2">
                            <?php
                                $getDuration = isset($totalMessages) ? (($totalMessages / 60) / 60) : 0;
                                $getHours = floor($getDuration);
                                $getMinutes = round(($getDuration - $getHours) * 60);
                            ?>

                            @if($totalMessages <= 0)
                                {{ translate('All_new_message_are_translated') }}
                            @elseif($getDuration < 1)
                                {{ translate('Translating_may_take_up_to') }} {{ $getMinutes }} {{ translate('minutes') }}
                            @elseif($getDuration >= 1)
                                @if($getMinutes == 0)
                                    {{ translate('Translating_may_take_up_to') }} {{ $getHours }} {{ translate('hour') }}
                                @else
                                    {{ translate('Translating_may_take_up_to') }} {{ $getHours }} {{ translate('hour') }} {{ $getMinutes }} {{ translate('minutes') }}
                                @endif
                            @endif

                        </h4>
                        <p class="mb-4">
                            {{ translate('please_do_not_close_the_browser_or_this_page') }}
                        </p>
                        <div class="max-w-215px mx-auto">
                            <div class="d-flex flex-wrap mb-1 justify-content-between font-semibold text--title">
                                <span>{{ translate('in_Progress') }}</span>
                                <span class="translating-modal-success-rate">0%</span>
                            </div>
                            <div class="progress mb-3 h-5px">
                                <div class="progress-bar bg-success rounded-pill translating-modal-success-bar" style="width: 0%"></div>
                            </div>
                        </div>
                        <p class="mb-4 text-9EADC1">
                            {{ translate('translating_all_the_content_may_take_some_time.') }} {{ translate('if_you_encounter_any_issues_during_the_translation_process,_please_contact_your_administrator.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <span id="total-messages-of-current-language"
          data-total="{{ $totalMessages }}"
          data-message-group="{{ $totalMessages > 0 ? $messageGroup : 1 }}"
          @if($totalMessages > 0)
            data-message="{{ translate('your') . ' ' . $totalMessages . ' ' . translate('messages_successfully_translated') }}"
          @else
              data-message="{{ translate('all_messages_are_in_translated') }}"
          @endif
    ></span>
    <span id="get-auto-translate-route-and-text" data-route="{{route('admin.business-settings.language.auto-translate', [$lang])}}"
          data-success-text="{{translate('key_translated_successfully')}}">
    </span>
    <span id="get-auto-translate-all-route-and-text" data-route="{{ route('admin.business-settings.language.auto-translate-all', [$lang]) }}"
          data-success-text="{{translate('all_translated_successfully')}}">
    </span>
    <span id="get-translate-route-and-text" data-route="{{route('admin.business-settings.language.translate-submit', [$lang])}}"
          data-success-text="{{translate('text_updated_successfully')}}">
    </span>
    <span id="get-data-table-route-and-text"
          data-route="{{ route('admin.business-settings.language.translate.list', ['lang' => $lang]) }}"
          data-page-length="{{ getWebConfig(name:'pagination_limit') }}"
          data-info="{{ translate('showing').' '.'_START_'.' '.translate('To').' '.'_END_'.' '.translate('of').' '.'_TOTAL_'.' '.translate('entries') }}"
          data-info-empty="{{ translate('showing').' '. 0 .translate('To').' '. 0 .' ' .translate('of').' '. 0 .' '.translate('entries') }}"
          data-info-filtered="{{ translate('filtered').' '.'_MAX_'.' '.translate('total_entries') }}"
          data-empty-table="{{  translate('no_data_found') }}"
          data-zero-records="{{ translate('no_matching_data_found') }}"
          data-search="{{ translate('search').':' }}"
          data-length-menu="{{ translate('show').'_MENU_'.translate('entries')  }}"
          data-paginate-first="{{translate('first')}}"
          data-paginate-last="{{translate('last')}}"
          data-paginate-next="{{translate('next')}}"
          data-paginate-previous="{{translate('previous')}}"
    ></span>
@endsection

@push('script')

    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/translate.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            // $('#warning-modal').modal('show')
            // $('#complete-modal').modal('show')
            // $('#translating-modal').modal('show')


            function setProgress(percentage) {
                const circle = $('.progress-circle .progress');
                const radius = circle.attr('r');
                const circumference = 2 * Math.PI * radius;
                const offset = circumference - (percentage / 100 * circumference);

                circle.css('stroke-dashoffset', offset);
            }

            const timer = setTimeout(()=> {
                setProgress(87)
            }, 1000)


        });
    </script>

@endpush
