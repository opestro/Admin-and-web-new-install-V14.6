'use strict';
$('.search-product').on('keyup',function (){
    let name = $(this).val();
    if (name.length > 0) {
        $.get($('#get-search-product-route').data('action'), {searchValue: name}, (response) => {
            $('.search-result-box').empty().html(response.result);
        })
    }
})
let selectProductSearch = $('.select-product-search');
let productIdsArray = [];
selectProductSearch.on('click', '.select-product-item', function () {
    let productId = $(this).find('.product-id').text();
    if(productIdsArray.indexOf(productId)){
        productIdsArray.push(productId);
        getProductDetails(productIdsArray);
    }


})
function removeSelectedProduct(){
    $('.remove-selected-product').on('click', function () {
        productIdsArray.splice(productIdsArray.indexOf($(this).data('product-id')));
        $(this).closest('.select-product-item').remove();
    });
}
$('.reset-selected-products').on('click',function (){
    productIdsArray = [];
    $('#selected-products').empty();
})

function getProductDetails(productIds){
    $.ajax({
        url: $('#get-multiple-product-details-route').data('action'),
        type: 'GET',
        data: { productIds: productIds },
        beforeSend: function () {
            $("#loading").fadeIn();
        },
        success: function(response) {
            $('#selected-products').empty().html(response.result);
            removeSelectedProduct();
        },
        complete: function () {
            $("#loading").fadeOut();
        },
    });

}
