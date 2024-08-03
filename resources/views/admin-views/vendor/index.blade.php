@php use Illuminate\Support\Str; @endphp
@extends('layouts.back-end.app')

@section('title', translate('vendor_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{translate('vendor_List')}}
                <span class="badge badge-soft-dark radius-50 fz-12">{{ $vendors->total() }}</span>
            </h2>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="d-flex justify-content-between gap-10 flex-wrap align-items-center">
                            <div class="">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom width-500px">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                            placeholder="{{translate('search_by_shop_name_or_vendor_name_or_phone_or_email')}}" aria-label="Search orders" value="{{ request('searchValue') }}">
                                        <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <div class="dropdown text-nowrap">
                                    <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{translate('export')}}
                                        <i class="tio-chevron-down"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a type="submit" class="dropdown-item d-flex align-items-center gap-2 " href="{{route('admin.vendors.export',['searchValue' => request('searchValue')])}}">
                                                <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                                {{translate('excel')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{route('admin.vendors.add')}}" type="button" class="btn btn--primary text-nowrap">
                                    <i class="tio-add"></i>
                                    {{translate('add_New_Vendor')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('shop_name')}}</th>
                                <th>{{translate('vendor_name')}}</th>
                                <th>{{translate('contact_info')}}</th>
                                <th>{{translate('status')}}</th>
                                <th class="text-center">{{translate('total_products')}}</th>
                                <th class="text-center">{{translate('total_orders')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vendors as $key=>$seller)
                                <tr>
                                    <td>{{$vendors->firstItem()+$key}}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-10 w-max-content">
                                            <img width="50"
                                            class="avatar rounded-circle" src="{{ getValidImage(path: 'storage/app/public/shop/'.($seller->shop?$seller->shop->image:''), type: 'backend-basic') }}"
                                                alt="">
                                            <div>
                                                <a class="title-color" href="{{ route('admin.vendors.view', ['id' => $seller->id]) }}">{{ $seller->shop ? Str::limit($seller->shop->name, 20) : translate('shop_not_found')}}</a>
                                                <br>
                                                <span class="text-danger">
                                                    @if($seller->shop && $seller->shop->temporary_close)
                                                        {{ translate('temporary_closed') }}
                                                    @elseif($seller->shop && $seller->shop->vacation_status && $current_date >= date('Y-m-d', strtotime($seller->shop->vacation_start_date)) && $current_date <= date('Y-m-d', strtotime($seller->shop->vacation_end_date)))
                                                        {{ translate('on_vacation') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a title="{{translate('view')}}"
                                           class="title-color"
                                           href="{{route('admin.vendors.view',$seller->id)}}">
                                            {{$seller->f_name}} {{$seller->l_name}}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <strong><a class="title-color hover-c1" href="mailto:{{$seller->email}}">{{$seller->email}}</a></strong>
                                        </div>
                                        <a class="title-color hover-c1" href="tel:{{$seller->phone}}">{{$seller->phone}}</a>
                                    </td>
                                    <td>
                                        {!! $seller->status=='approved'?'<label class="badge badge-success">'.translate('active').'</label>':'<label class="badge badge-danger">'.translate('inactive').'</label>' !!}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('admin.vendors.product-list',[$seller['id']])}}"
                                           class="btn text--primary bg-soft--primary font-weight-bold px-3 py-1 mb-0 fz-12">
                                            {{$seller->product->count()}}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('admin.vendors.order-list',[$seller['id']])}}"
                                            class="btn text-info bg-soft-info font-weight-bold px-3 py-1 fz-12 mb-0">
                                            {{$seller->orders->where('seller_is','seller')->where('order_type','default_type')->count()}}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a title="{{translate('view')}}"
                                                class="btn btn-outline-info btn-sm square-btn"
                                                href="{{route('admin.vendors.view',$seller->id)}}">
                                                <i class="tio-invisible"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            {!! $vendors->links() !!}
                        </div>
                    </div>
                    @if(count($vendors)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_vendor_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
