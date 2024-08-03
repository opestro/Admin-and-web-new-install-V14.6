@extends('layouts.back-end.app')

@section('title', translate('edit_most_demanded'))

@section('content')
    <div class="content container-fluid">

        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 text-capitalize">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/most-demanded.png') }}" alt="">
                {{ translate('edit_most_demanded') }}
            </h2>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.most-demanded.update', ['id'=>$mostDemandedProduct->id]) }}"
                              method="post" class="text-start"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-2">
                                        <label for="name" class="title-color font-weight-medium">
                                            {{ translate('products') }}
                                        </label>
                                        <select
                                            class="js-example-basic-multiple js-states js-example-responsive form-control"
                                            name="product_id">
                                            <option value="" disabled selected>
                                                {{ translate('select_Product') }}
                                            </option>
                                            @foreach ($products as $key => $product)
                                                <option
                                                    value="{{ $product->id }}"{{ $mostDemandedProduct->product_id == $product->id ?'selected':''}}>
                                                    {{ $product['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group md-2">
                                        <label for="name" class="title-color font-weight-medium">
                                            {{ translate('banner') }}
                                        </label>
                                        <span class="text-info ml-1">
                                            ( {{ translate('ratio') }} {{ '5:1' }} )
                                        </span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="banner-image"
                                                   class="custom-file-input image-preview-before-upload"
                                                   data-preview="#viewer"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label text-capitalize" for="banner-image">
                                                {{ translate('choose_File') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="text-center mt-lg-3">
                                            <img class="border radius-10 ratio-4:1 max-w-655px w-100"
                                                 id="viewer"
                                                 src="{{ getValidImage(path:'storage/app/public/most-demanded/'.$mostDemandedProduct['banner'],type: 'backend-basic')}}"
                                                 alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" id="reset" class="btn btn-secondary px-4">
                                    {{ translate('reset') }}
                                </button>
                                <button type="submit" class="btn btn--primary px-4">
                                    {{ translate('update') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
