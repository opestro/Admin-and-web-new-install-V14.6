@extends('layouts.back-end.app')

@section('title', $seller?->shop->name ?? translate("shop_name_not_found"))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-center">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{translate('vendor_details')}}
            </h2>
        </div>
        <div class="flex-between d-sm-flex row align-items-center justify-content-between mb-2 mx-1">
            <div>
                @if ($seller->status=="pending")
                    <div class="mt-4 pr-2">
                        <div class="flex-between">
                            <div class="mx-1"><h4><i class="tio-shop-outlined"></i></h4></div>
                            <div><h4>{{translate('vendor_request_for_open_a_shop.')}}</h4></div>
                        </div>
                        <div class="text-center">
                            <form class="d-inline-block" action="{{route('admin.vendors.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"
                                        class="btn btn--primary btn-sm">{{translate('approve')}}</button>
                            </form>
                            <form class="d-inline-block" action="{{route('admin.vendors.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit"
                                        class="btn btn-danger btn-sm">{{translate('reject')}}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="page-header">
            <div class="flex-between row mx-1">
                <div>
                    <h1 class="page-header-title">{{ $seller?->shop->name ?? translate("shop_Name")." : ".translate("update_Please") }}</h1>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <ul class="nav nav-tabs flex-wrap page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link "
                           href="{{ route('admin.vendors.view',$seller->id) }}">{{translate('shop')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.vendors.view',['id'=>$seller->id, 'tab'=>'order']) }}">{{translate('order')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.vendors.view',['id'=>$seller->id, 'tab'=>'product']) }}">{{translate('product')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.vendors.view',['id'=>$seller->id, 'tab'=>'setting']) }}">{{translate('setting')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.vendors.view',['id'=>$seller->id, 'tab'=>'transaction']) }}">{{translate('transaction')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"
                           href="{{ route('admin.vendors.view',['id'=>$seller->id, 'tab'=>'review']) }}">{{translate('review')}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content container-fluid p-0">
            <div class="row gx-2 gx-lg-3">
                <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                    <div class="card">
                        <div class="px-3 py-4">
                            <div class="row align-items-center">
                                <div class="col-sm-4 col-md-6 col-lg-8 mb-3 mb-sm-0">
                                    <h5 class="mb-0 d-flex gap-1 align-items-center">
                                        {{translate('review_table') }}
                                        <span
                                            class="badge badge-soft-dark radius-50 fz-12">{{ $reviews->total() }}</span>
                                    </h5>
                                </div>
                                <div class="col-sm-8 col-md-6 col-lg-4">
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-custom">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="searchValue"
                                                   class="form-control"
                                                   placeholder="{{translate('search_by_product_name')}}" aria-label="Search orders"
                                                   value="{{ request('searchValue') }}">
                                            <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive datatable-custom">
                            <table id="columnSearchDatatable"
                                   style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                   class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('product')}}</th>
                                    <th>{{translate('review')}}</th>
                                    <th>{{translate('rating')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($reviews as $key=> $review)
                                    <tr>
                                        <td>{{$reviews->firstItem()+ $key}}</td>
                                        <td>
                                        <span class="d-block font-size-sm text-body">
                                            @if($review->product)
                                                <a href="{{route('admin.products.view',['addedBy'=>($review->product->added_by =='seller'?'vendor' : 'in-house'),'id'=>$review->product->id])}}"
                                                    class="title-color hover-c1">
                                                    {{$review->product['name']}}
                                                </a>
                                            @else
                                                <a href="javascript:" class="title-color hover-c1">
                                                    {{ translate('product_not_found') }}
                                                </a>
                                            @endif
                                        </span>
                                        </td>
                                        <td>
                                            <p class="text-wrap mb-1">
                                                {{$review->comment ?? translate("no_Comment_Found")}}
                                            </p>
                                            @if($review->attachment)
                                                @foreach (json_decode($review->attachment) as $img)
                                                    <a href="{{dynamicStorage(path: 'storage/app/public/review')}}/{{$img}}"
                                                        data-lightbox="mygallery">
                                                        <img class="p-1" width="60" height="60"
                                                             src="{{ getValidImage(path: 'storage/app/public/review/'.$img, type: 'backend-basic') }}"
                                                                alt="" >
                                                    </a>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <label class="mb-0 badge badge-soft-info">
                                                {{$review->rating}} <i class="tio-star"></i>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-4">
                            <div class="px-4 d-flex justify-content-lg-end">
                                {{$reviews->links()}}
                            </div>
                        </div>

                        @if(count($reviews)==0)
                            @include('layouts.back-end._empty-state',['text'=>'no_review_found'],['image'=>'default'])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
