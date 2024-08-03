@extends('layouts.blank')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>{{ "6valley Software Installation" }}</h2>
        <h6 class="fw-normal">
            {{ "Please proceed step by step with proper data according to instructions" }}
        </h6>
    </div>

    <div class="pb-2">
        <div class="progress cursor-pointer" role="progressbar" aria-label="6valley Software Installation"
             aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip"
             data-bs-placement="top" data-bs-custom-class="custom-progress-tooltip" data-bs-title="Fourth Step!"
             data-bs-delay='{"hide":1000}'>
            <div class="progress-bar width-80"></div>
        </div>
    </div>

    <div class="card mt-4 position-relative">
        <div class="d-flex justify-content-end mb-2 position-absolute top-end">
            <a href="#" class="d-flex align-items-center gap-1">
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                      data-bs-title="Click on the section to automatically import database">
                    <img src="{{ dynamicAsset(path: 'public/assets/installation/assets/img/svg-icons/info.svg') }}" alt=""
                         class="svg">
                </span>
            </a>
        </div>
        <div class="p-4 mb-md-3 mx-xl-4 px-md-5">
            <div class="d-flex align-items-center column-gap-3 flex-wrap">
                <h5 class="fw-bold fs text-uppercase">{{ "Step 4." }}</h5>
                <h5 class="fw-normal">{{ "Import Database" }}</h5>
            </div>
            <p class="mb-5">
                {{ "Your Database has been connected ! Just click on the section to automatically import database" }}
            </p>

            @if(session()->has('error'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            {{ "Your database is not clean, do you want to clean database then import ?" }}
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('force-import-sql') }}" class="btn btn-danger px-sm-5 action-installation-show-loader">
                        {{ "Force Import Database" }}
                    </a>
                </div>
            @else
                <div class="text-center">
                    <a href="{{ route('import_sql') }}" class="btn btn-dark px-sm-5 action-installation-show-loader">
                        {{ "Click Here" }}
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
