<table class="table" id="datatable">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">
            {{translate('product_Name')}} <label class="badge badge-success ml-3 cursor-pointer">{{translate('Asc/Dsc')}}</label>
        </th>
        <th scope="col">
            {{translate('total_Sale')}} <label class="badge badge-success ml-3 cursor-pointer">{{translate('Asc/Dsc')}}</label>
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($products_array as $key=>$data)
        <tr>
            <th scope="row">{{$key+1}}</th>
            <td>{{$data['product_name']}}</td>
            <td>{{$data['qty']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script type="text/javascript">
    'use strict';
    $(document).ready(function () {
        $('input').addClass('form-control');
    });

    var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                className: 'd-none'
            },
            {
                extend: 'excel',
                className: 'd-none'
            },
            {
                extend: 'csv',
                className: 'd-none'
            },
            {
                extend: 'pdf',
                className: 'd-none'
            },
            {
                extend: 'print',
                className: 'd-none'
            },
        ],
        select: {
            style: 'multi',
            selector: 'td:first-child input[type="checkbox"]',
            classMap: {
                checkAll: '#datatableCheckAll',
                counter: '#datatableCounter',
                counterInfo: '#datatableCounterInfo'
            }
        },
        language: {
            zeroRecords: '<div class="text-center p-4">' +
                '<img class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">' +
                '<p class="mb-0">No data to show</p>' +
                '</div>'
        }
    });

    $('#export-copy').click(function () {
        datatable.button('.buttons-copy').trigger()
    });

    $('#export-excel').click(function () {
        datatable.button('.buttons-excel').trigger()
    });

    $('#export-csv').click(function () {
        datatable.button('.buttons-csv').trigger()
    });

    $('#export-pdf').click(function () {
        datatable.button('.buttons-pdf').trigger()
    });

    $('#export-print').click(function () {
        datatable.button('.buttons-print').trigger()
    });

    $('.js-datatable-filter').on('change', function () {
        var $this = $(this),
            elVal = $this.val(),
            targetColumnIndex = $this.data('target-column-index');

        datatable.column(targetColumnIndex).search(elVal).draw();
    });

    $('#datatableSearch').on('search', function () {
        datatable.search('').draw();
    });
</script>
