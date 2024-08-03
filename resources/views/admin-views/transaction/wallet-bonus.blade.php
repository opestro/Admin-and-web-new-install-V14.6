@extends('layouts.back-end.app')

@section('title', translate('order_Transactions'))

@section('content')
    <div class="content container-fluid ">
        <!-- Page Title -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/order_report.png')}}" alt="">
                {{translate('transaction_report')}}
            </h2>

            <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder">
                {{translate('how_it_works')}}
                <div class="ripple-animation" data-toggle="modal" data-target="#howItWorksModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z" fill="currentColor"></path>
                    </svg>
                </div>
            </div>

            <div class="modal fade" id="howItWorksModal" tabindex="-1" aria-labelledby="howItWorksModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                            <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                        </div>
                        <div class="modal-body px-4 px-sm-5 pt-0 text-center">
                            <div class="d-flex flex-column align-items-center gap-2">
                                <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/para.png')}}" loading="lazy" alt="">
                                <h4 class="lh-md">Wallet bonus is only applicable when a customer add fund to wallet via outside payment gateway !</h4>
                                <p>Customer will get extra amount to his / her wallet additionally with the amount he / she added from other payment gateways. The bonus amount will be deduct from admin wallet & will consider as admin expense</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.report.transaction-report-inline-menu')
        <!-- End Inlile Menu -->

        <div class="card">
            <div class="card-body">
                <form action="#" id="form-data" method="GET">
                    <div class="row gx-2">
                        <div class="col-12">
                            <h5 class="text-capitalize mb-2">{{translate('search_data')}}</h5>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <select name="all_stores" class="form-control">
                                    <option value="0" selected>{{translate('all_stores')}}</option>
                                    <option value="1">{{translate('stores_one')}}</option>
                                    <option value="2">{{translate('stores_two')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <select name="all_customers" class="form-control">
                                    <option value="0" selected>{{translate('all_customers')}}</option>
                                    <option value="1">{{translate('customer_one')}}</option>
                                    <option value="2">{{translate('customer_two')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <select name="custom_date" class="form-control">
                                    <option value="0" selected>{{translate('custom_date')}}</option>
                                    <option value="1">{{translate('this_month')}}</option>
                                    <option value="2">{{translate('this_year')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="start_date" class="title-color text-capitalize d-flex">{{translate('start_date')}}</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="end_date" class="title-color text-capitalize d-flex">{{translate('end_date')}}</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-end">
                                <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-5">{{translate('filter')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <h4 class="mt-4 text-capitalize d-flex">{{translate('total_transactions')}}</h4>
        <div class="card mt-2">
            <div class="px-3 py-4">
                <div class="row align-items-center">
                    <div class="col-lg-4">
                        <!-- Search -->
                        <form action="#" method="GET">
                            <div class="input-group input-group-custom input-group-merge">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input type="search" name="search" class="form-control" placeholder="{{translate('search_product_name')}}" required>
                                <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                            </div>
                        </form>
                        <!-- End Search -->
                    </div>
                    <div class="col-lg-8 mt-3 mt-lg-0 d-flex flex-wrap gap-3 justify-content-lg-end">
                        <div>
                            <select name="expense_type" class="form-control">
                                <option value="0" selected>{{translate('expense_type')}}</option>
                                <option value="1">{{translate('fixed')}}</option>
                                <option value="2">{{translate('recurring')}}</option>
                            </select>
                        </div>

                        <a href="#" class="btn btn-outline--primary">
                            <i class="tio-file-outlined"></i>
                            <span class="text">{{translate('download_PDF')}}</span>
                        </a>
                        <div>
                            <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{translate('export')}}
                                <i class="tio-chevron-down"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a class="dropdown-item" href="#">{{translate('excel')}}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('transaction_ID')}}</th>
                            <th>{{translate('transaction_date')}}</th>
                            <th>{{translate('order_ID')}}</th>
                            <th>{{translate('expense_amount')}}</th>
                            <th>{{translate('expense_type')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>21423355</td>
                            <td>15 May 2020 9:30 am</td>
                            <td>100234</td>
                            <td>$ 687.93</td>
                            <td>Free Delivery</td>
                            <td>
                                <div class="d-flex gap-10 justify-content-center">
                                    <a class="btn btn-outline-success square-btn btn-sm" target="_blank" title="Transactions" href="#">
                                        <i class="tio-download-to"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>21423355</td>
                            <td>15 May 2020 9:30 am</td>
                            <td>100234</td>
                            <td>$ 687.93</td>
                            <td>Free Delivery</td>
                            <td>
                                <div class="d-flex gap-10 justify-content-center">
                                    <a class="btn btn-outline-success square-btn btn-sm" target="_blank" title="Transactions" href="#">
                                        <i class="tio-download-to"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>21423355</td>
                            <td>15 May 2020 9:30 am</td>
                            <td>100234</td>
                            <td>$ 687.93</td>
                            <td>Free Delivery</td>
                            <td>
                                <div class="d-flex gap-10 justify-content-center">
                                    <a class="btn btn-outline-success square-btn btn-sm" target="_blank" title="Transactions" href="#">
                                        <i class="tio-download-to"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>21423355</td>
                            <td>15 May 2020 9:30 am</td>
                            <td>100234</td>
                            <td>$ 687.93</td>
                            <td>Free Delivery</td>
                            <td>
                                <div class="d-flex gap-10 justify-content-center">
                                    <a class="btn btn-outline-success square-btn btn-sm" target="_blank" title="Transactions" href="#">
                                        <i class="tio-download-to"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>21423355</td>
                            <td>15 May 2020 9:30 am</td>
                            <td>100234</td>
                            <td>$ 687.93</td>
                            <td>Free Delivery</td>
                            <td>
                                <div class="d-flex gap-10 justify-content-center">
                                    <a class="btn btn-outline-success square-btn btn-sm" target="_blank" title="Transactions" href="#">
                                        <i class="tio-download-to"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
