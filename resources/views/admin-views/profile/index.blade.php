@extends('layouts.back-end.app')

@section('title', translate('profile'))

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard.index')}}">{{translate('dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{translate('my_profile')}}</li>
        </ol>
    </nav>
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h3 class="h3 mb-0 text-black-50">{{translate('my_profile')}}  </h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <img  src="{{ getValidImage(path: 'storage/app/public/admin/'.$admin->image, type: 'backend-basic') }}"
                          class="rounded-circle border"
                         height="200" width="200" alt="">
                    <div class="p-4">
                    <h4>{{translate('name')}} : {{$admin->name}}</h4>
                    <h6>{{translate('phone')}} : {{$admin->phone}}</h6>
                    <h6>{{translate('email')}} : {{$admin->email}}</h6>
                    <a class="btn btn-success" href="{{route('admin.profile.update',[$admin->id])}}">{{translate('edit')}}</a>
                </div>

                </div>
            </div>
        </div>
    </div>

@endsection
