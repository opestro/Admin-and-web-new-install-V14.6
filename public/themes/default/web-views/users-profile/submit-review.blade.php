@extends('layouts.front-end.app')

@section('title',translate('submit_a_review'))

@section('content')

<div class="container pb-5 mb-2 mb-md-4 mt-2 rtl text-align-direction">
    <div class="row g-3">
    @include('web-views.partials._profile-aside')
        <section class="col-lg-9 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="__ml-20">{{translate('submit_a_review')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('review.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="exampleInputEmail1">{{translate('rating')}}</label>
                                <select class="form-control" name="rating">
                                    <option value="1">{{translate('1')}}</option>
                                    <option value="2">{{translate('2')}}</option>
                                    <option value="3">{{translate('3')}}</option>
                                    <option value="4">{{translate('4')}}</option>
                                    <option value="5">{{translate('5')}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">{{translate('comment')}}</label>
                                <input name="product_id" value="{{$order_details->product_id}}" hidden>
                                <input name="order_id" value="{{$order_details->order_id}}" hidden>
                                <textarea class="form-control" name="comment"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">{{translate('attachment')}}</label>
                                <div class="row coba"></div>
                                <div class="mt-1 text-info">{{translate('file_type')}}: jpg, jpeg, png. {{ translate('maximum size')}}: 2MB</div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{translate('back')}}</a>

                            <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

</div>
@endsection

@push('script')
    <script src="{{theme_asset(path: 'public/assets/front-end/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $(".coba").spartanMultiImagePicker({
                fieldName: 'fileUpload[]',
                maxCount: 5,
                rowHeight: '150px',
                groupClassName: 'col-md-4',
                placeholderImage: {
                    image: '{{theme_asset(path: 'public/assets/front-end/img/image-place-holder.png')}}',
                    width: '100%'
                },
                dropFileLabel: "{{translate('drop_here')}}",
                onAddRow: function (index, file) {
                },
                onRenderedPreview: function (index) {
                },
                onRemoveRow: function (index) {
                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{translate('input_png_or_jpg')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{translate('file_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
