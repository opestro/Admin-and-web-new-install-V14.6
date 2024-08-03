@extends('layouts.back-end.app')

@section('title', translate('update_Notification'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/push_notification.png')}}" alt="">
                {{translate('push_notification_update')}}
            </h2>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.notification.update',[$notification['id']])}}" method="post" class="text-start"
                        enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                                <input type="text" value="{{$notification['title']}}" name="title" class="form-control"
                                        placeholder="{{translate('new_notification')}}" required>
                            </div>
                            <div class="form-group mb-0">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('description')}}</label>
                                <textarea name="description" class="form-control"
                                            required>{{$notification['description']}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-center">
                                <img class="upload-img-view mt-4" id="viewer"
                                     src="{{ getValidImage(path: 'storage/app/public/notification/'.$notification['image']?? '', type: 'backend-basic') }}"
                                        alt="{{translate('image')}}"/>
                            </div>
                            <label class="title-color">{{translate('image')}}</label>
                            <span class="text-info"> ( {{translate('ratio').'1:1'}})</span>
                            <div class="custom-file">
                                <input type="file" name="image"  class="custom-file-input image-input" data-image-id="viewer"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileEg1">{{translate('choose_File')}}</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
