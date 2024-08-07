@extends('theme-views.layouts.app')

@section('title', translate('FAQ'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 pt-3 mb-sm-5">
        <div class="page-title overlay py-5 __opacity-half background-custom-fit"
             data-bg-img = {{getStorageImages(path: imagePathProcessing(imageData: (isset($pageTitleBanner['value']) ?json_decode($pageTitleBanner['value'])?->image : null),path: 'banner'),source: theme_asset('assets/img/media/page-title-bg.png'))}}>
        <div class="container">
                <h1 class="absolute-white text-center">{{ translate('FAQ') }}</h1>
            </div>
        </div>
        <?php
            $length=count($helps);
                if($length%2!=0){
                    $first=($length+1)/2;
                }else{
                    $first=$length/2;
                }
        ?>
        <div class="container">
            <div class="my-4">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    @php($index = 0)
                    @foreach($helps as $index => $help)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading{{ $help['id'] }}">
                                <button class="accordion-button text-dark fw-semibold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $help['id'] }}"
                                        aria-expanded="false" aria-controls="flush-collapse{{ $help['id'] }}">
                                    {{ $help['question'] }}
                                </button>
                            </h2>
                            <div id="flush-collapse{{ $help['id'] }}"
                                 class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                 aria-labelledby="flush-heading{{ $help['id'] }}"
                                 data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    {{ $help['answer'] }}
                                </div>
                            </div>
                        </div>
                        @php($index++)
                    @endforeach
                </div>
            </div>
        </div>
    </main>
@endsection

