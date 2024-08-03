@foreach($home_categories as $category)
    <section>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="border-bottom gap-3 d-flex align-items-start justify-content-between mb-30">
                        <h3 class="styled-title">{{Str::limit($category['name'],18)}}</h3>
                        @if(count($category['products']) > 0)
                            <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}" class="btn-link text-capitalize">{{translate('view_all')}}
                                <i class="bi bi-chevron-right text-primary"></i></a>
                        @endif
                    </div>
                    <div class="swiper-container auto-item-width position-relative">
                        <div class="{{ $category['products']->count() > 0 ? 'swiper':'' }}" data-swiper-loop="true" data-swiper-items="auto" data-swiper-margin="20"  data-swiper-pagination-el="null" data-swiper-navigation-next="#next{{$category->id}}" data-swiper-navigation-prev="#prev{{$category->id}}" data-swiper-delay="4000">
                            <div class="swiper-wrapper text-center swiper-wrapper-rtl">
                                @foreach($category['products'] as $key=>$product)
                                        @include('theme-views.partials._product-category-card')
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper-button-next cleaning-nav-next" id="next{{$category->id}}"></div>
                        <div class="swiper-button-prev cleaning-nav-prev" id="prev{{$category->id}}"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endforeach
