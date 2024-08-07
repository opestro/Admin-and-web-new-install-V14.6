@extends('layouts.back-end.app-seller')
@section('title',translate('emergency_Contact'))
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-delivery-man.png')}}" alt="">
                {{translate('emergency_Contact')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="{{route('vendor.delivery-man.emergency-contact.index')}}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                                <i class="tio-user"></i>
                                {{translate('add_new_contact_information')}}
                            </h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="title-color d-flex"
                                               for="f_name">{{translate('contact_name')}}</label>
                                        <input type="text" name="name" class="form-control"
                                               placeholder="{{translate('contact_name')}}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="title-color d-flex" for="exampleFormControlInput1">{{translate('phone')}}</label>
                                        <div class="input-group mb-3">
                                            <div>
                                                <select class="js-example-basic-multiple js-states js-example-responsive form-control " name="country_code" required>
                                                    @foreach (TELEPHONE_CODES as $code)
                                                        <option value="{{ $code['code'] }}" {{old($code['code']) == $code['code']? 'selected' : ''}}>{{ $code['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input value="{{old('phone')}}" type="text" name="phone" class="form-control" placeholder="{{translate('ex').':'.'017********'}}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-3 justify-content-end">
                                <button type="reset" id="reset"
                                        class="btn btn-secondary px-4">{{translate('reset')}}</button>
                                <button type="submit"
                                        class="btn btn--primary px-4">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card mt-3">
                    <div class="p-3">
                        <div class="row gy-1 align-items-center justify-content-between">
                            <div class="col-auto">
                                <h5>
                                    {{translate('contact_information_Table')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $contacts->count() }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-left">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th class="text-center">{{translate('name')}}</th>
                                <th class="text-center">{{translate('phone')}}</th>
                                <th class="text-center">{{translate('status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($contacts as $contact)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td class="text-center text-capitalize">{{ $contact['name'] }}</td>
                                    <td class="text-center"><a class="title-color hover-c1" href="tel:{{$contact['country_code'].$contact['phone']}}">{{$contact['country_code'].$contact['phone']}}</a></td>
                                    <td>
                                        <form action="{{route('vendor.delivery-man.emergency-contact.index')}}" method="post" id="contact_status{{$contact['id']}}-form" class="contact_status_form">
                                            @csrf @method('patch')
                                            <input hidden name="id" value="{{$contact['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input toggle-switch-message" id="contact_status{{$contact['id']}}" name="status" value="1" {{ $contact->status == 1 ? 'checked':'' }}
                                                    data-modal-id = "toggle-status-modal"
                                                    data-toggle-id = "contact_status{{$contact['id']}}"
                                                    data-on-image = ""
                                                    data-off-image = ""
                                                    data-on-title = "{{translate('are_you_sure').'?'}}"
                                                    data-off-title = "{{translate('are_you_sure').'?'}}"
                                                    data-on-message = "<p>{{translate('want_to_change_status')}}</p>"
                                                    data-off-message = "<p>{{translate('want_to_change_status')}}</p>"
                                                >
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <button  class="btn btn-outline--primary btn-sm emergency-contact-update-view"
                                                     title="{{translate('edit')}}"
                                                     data-action="{{route('vendor.delivery-man.emergency-contact.update',['id'=>$contact->id])}}">
                                                <i class="tio-edit"></i>
                                            </button>
                                            <a class="btn btn-outline-danger btn-sm delete delete-data" href="javascript:"
                                               data-id="delete-contact-{{$contact->id}}"
                                               title="{{ translate('delete')}}">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                        <form action="{{route('vendor.delivery-man.emergency-contact.index')}}"
                                              method="post" id="delete-contact-{{$contact->id}}">
                                            @csrf @method('delete')
                                            <input type="hidden" name="id" value="{{ $contact->id }}">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(count($contacts)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_contact_found'],['image'=>'default'])
                    @endif
                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            {{ $contacts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade emergency-contact-update-modal" tabindex="-1" aria-labelledby="toggle-modal" aria-hidden="true">
    </div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/emergency-contact.js')}}"></script>
@endpush
