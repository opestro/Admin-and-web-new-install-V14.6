@extends('layouts.back-end.app-seller')

@section('title', translate('delivery_man_Review'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-10 mb-3">
            <div class="">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/deliveryman.png')}}" width="20" alt="">
                    {{$deliveryMan['f_name']. ' '. $deliveryMan['l_name']}}
                </h2>
            </div>

            <div class="d-flex justify-content-end flex-wrap gap-10">
                <a href="{{route('vendor.delivery-man.list')}}" class="btn btn--primary">
                    <i class="tio-back-ui"></i> {{translate('back')}}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body my-3">
                <div class="row align-items-md-center gx-md-5">
                    <div class="col-md-auto mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <img class="avatar avatar-xxl avatar-4by3 {{Session::get('direction') === "rtl" ? 'ml-4' : 'mr-4'}}"
                                src="{{getValidImage(path:'storage/app/public/delivery-man/'.$deliveryMan['image'],type: 'backend-profile')}}"
                                alt="Image Description">
                            <div class="d-block">
                                <h4 class="display-2 text-dark mb-0">
                                    {{number_format($averageRatting, 2, '.', ' ')}}
                                </h4>
                                <p> {{translate('of')}} {{$reviews->count()?? 0}} {{translate('reviews')}}
                                    <span
                                        class="badge badge-soft-dark badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md">
                        <ul class="list-unstyled list-unstyled-py-2 mb-0">
                            <li class="d-flex align-items-center font-size-sm">
                                <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{'5'.' '.translate('star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($five/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$five}}</span>
                            </li>
                            <li class="d-flex align-items-center font-size-sm">
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{translate('4')}} {{translate('star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($four/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$four}}</span>
                            </li>
                            <li class="d-flex align-items-center font-size-sm">
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{translate('3')}} {{translate('star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($three/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$three}}</span>
                            </li>
                            <li class="d-flex align-items-center font-size-sm">
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{translate('2')}} {{translate('star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($two/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$two}}</span>
                            </li>
                            <li class="d-flex align-items-center font-size-sm">
                                <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{translate('1')}} {{translate('star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($one/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$one}}</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
        <div class="card card-body mt-3">
            <div class="row border-bottom pb-3 align-items-center mb-20">
                <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0"></div>
                <div class="col-sm-8 col-md-6 col-lg-4">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                   placeholder="{{ translate('search_by_Order_ID') }}"
                                   aria-label="Search orders" value="{{ $searchValue}}" required>
                            <button type="submit" class="btn btn--primary">{{ translate('search') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <form action="{{ url()->current() }}" method="GET">
                <div class="row gy-3 align-items-end">
                    <div class="col-md-3">
                        <div>
                            <label for="from" class="title-color d-flex">{{ translate('from') }}</label>
                            <input type="date" name="from_date" id="from_date" value="{{ $filters['from'] }}"
                                   class="form-control"
                                   title="{{ translate('from_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div>
                            <label for="to_date" class="title-color d-flex">{{ translate('to') }}</label>
                            <input type="date" name="to_date" id="to_date" value="{{ $filters['to'] }}"
                                   class="form-control"
                                   title="{{ ucfirst(translate('to_date')) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div>
                            <select class="form-control" name="rating">
                                <option value="" selected>{{'--'.translate('select_Rating').'--'}}</option>
                                <option value="1" {{ $filters['rating']==1 ? 'selected': '' }}>{{ translate('1') }}</option>
                                <option value="2" {{ $filters['rating']==2 ? 'selected': '' }}>{{ translate('2') }}</option>
                                <option value="3" {{ $filters['rating']==3 ? 'selected': '' }}>{{ translate('3') }}</option>
                                <option value="4" {{ $filters['rating']==4 ? 'selected': '' }}>{{ translate('4') }}</option>
                                <option value="5" {{ $filters['rating']==5 ? 'selected': '' }}>{{ translate('5') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div>
                            <button id="filter" type="submit" class="btn btn--primary btn-block filter">
                                <i class="tio-filter-list nav-icon"></i>
                                {{ translate('filter') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card mt-3">
            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('order_ID')}}</th>
                        <th>{{translate('reviewer')}}</th>
                        <th>{{translate('review')}}</th>
                        <th>{{translate('date')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reviews as $key=>$review)
                        <tr>
                            <td>
                                {{$reviews->firstItem()+$key}}
                            </td>
                            <td>
                                <a class="title-color hover-c1" href="{{$review->order_id ? route('vendor.orders.details',$review['order_id']) : ''}}">{{ $review['order_id'] }}</a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img" src="{{getValidImage(path: 'storage/app/public/profile/'.$review->customer->image,type: 'backend-profile')}}"
                                            alt="{{translate('image_description')}}">
                                    </div>
                                    <div class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                    <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                                            class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                            title="Verified Customer"></i></span>
                                        <span
                                            class="d-block font-size-sm text-body">{{$review->customer->email??""}}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-wrap">
                                    <div class="d-flex mb-2">
                                        <label class="badge badge-soft-info">
                                            <span>{{$review->rating}} <i class="tio-star"></i> </span>
                                        </label>
                                    </div>
                                    <p>{{$review['comment']}}</p>
                                </div>
                            </td>
                            <td>
                                {{date('d M Y H:i:s',strtotime($review['updated_at']))}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {{ $reviews->links() }}
                </div>
            </div>
            @if(count($reviews)==0)
                @include('layouts.back-end._empty-state',['text'=>'no_review_found'],['image'=>'default'])
            @endif
        </div>
    </div>
@endsection
