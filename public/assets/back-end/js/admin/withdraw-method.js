'use strict';
let counter;
function removeField(){
    $('.remove-field').on('click',function (){
        $( `#field-row--${$(this).data('key')}` ).remove();
        counter--;
    })
}
removeField();
jQuery(document).ready(function ($) {
    counter = 1;
    $('#add-more-field').on('click', function (event) {
        let getFiledText = $('#get-add-filed-text');
        if(counter < 15) {
            event.preventDefault();
            let html =
                `<div class="card card-body mt-3" id="field-row--${counter}">
                            <div class="row gy-4 align-items-center">
                                <div class="col-md-6 col-12">
                                    <select class="form-control js-select" name="field_type[]" required>
                                        <option value="" selected disabled>${getFiledText.data('input-filed')+' '+'*'}</option>
                                        <option value="string">${getFiledText.data('string')}</option>
                                        <option value="number">${getFiledText.data('number')}</option>
                                        <option value="date">${getFiledText.data('date')}</option>
                                        <option value="password">${getFiledText.data('password')}</option>
                                        <option value="email">${getFiledText.data('email')}</option>
                                        <option value="phone">${getFiledText.data('phone')}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="field_name[]"
                                               placeholder="Select field name" value="" required>
                                        <label>${getFiledText.data('field-name')+' '+'*'}</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="placeholder_text[]"
                                               placeholder="Select placeholder text" value="" required>
                                        <label>${getFiledText.data('placeholder-text')+' '+'*'}</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="is_required[${counter}]" id="flex-check-default--${counter}" checked>
                                        <label class="form-check-label" for="flex-check-default--${counter}">
                                            ${getFiledText.data('required')}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex justify-content-end">
                                    <span class="btn btn-danger remove-field" data-key="${counter}">
                                    <i class="tio-delete"></i>
                                        ${getFiledText.data('remove')}
                                    </span>
                                </div>
                            </div>
                        </div>`;
            $('#custom-field-section').append(html)
            $(".js-select").select2();
            removeField();
            counter++;
        } else {
            Swal.fire({
                title: getFiledText.data('reached-maximum'),
                confirmButtonText: getFiledText.data('confirm'),
            });
        }
    })
    $('form').on('reset', function () {
        if(counter > 1) {
            $('#custom-field-section').html("");
            $('#method_name').val("");
        }
        counter = 1;
    })
});
