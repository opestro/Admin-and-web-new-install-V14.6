@extends('layouts.admin.app')

@section('title','Delivery Man Preview')

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-6">
                    <h1 class="page-header-title">{{$dm['f_name'].' '.$dm['f_name']}}</h1>
                </div>
                <div class="col-6">
                    <a href="{{url()->previous()}}" class="btn btn--primary float-right">
                        <i class="tio-back-ui"></i> {{translate('back')}}
                    </a>
                </div>
            </div>
            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:">
                        {{translate('deliveryman')}} {{translate('reviews')}}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card mb-3 mb-lg-5">
            <div class="card-body">
                <div class="row align-items-md-center gx-md-5">
                    <div class="col-md-auto mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <img class="avatar avatar-xxl avatar-4by3 mr-4"
                                 onerror="this.src='{{dynamicAsset(path: 'public/assets/admin/img/160x160/img1.jpg')}}'"
                                 src="{{dynamicStorage(path: 'storage/app/public/delivery-man')}}/{{$dm['image']}}"
                                 alt="Image Description">
                            <div class="d-block">
                                <h4 class="display-2 text-dark mb-0">{{count($dm->rating)>0?number_format($dm->rating[0]->average, 2, '.', ' '):0}}</h4>
                                <p> of {{$dm->reviews->count()}} {{translate('reviews')}}
                                    <span class="badge badge-soft-dark badge-pill ml-1"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md">
                        <ul class="list-unstyled list-unstyled-py-2 mb-0">

                        @php($total=$dm->reviews->count())
                            <li class="d-flex align-items-center font-size-sm">
                                @php($five=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],5))
                                <span
                                    class="mr-3">5 star</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($five/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$five}}</span>
                            </li>
                            <li class="d-flex align-items-center font-size-sm">
                                @php($four=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],4))
                                <span class="mr-3">4 star</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($four/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$four}}</span>
                            </li>
                            <li class="d-flex align-items-center font-size-sm">
                                @php($three=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],3))
                                <span class="mr-3">3 star</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($three/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$three}}</span>
                            </li>
                            <li class="d-flex align-items-center font-size-sm">
                                @php($two=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],2))
                                <span class="mr-3">2 star</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($two/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$two}}</span>
                            </li>
                            <li class="d-flex align-items-center font-size-sm">
                                @php($one=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],1))
                                <span class="mr-3">1 star</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($one/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$one}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0, 3, 6],
                        "orderable": false
                      }],
                     "order": [],
                     "info": {
                       "totalQty": "#datatableWithPaginationInfoTotalQty"
                     },
                     "search": "#datatableSearch",
                     "entries": "#datatableEntries",
                     "pageLength": 25,
                     "isResponsive": false,
                     "isShowPaging": false,
                     "pagination": "datatablePagination"
                   }'>
                    <thead class="thead-light">
                    <tr>
                        <th>{{translate('reviewer')}}</th>
                        <th>{{translate('review')}}</th>
                        <th>{{translate('attachment')}}</th>
                        <th>{{translate('date')}}</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($reviews as $review)
                        <tr>
                            <td>
                                <a class="d-flex align-items-center"
                                   href="{{route('admin.customer.view',[$review['user_id']])}}">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img" width="75" height="75"
                                             onerror="this.src='{{dynamicAsset(path: 'public/assets/admin/img/160x160/img1.jpg')}}'"
                                             src="{{dynamicStorage(path: 'storage/app/public/profile/'.$review->customer->image)}}"
                                             alt="Image Description">
                                    </div>
                                    <div class="ml-3">
                                    <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                                            class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                            title="Verified Customer"></i></span>
                                        <span class="d-block font-size-sm text-body">{{$review->customer->email}}</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div class="text-wrap __w-18rem">
                                    <div class="d-flex mb-2">
                                        <label class="badge badge-soft-info">
                                            {{$review->rating}} <i class="tio-star"></i>
                                        </label>
                                    </div>

                                    <p>
                                        {{$review['comment']}}
                                    </p>
                                </div>
                            </td>
                            <td>
                                @foreach(json_decode($review['attachment'],true) as $attachment)
                                    <img width="100" onerror="this.src='{{dynamicAsset(path: 'public/assets/admin/img/160x160/img2.jpg')}}'" src="{{dynamicStorage(path: 'storage/app/public')}}/{{$attachment}}">
                                @endforeach
                            </td>
                            <td>
                                {{date('d M Y H:i:s',strtotime($review['created_at']))}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-12">
                        {!! $reviews->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
