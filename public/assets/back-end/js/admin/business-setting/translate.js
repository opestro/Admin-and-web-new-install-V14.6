'use strict';
$(document).ready(function () {

    let getDataTable = $('#get-data-table-route-and-text');
    let dataTablePageLength = [getDataTable.data('page-length'), 10, 20, 50, 100];
    dataTablePageLength.sort(function(a, b) {
        return a - b;
    });
    let uniquePageLengths = dataTablePageLength.filter(function(value, index, self) {
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
                "data":null,
                className: "text-center",
                render: function (data, type, full, meta) {
                    return `<input class="form-control w-100" id="value-${meta.row + 1}" value="`+data.value+`">`;
                },
            },
            {
                "data":null,
                className: "text-center",
                render: function (data, type, full, meta) {
                    return `<button type="button"  class="btn btn-ghost-success btn-block autoTranslate" data-key="${data.encode}" data-index="${meta.row + 1}">
                                        <i class="tio-globe"></i></button>`;
                },
            },
            {
                "data":null,
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
    $('.autoTranslate').on('click', function (){
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
                $('#value-'+currentElement.data('index')).val(response.translated_data);
            },
            complete: function () {
                $('#loading').fadeOut();
            },
        });
    })

    $('.update-lang').on('click',function (){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        const translate = $('#get-translate-route-and-text');
        const value = $('#value-'+$(this).data('index')).val()
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
