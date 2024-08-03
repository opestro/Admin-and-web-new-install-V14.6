@extends('layouts.front-end.app')

@section('title',translate('FAQ'))

@push('css_or_js')
    <meta property="og:image" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="FAQ of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="FAQ of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
@endpush

@section('content')
    <div class="__inline-60">
        <div class="container rtl">
            <div class="row">
                <div class="col-md-12 sidebar_heading text-center mb-2">
                    <h1 class="text-center pt-4 fs-24 font-semi-bold text-capitalize">{{ translate('frequently_asked_question') }}</h1>
                </div>
            </div>
            <hr>
        </div>

        <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl">
            <div class="row">

                @php $length=count($helps); @endphp
                @if(count($helps) > 0)
                    @php if($length%2!=0){$first=($length+1)/2;}else{$first=$length/2;}@endphp
                    <div class="col-lg-2"></div>
                    <section class="col-lg-10 mt-3">
                        <section class="container pt-4 pb-5 ">
                            <div class="row pt-4">
                                <div class="col-sm-6">
                                    <ul class="list-unstyled">
                                        @for($i=0;$i<$first;$i++)
                                            <div id="accordion">
                                                <div class="row mb-0 text-black">
                                                    <div class="col-1 mt-3">
                                                        <i class="czi-book text-muted mr-2"></i>
                                                    </div>
                                                    <div class="col-11">
                                                        <button class="btnF btn-link collapsed text-align-direction" data-toggle="collapse"
                                                                data-target="#collapseTwo{{ $helps[$i]['id'] }}"
                                                                aria-expanded="false" aria-controls="collapseTwo">
                                                            <span class="d-flex align-items-center border-bottom pb-3 mb-3">{{ $helps[$i]['question'] }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="collapseTwo{{ $helps[$i]['id'] }}" class="collapse"
                                                     aria-labelledby="headingTwo" data-parent="#accordion">
                                                    <div class="card-body">
                                                        {{ $helps[$i]['answer'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </ul>
                                </div>

                                <div class="col-sm-6">
                                    <ul class="list-unstyled">
                                        @for($i=$first; $i<$length; $i++)
                                            <div id="accordion">
                                                <div class="row mb-0 text-black">
                                                    <div class="col-1 mt-3">
                                                        <i class="czi-book text-muted mr-2"></i>
                                                    </div>
                                                    <div class="col-11">
                                                        <button class="btnF btn-link collapsed text-align-direction" data-toggle="collapse"
                                                                data-target="#collapseTwo{{ $helps[$i]['id'] }}"
                                                                aria-expanded="false" aria-controls="collapseTwo">
                                                            <span class="d-flex align-items-center border-bottom pb-3 mb-3">{{ $helps[$i]['question'] }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="collapseTwo{{ $helps[$i]['id'] }}" class="collapse"
                                                     aria-labelledby="headingTwo" data-parent="#accordion">
                                                    <div class="card-body">
                                                        {{ $helps[$i]['answer'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </ul>
                                </div>

                            </div>
                        </section>
                    </section>
                @else
                    <div class="col-12">
                        <div class="d-flex justify-content-center align-items-center pt-4">
                            <div class="d-flex flex-column justify-content-center align-items-center gap-3">
                                <img src="{{ dynamicStorage(path: 'public/assets/front-end/img/empty-icons/empty-faqs.svg') }}"
                                     alt="{{ translate('brand') }}" class="img-fluid" width="100">
                                <h5 class="text-muted fs-14 font-semi-bold text-center">{{ translate('there_is_no_FAQs') }}</h5>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


