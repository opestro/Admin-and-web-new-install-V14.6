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
             aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip"
             data-bs-placement="top" data-bs-custom-class="custom-progress-tooltip" data-bs-title="Third Step!"
             data-bs-delay='{"hide":1000}'>
            <div class="progress-bar width-60"></div>
        </div>
    </div>

    <div class="card mt-4 position-relative">
        <div class="d-flex justify-content-end mb-2 position-absolute top-end">
            <a href="#" class="d-flex align-items-center gap-1">
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                      data-bs-title="Follow our documentation">

                    <img src="{{ dynamicAsset(path: 'public/assets/installation/assets/img/svg-icons/info.svg') }}" alt=""
                         class="svg">
                </span>
            </a>
        </div>
        <div class="p-4 mb-md-3 mx-xl-4 px-md-5">
            <div class="d-flex align-items-center column-gap-3 flex-wrap">
                <h5 class="fw-bold fs text-uppercase">{{ "Step 3." }}</h5>
                <h5 class="fw-normal">{{ "Update Database Information" }}</h5>
            </div>
            <p class="mb-4">
                {{ "Provide your database information." }}
                <a href="https://docs.6amtech.com/docs-six-valley/admin-panel/install-on-server" target="_blank">
                    {{ "Where to get this information ?" }}
                </a>
            </p>

            @if (isset($error) || session()->has('error'))
                <div class="row margin-top-20px">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            {{ "Invalid Database Credentials or Host. Please check your database credentials carefully." }}
                        </div>
                    </div>
                </div>
            @elseif(session()->has('success'))
                <div class="row margin-top-20px">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <strong>{{session('success') }}</strong>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('install.db') }}">
                @csrf
                <div class="bg-light p-4 rounded mb-4">
                    <div class="px-xl-2 pb-sm-3">
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="db_host" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Database Host" }}
                                    </label>
                                    <input type="text" id="db_host" class="form-control" name="DB_HOST"
                                           required
                                           placeholder="Ex: localhost" autocomplete="off">
                                    <input type="hidden" name="types[]" value="DB_HOST">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="db_name" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Database Name" }}
                                    </label>
                                    <input type="text" id="db_name" class="form-control" name="DB_DATABASE"
                                           required
                                           placeholder="Ex: project-name-db" autocomplete="off">
                                    <input type="hidden" name="types[]" value="DB_DATABASE">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="db_user" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Database Username" }}
                                    </label>
                                    <input type="text" id="db_user" class="form-control"
                                           name="DB_USERNAME" required
                                           placeholder="Ex: root" autocomplete="off">
                                    <input type="hidden" name="types[]" value="DB_USERNAME">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="db_pass" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Database Password" }}
                                    </label>
                                    <div class="input-inner-end-ele position-relative">
                                        <input type="password" id="db_pass" min="8" class="form-control"
                                               name="DB_PASSWORD"
                                               required
                                               placeholder="Ex: password" autocomplete="off">
                                        <input type="hidden" name="types[]" value="DB_PASSWORD">
                                        <div class="togglePassword">
                                            <img alt="" class="svg eye"
                                                src="{{ dynamicAsset(path: 'public/assets/installation/assets/img/svg-icons/eye.svg') }}">
                                            <img alt="" class="svg eye-off"
                                                src="{{ dynamicAsset(path: 'public/assets/installation/assets/img/svg-icons/eye-off.svg') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-dark px-sm-5">{{ "Continue" }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
