'use strict';
$(document).ready(function () {

    let getDataTable = $('#get-data-table-route-and-text');
    let dataTablePageLength = [getDataTable.data('page-length'), 10, 20, 50, 100];
    dataTablePageLength.sort(function (a, b) {
        return a - b;
    });
    let uniquePageLengths = dataTablePageLength.filter(function (value, index, self) {
        return self.indexOf(value) === index;
    });

    let dataTable = $('#dataTable').DataTable({
        "pageLength": getDataTable.data('page-length'),
        lengthMenu: uniquePageLengths,
        ajax: {
            type: "get",
            url: getDataTable.data('route'),
            dataSrc: ''
        },
        language: {
            info: getDataTable.data('info'),
            infoEmpty: getDataTable.data('info-empty'),
            infoFiltered: getDataTable.data('info-filtered'),
            emptyTable: getDataTable.data('empty-table'),
            search: getDataTable.data('search'),
            lengthMenu: getDataTable.data('length-menu'),
            paginate: {
                first: getDataTable.data('paginate-first'),
                last: getDataTable.data('paginate-last'),
                next: getDataTable.data('paginate-next'),
                previous: getDataTable.data('paginate-previous')
            },
        },
        columns: [{
            data: null,
            className: "text-center",
            render: function (data, type, full, meta) {
                return meta.row + 1;
            }
        },
            {
                className: "text-center break-all",
                data: 'key'
            },
            {
                "data": null,
                className: "text-center",
                render: function (data, type, full, meta) {
                    return `<input class="form-control w-100" id="value-${meta.row + 1}" value="` + data.value + `">`;
                },
            },
            {
                "data": null,
                className: "text-center",
                render: function (data, type, full, meta) {
                    return `<button type="button"  class="btn btn-ghost-success btn-block autoTranslate" data-key="${data.encode}" data-index="${meta.row + 1}">
                                        <i class="tio-globe"></i></button>`;
                },
            },
            {
                "data": null,
                className: "text-center",
                render: function (data, type, full, meta) {
                    return `<button type="button" class="btn btn--primary btn-block update-lang" data-key="${data.encode}" data-index="${meta.row + 1}">
                                        <i class="tio-save-outlined"></i>
                                    </button>`;
                },
            },
        ],
    });

    dataTable.on('draw.dt', function () {
        autoTranslateFunctionality();
    });

    dataTable.on('xhr.dt', function () {
        autoTranslateFunctionality();
    });

    dataTable.on('search.dt', function () {
        autoTranslateFunctionality();
    });
});


function autoTranslateFunctionality() {
    $('.autoTranslate').on('click', function () {
        let currentElement = $(this);
        let autoTranslate = $('#get-auto-translate-route-and-text');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: autoTranslate.data('route'),
            method: 'POST',
            data: {
                key: $(this).data('key')
            },
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function (response) {
                toastr.success(autoTranslate.data('success-text'));
                $('#value-' + currentElement.data('index')).val(response.translated_data);
            },
            complete: function () {
                $('#loading').fadeOut();
            },
        });
    })

    $('.update-lang').on('click', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        const translate = $('#get-translate-route-and-text');
        const value = $('#value-' + $(this).data('index')).val()
        $.ajax({
            url: translate.data('route'),
            method: 'POST',
            data: {
                key: $(this).data('key'),
                value: value
            },
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function () {
                toastr.success(translate.data('success-text'));
            },
            complete: function () {
                $('#loading').fadeOut();
            },
        });
    })
}

autoTranslateFunctionality()
let totalMessagesOfCurrentLanguageElement = $('#total-messages-of-current-language');
var needToTranslateCall = parseInt(totalMessagesOfCurrentLanguageElement.data('total')) / totalMessagesOfCurrentLanguageElement.data('message-group');
var translateInitValue = 0;
var translateInitSpeedValue = 1000;
$('#translating-modal-start').on('click', function () {
    $('.translating-modal-success-rate').html('0%');
    $('.translating-modal-success-bar').attr('style', 'width:0%');
    autoTranslationFunction()
})

function autoTranslationFunction() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: $('#get-auto-translate-all-route-and-text').data('route'),
        method: 'GET',
        beforeSend: function () {
            $('#translating-modal').modal('show')
        },
        success: function (response) {
            if (response.due_message != 0) {
                translateInitValue += Math.round((100 / needToTranslateCall) * 100) / 100;
                translateInitValue = translateInitValue > 100 ? 100 : translateInitValue;
                $('.translating-modal-success-bar').attr('style', 'width:' + translateInitValue + '%');
                $('.translating-modal-success-rate').html(parseFloat(translateInitValue.toFixed(2)) + '%');
                autoTranslationFunction()
            } else {
                toastr.success(response.message);
                $('.translateCountSuccess').html(response.translate_success_message);
                translateInitSpeedValue = 10;
                translatingModalSuccessRate(translateInitSpeedValue);
                translateInitSpeedValue = 1000;
                setTimeout(() => {
                    $('#translating-modal').modal('hide');
                    setTimeout(() => {
                        $('#complete-modal').modal('show');
                    }, 500)
                }, 2000)
            }
        },
        complete: function () {

        },
        error: function (xhr, ajaxOption, thrownError) {
        },
    });
}

function translatingModalSuccessRate(SpeedValue) {
    const translatingRateInterval = setInterval(() => {
        if (translateInitValue < 100) {
            $('.translating-modal-success-rate').html(translateInitValue + '%');
            $('.translating-modal-success-bar').attr('style', 'width:' + translateInitValue + '%');
            translateInitValue++;
        }
    }, SpeedValue)
    if (SpeedValue !== translateInitSpeedValue) {
        clearInterval(translatingRateInterval)
    }
}
