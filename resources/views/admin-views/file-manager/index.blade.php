@extends('layouts.back-end.app')

@section('title',translate('gallery'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/file-manager.png')}}" width="20" alt="">
                {{translate('file_manager')}}
            </h2>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">{{translate('file_manager')}}</h5>
            <button type="button" class="btn btn-sm btn--primary modalTrigger" data-toggle="modal" data-target="#exampleModal">
                <i class="tio-add"></i>
                <span class="text text-capitalize">{{translate('add_new')}}</span>
            </button>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                            {{translate(end($currentFolder))}}
                            <span class="badge badge-soft-dark radius-50" id="itemCount">{{count($data)}}</span>
                        </h5>
                        @if(end($currentFolder) != 'public')
                            <a class="btn btn--primary btn-sm" href="{{ route('admin.file-manager.index', base64_encode($previousFolder)) }}">
                                <i class="tio-chevron-left"></i>
                                {{translate('back')}}
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($data as $key=>$file)
                                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                                    @if($file['type']=='folder')
                                        <a class="btn p-0"
                                           href="{{route('admin.file-manager.index', base64_encode($file['path']))}}">
                                            <img class="img-thumbnail mb-2"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/folder.png')}}" alt="">
                                            <p class="title-color">{{Str::limit($file['name'],10)}}</p>
                                        </a>
                                    @elseif($file['type']=='file')
                                        <button class="btn p-0 w-100" data-toggle="modal"
                                                data-target="#imagemodal{{$key}}" title="{{$file['name']}}">
                                            <span class="d-flex flex-column justify-content-center gallary-card aspect-1 overflow-hidden border rounded">
                                                <img src="{{dynamicStorage(path: 'storage/app/'.$file['path'])}}"
                                                     alt="{{$file['name']}}" class="h-auto w-100">
                                            </span>
                                            <span class="overflow-hidden pt-2 m-0">{{Str::limit($file['name'],10)}}</span>
                                        </button>
                                        <div class="modal fade" id="imagemodal{{$key}}" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel">{{$file['name']}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal"><span
                                                                aria-hidden="true">&times;</span><span
                                                                class="sr-only">{{translate('close')}}</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img src="{{dynamicStorage(path: 'storage/app/'.$file['path'])}}"
                                                             class="w-100 h-auto" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a class="btn btn--primary"
                                                           href="{{route('admin.file-manager.download', base64_encode($file['path']))}}"><i
                                                                class="tio-download"></i> {{translate('download')}}
                                                        </a>

                                                        <button class="btn btn-info copy-path"
                                                                data-path="{{ $file['db_path'] }}"><i
                                                                class="tio-copy"></i> {{translate('copy_path')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="indicator"></div>
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize"
                            id="exampleModalLabel">{{translate('upload_file')}} </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin.file-manager.image-upload')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="path" value="{{base64_decode($folderPath)}}" hidden>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" name="images[]" id="customFileUpload" class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" multiple>
                                    <label class="custom-file-label text-capitalize"
                                           for="customFileUpload">{{translate('choose_images')}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" name="file" id="customZipFileUpload" class="custom-file-input"
                                           accept=".zip">
                                    <label class="custom-file-label" id="zipFileLabel"
                                           for="customZipFileUpload">{{translate('upload_zip_file')}}</label>
                                </div>
                            </div>

                            <div class="row" id="files"></div>
                            <div class="form-group">
                                <input class="btn btn--primary {{env('APP_MODE') != 'demo'?'':'call-demo'}}" type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                       value="{{translate('upload')}}">
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="get-file-copy-success-message" data-success="{{translate('file_path_copied_successfully')}}" ></span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/file-manager.js')}}"></script>
@endpush
