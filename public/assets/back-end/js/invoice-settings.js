$('#invoice-image').on('change', function(){
    var input = this;
    console.log(input);
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        let inputImage = $('.input_image');
        reader.onload = (e) => {
            let imageData = e.target.result;
            input.setAttribute("data-title", "");
            let img = new Image();
            img.onload = function () {
                inputImage.css({
                    "background-image": `url('${imageData}')`,
                    "width": "100%",
                    "height": "auto",
                    backgroundPosition: "center",
                    backgroundSize: "contain",
                    backgroundRepeat: "no-repeat",
                });
                inputImage.addClass('hide-before-content')
            };
            img.src = imageData;
        }
        reader.readAsDataURL(input.files[0]);
    }
});
let businessIdentity = $('.business-identity');
businessIdentity.on('change', function(){
    let value = $(this).val();
    $('#business-identity-value').attr('placeholder', 'Enter '+value);
});
businessIdentity.on('dblclick',function (){
    let isChecked = $(this).prop('checked');
    $(this).prop('checked', !isChecked);
    $('#business-identity-value').attr('placeholder','');
})
