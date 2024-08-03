"use strict";

$('.product-list-filter-on-viewpage').on('change', function (){
    let value = $(this).val();
    let data = $('#products-search-data-backup');
    $.get({
        url: data.data('url'),
        data: {
            id: data.data('id'),
            name: data.data('name'),
            data_from: data.data('from'),
            min_price: data.data('min-price'),
            max_price: data.data('max-price'),
            sort_by: value
        },
        dataType: 'json',
        beforeSend: function () {
            $('#loading').show();
        },
        success: function (response) {
            $('#ajax-products').html(response.view);
            $(".view-page-item-count").html(response.total_product);
            renderQuickViewFunction()
        },
        complete: function () {
            $('#loading').hide();
        },
    });
})

$('.action-search-products-by-price').on('click', function (){
    searchByPrice();
})

function searchByPrice() {
    let min = $('#min_price').val();
    let max = $('#max_price').val();
    let data = $('#products-search-data-backup');
    $.get({
        url: data.data('url'),
        data: {
            id: data.data('id'),
            name: data.data('name'),
            data_from: data.data('from'),
            sort_by: data.data('sort'),
            min_price: min,
            max_price: max,
        },
        dataType: 'json',
        beforeSend: function () {
            $('#loading').show();
        },
        success: function (response) {
            $('#ajax-products').html(response.view);
            $(".view-page-item-count").html(response.total_product);
            $('#paginator-ajax').html(response.paginator);
            $('#price-filter-count').text(response.total_product + data.data('message'))
            renderQuickViewFunction()
        },
        complete: function () {
            $('#loading').hide();
        },
    });
}

$('#searchByFilterValue, #searchByFilterValue-m').change(function () {
    var url = $(this).val();
    if (url) {
        window.location = url;
    }
    return false;
});

$("#search-brand").on("keyup", function () {
    let value = this.value.toLowerCase().trim();
    $("#lista1 div>li").show().filter(function () {
        return $(this).text().toLowerCase().trim().indexOf(value) == -1;
    }).hide();
});
