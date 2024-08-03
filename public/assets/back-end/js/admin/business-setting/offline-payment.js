'use strict';

function removeInputFieldsGroup(){
    $('.remove-input-fields-group').on('click',function (){
        $('#'+$(this).data('id')).remove();
    })
}
removeInputFieldsGroup()
let counter = 1;
$('#add-input-fields-group').on('click',function (){
    let getAddInputText = $('#get-add-input-field-text');
    let id = Math.floor((Math.random() + 1 )* 9999);
    let newField = `<div class="row align-items-end" id="`+id+`">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input_name" class="title_color">${getAddInputText.data('input-field-name')}</label>
                                    <input type="text" name="input_name[]" class="form-control" placeholder="${getAddInputText.data('input-field-name-placeholder')}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input_data" class="title_color">${getAddInputText.data('input-data')}</label>
                                    <input type="text" name="input_data[]" class="form-control" placeholder="${getAddInputText.data('input-data-placeholder')}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="d-flex justify-content-end">
                                        <a href="javascript:" class="btn btn-outline-danger btn-sm delete square-btn remove-input-fields-group" title="${getAddInputText.data('delete-text')}" data-id="${id}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>`;

    $('#input-fields-section').append(newField);
    $('#'+id).fadeIn();
    removeInputFieldsGroup()
})

$('#add-customer-input-fields-group').on('click',function (){
    let id = Math.floor((Math.random() + 1 )* 9999);
    let getCustomerAddInputText = $('#get-add-customer-input-field-text');
    if(counter < 100) {
        $('#customer-input-fields-section').append(
            `<div class="row align-items-end" id="`+id+`">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="title_color">${getCustomerAddInputText.data('input-field-name')}</label>
                            <input type="text" name="customer_input[]" class="form-control" placeholder="${getCustomerAddInputText.data('input-field-name-placeholder')}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customer_placeholder" class="title_color">${getCustomerAddInputText.data('input-placeholder')}</label>
                            <input type="text" name="customer_placeholder[]" class="form-control" placeholder="${getCustomerAddInputText.data('input-placeholder-placeholder')}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="d-flex justify-content-between gap-2">
                                <div class="form-check text-start mb-3">

                                    <label class="form-check-label text-dark" for="`+id+1+`">
                                        <input type="checkbox" class="form-check-input" id="`+id+1+`" name="is_required[${counter}]"> ${getCustomerAddInputText.data('require-text')}
                                    </label>
                                </div>

                                <a class="btn btn-outline-danger btn-sm delete square-btn remove-input-fields-group" title="${getCustomerAddInputText.data('delete-text')}"  data-id="${id}">
                                    <i class="tio-delete"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>`
        );
        counter++;
    }
    $('#'+id).fadeIn();
    removeInputFieldsGroup()
})
$('#payment-method-offline').on('submit', function(event){
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: $(this).serialize(),
        success: function (data) {
            if(parseInt(data.status) === 1) {
                toastr.success(data.message);
                location.href = data.redirect_url;
            }else {
                toastr.error(data.message);
            }
        }
    });
});

$('.method-status-form').on('submit', function(event){
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: $(this).serialize(),
        success: function (data) {
            if(parseInt(data.success_status) === 1) {
                toastr.success(data.message);
            }else if(parseInt(data.success_status) === 0) {
                toastr.error(data.message);
            }
            setTimeout(function(){
                location.reload();
            }, 1000);
        }
    });
});
