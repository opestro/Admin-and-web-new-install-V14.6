@extends('layouts.front-end.app')

@section('title', translate('all_Brands'))

@push('css_or_js')
    <meta property="og:image" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Brands of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Brands of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
@endpush

@section('content')

    <div class="container pb-3 mb-2 mb-md-4 rtl text-align-direction">
        <div class="bg-primary-light rounded-10 my-4 p-3 p-sm-4"
             data-bg-img="{{ theme_asset(path: 'public/assets/front-end/img/media/bg.png') }}">
            <div class="d-flex flex-column gap-1 text-primary">
                <h4 class="mb-0 text-start fw-bold text-primary text-uppercase">
                    {{ translate('brands') }}
                </h4>
                <p class="fs-14 fw-semibold mb-0">
                    {{translate('Find_your_favourite_brands_and_products')}}
                </p>
            </div>
        </div>

        @if(count($brands) > 0)
            <div class="brand_div-wrap mb-4">
                @foreach($brands as $brand)
                    <a href="{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}" class="brand_div"
                       data-toggle="tooltip" title="{{$brand->name}}">
                        <img alt="{{$brand->name}}"
                             src="{{ getValidImage(path: 'storage/app/public/brand/'.$brand->image, type: 'brand') }}">
                    </a>
                @endforeach
            </div>
        @else
            <div class="d-flex justify-content-center align-items-center pt-3">
                <div class="d-flex flex-column justify-content-center align-items-center gap-3">
                    <img src="{{ dynamicStorage(path: 'public/assets/front-end/img/empty-icons/empty-brand.svg') }}"
                         alt="{{ translate('brand') }}" class="img-fluid" width="100">
                    <h5 class="text-muted fs-14 font-semi-bold text-center">{{ translate('There_is_no_brands') }}</h5>
                </div>
            </div>
        @endif


        <div class="row mx-n2">
            <div class="col-md-12">
                <div class="text-center">
                    {!! $brands->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{theme_asset(path: 'public/assets/front-end/vendor/nouislider/distribute/nouislider.min.js')}}"></script>
@endpush
