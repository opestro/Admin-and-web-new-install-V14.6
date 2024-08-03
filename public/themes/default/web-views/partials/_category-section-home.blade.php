@if ($categories->count() > 0 )
    <section class="pb-4 rtl">
        <div class="container">
            <div>
                <div class="card __shadow h-100 max-md-shadow-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="categories-title m-0">
                                <span class="font-semibold">{{ translate('categories')}}</span>
                            </div>
                            <div>
                                <a class="text-capitalize view-all-text web-text-primary"
                                   href="{{route('categories')}}">{{ translate('view_all')}}
                                    <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 float-left' : 'right ml-1 mr-n1'}}"></i>
                                </a>
                            </div>
                        </div>
                        <div class="d-none d-md-block">
                            <div class="row mt-3">
                                @foreach($categories as $key => $category)
                                    @if ($key<10)
                                        <div class="text-center __m-5px __cate-item">
                                            <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                                                <div class="__img">
                                                    <img alt="{{ $category->name }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/category/'.$category->icon, type: 'category') }}">
                                                </div>
                                                <p class="text-center fs-13 font-semibold mt-2">{{Str::limit($category->name, 12)}}</p>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="d-md-none">
                            <div class="owl-theme owl-carousel categories--slider mt-3">
                                @foreach($categories as $key => $category)
                                    @if ($key<10)
                                        <div class="text-center m-0 __cate-item w-100">
                                            <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                                                <div class="__img mw-100 h-auto">
                                                    <img alt="{{ $category->name }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/category/'.$category->icon, type: 'category') }}">
                                                </div>
                                                <p class="text-center small mt-2">{{Str::limit($category->name, 12)}}</p>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
