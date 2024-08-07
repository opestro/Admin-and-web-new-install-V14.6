@extends('layouts.back-end.app')

@section('title', translate('notification_setup'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('notification_setup')}}
            </h2>
        </div>
        @include('admin-views.notification-setup.partials.inline-menu')
        <div class="card">
            <div class="card-header">
                <div class="w-100">
                    <div class="row g-2 align-items-center flex-grow-1">
                        <div class="col-md-4">
                            <h3 class="text-capitalize d-flex gap-1 m-0">
                                {{translate('Notifications')}}
                            </h3>
                        </div>
                        <div class="col-md-8 d-flex gap-3 flex-wrap flex-sm-nowrap justify-content-md-end">
                            <form action="" method="GET">
                                <div class="input-group input-group-custom input-group-merge">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch_" type="search" name="searchValue" class="form-control" placeholder="{{translate('Search by topics name')}}">
                                    <button type="submit" class="btn btn--primary input-group-text">{{translate('search')}}</button>
                                </div>
                            </form>
                            <div class="dropdown">
                                <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a type="submit" class="dropdown-item d-flex align-items-center gap-2" href="">
                                            <img width="14" src="{{asset('public/assets/back-end/img/excel.png')}}" alt="">
                                            {{translate('excel')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th class="lg-w-43">SL</th>
                                <th class="lg-w-160">Topics</th>
                                <th class="lg-w-160">Push Notification</th>
                                <th class="lg-w-160">Mail</th>
                                <th class="lg-w-160">SMS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Order</td>
                                <td>
                                    <form>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                    id="banner-status" value="1" checked
                                                    data-modal-id="toggle-status-modal"
                                                    data-toggle-id="notification-status-"
                                                    data-on-image="banner-status-on.png"
                                                    data-off-image="banner-status-off.png"
                                                    data-on-title="{{ translate('Want_to_Turn_ON') }}"
                                                    data-off-title="{{ translate('Want_to_Turn_OFF') }}"
                                                    data-on-message="<p>{{ translate('if_enabled_this_banner_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                    data-off-message="<p>{{ translate('if_disabled_this_banner_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <form>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                    id="banner-status" value="1" checked
                                                    data-modal-id="toggle-status-modal"
                                                    data-toggle-id="notification-status-"
                                                    data-on-image="banner-status-on.png"
                                                    data-off-image="banner-status-off.png"
                                                    data-on-title="{{ translate('Want_to_Turn_ON') }}"
                                                    data-off-title="{{ translate('Want_to_Turn_OFF') }}"
                                                    data-on-message="<p>{{ translate('if_enabled_this_banner_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                    data-off-message="<p>{{ translate('if_disabled_this_banner_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <form>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                    id="banner-status" value="1" checked
                                                    data-modal-id="toggle-status-modal"
                                                    data-toggle-id="notification-status-"
                                                    data-on-image="banner-status-on.png"
                                                    data-off-image="banner-status-off.png"
                                                    data-on-title="{{ translate('Want_to_Turn_ON') }}"
                                                    data-off-title="{{ translate('Want_to_Turn_OFF') }}"
                                                    data-on-message="<p>{{ translate('if_enabled_this_banner_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                    data-off-message="<p>{{ translate('if_disabled_this_banner_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Terms & Condition Updates</td>
                                <td>
                                    <form>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                    id="banner-status" value="1" checked
                                                    data-modal-id="toggle-status-modal"
                                                    data-toggle-id="notification-status-"
                                                    data-on-image="banner-status-on.png"
                                                    data-off-image="banner-status-off.png"
                                                    data-on-title="{{ translate('Want_to_Turn_ON') }}"
                                                    data-off-title="{{ translate('Want_to_Turn_OFF') }}"
                                                    data-on-message="<p>{{ translate('if_enabled_this_banner_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                    data-off-message="<p>{{ translate('if_disabled_this_banner_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <form>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                    id="banner-status" value="1"
                                                    data-modal-id="toggle-status-modal"
                                                    data-toggle-id="notification-status-"
                                                    data-on-image="banner-status-on.png"
                                                    data-off-image="banner-status-off.png"
                                                    data-on-title="{{ translate('Want_to_Turn_ON') }}"
                                                    data-off-title="{{ translate('Want_to_Turn_OFF') }}"
                                                    data-on-message="<p>{{ translate('if_enabled_this_banner_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                    data-off-message="<p>{{ translate('if_disabled_this_banner_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <form>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                    id="banner-status" value="1"
                                                    data-modal-id="toggle-status-modal"
                                                    data-toggle-id="notification-status-"
                                                    data-on-image="banner-status-on.png"
                                                    data-off-image="banner-status-off.png"
                                                    data-on-title="{{ translate('Want_to_Turn_ON') }}"
                                                    data-off-title="{{ translate('Want_to_Turn_OFF') }}"
                                                    data-on-message="<p>{{ translate('if_enabled_this_banner_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                    data-off-message="<p>{{ translate('if_disabled_this_banner_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
