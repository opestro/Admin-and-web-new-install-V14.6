'use strict';
$('.reset-button').on('click',function (){
    let placeholderImg = $("#placeholderImg").data('img');
    $('#viewer').attr('src', placeholderImg);
    $('#viewerBanner').attr('src', placeholderImg);
    $('#viewerBottomBanner').attr('src', placeholderImg);
    $('#viewerLogo').attr('src', placeholderImg);
    $('.spartan_remove_row').click();
})

$('#exampleInputPassword ,#exampleRepeatPassword').on('keyup',function () {
    let pass = $("#exampleInputPassword").val();
    let passRepeat = $("#exampleRepeatPassword").val();
    if (pass === passRepeat){
        $('.pass').hide();
    }
    else{
        $('.pass').show();
    }
});
$('#apply').on('click',function () {
    let image = $("#image-set").val();
    if (image===null)
    {
        $('.image').show();
        return false;
    }
    let pass = $("#exampleInputPassword").val();
    let passRepeat = $("#exampleRepeatPassword").val();
    if (pass!==passRepeat){
        $('.pass').show();
        return false;
    }
});
