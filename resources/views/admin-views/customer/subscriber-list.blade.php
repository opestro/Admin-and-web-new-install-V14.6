@extends('layouts.back-end.app')

@section('title', translate('subscriber_list'))

@section('content')
<div class="content container-fluid">
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/subscribers.png')}}" width="20" alt="">
            {{translate('subscriber_list')}}
            <span class="badge badge-soft-dark radius-50 fz-14 ml-1">{{ $subscription_list->total() }}</span>
        </h2>
    </div>
    <div class="row mt-20">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                placeholder="{{ translate('search_by_email')}}"  aria-label="Search orders" value="{{ request('searchValue') }}">
                            <button type="submit" class="btn btn--primary">{{ translate('search')}}</button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-outline--primary text-nowrap" data-toggle="dropdown">
                        <i class="tio-download-to"></i>
                        {{ translate('export') }}
                        <i class="tio-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a class="dropdown-item" href="{{route('admin.customer.subscriber-list.export',['searchValue'=>request('searchValue')])}}">
                                <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                {{ translate('excel') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="table-responsive">
                    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{ translate('SL')}}</th>
                            <th scope="col">
                                {{ translate('email')}}
                            </th>
                            <th>{{ translate('subscription_date')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscription_list as $key=>$item)
                                <tr>
                                    <td>{{$subscription_list->firstItem()+$key}}</td>
                                    <td>{{$item->email}}</td>
                                    <td>
                                        {{date('d M Y, h:i A',strtotime($item->created_at))}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        {{$subscription_list->links()}}
                    </div>
                </div>
                @if(count($subscription_list)==0)
                    @include('layouts.back-end._empty-state',['text'=>'no_subscriber_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
