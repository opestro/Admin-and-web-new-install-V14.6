@extends('layouts.back-end.app')
@section('title', translate('inhouse_product_sale Report'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/inhouse_sale.png')}}" alt="">
                {{translate('inhouse_sale')}}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <form class="w-100" method="GET" action="{{ url()->current() }}">
                            <div class="row gy-2 align-items-center">
                                <div class="col-sm-9">
                                    <div class="d-flex align-items-center gap-10">
                                        <label for="exampleInputEmail1"
                                               class="title-color mb-0">{{translate('category')}}</label>
                                        <select
                                            class="js-select2-custom form-control"
                                            name="category_id">
                                            <option value="all">{{translate('all')}}</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category['id']}}" {{request('category_id')==$category['id']? 'selected': ''}}>
                                                    {{$category['name']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn--primary btn-block">
                                        {{translate('filter')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table
                            class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}} </th>
                                <th>
                                    {{translate('product_Name')}}
                                </th>
                                <th class="text-center">
                                    {{translate('total_Sale')}}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key=> $data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data['name']}}</td>
                                    <td class="text-center">{{$data->orderDelivered->sum('qty')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {!! $products->links() !!}
                        </div>
                    </div>
                    @if(count($products) <= 0)
                        @include('layouts.back-end._empty-state',['text'=>'no_product_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
