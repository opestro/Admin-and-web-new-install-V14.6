"use strict";
$(document).ready(function () {
    let messageHistory = $('.message-history');
    messageHistory.stop().animate({scrollTop: messageHistory[0].scrollHeight}, 1000);
});
$('#chat-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function () {
            toastr.success($('#get-send-success-msg').data('text'), {
                CloseButton: true,
                ProgressBar: true
            });
            $('#chat-form').trigger('reset');
        }
    });
});
$("#message-send-button").click(function (e) {
    e.preventDefault();

    let submitMessage = $('#submit-message');
    let messageHistory = $('.message-history');
    let inputs = submitMessage.find('#write-message').val();
    let newShopId = submitMessage.find('#shop-id').val();
    let newSellerId =submitMessage.find('#seller-id').val();
    let deliveryManId =submitMessage.find('#delivery-man-id').val();

    let data = {
        message: inputs,
        shop_id: newShopId,
        seller_id: newSellerId,
        delivery_man_id:deliveryManId
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: submitMessage.attr('method'),
        url: submitMessage.attr('action'),
        data: data,
        success: function (response) {
            if (response.message)
            {
                $(".message-history").append(`
                     <div class="outgoing_msg" id="outgoing_msg">
                        <p class="message_text">
                            ${response.message }
                        </p>
                        <span class="time_date">now</span>
                    </div>`
                )
            }
        },
        error: function (error) {
            toastr.warning(error.responseJSON)
        }
    });
    submitMessage.find('#write-message').val('');
    messageHistory.stop().animate({scrollTop: messageHistory[0].scrollHeight}, 1000);
});
$("#search-value").on("keyup", function () {
    let value = $(this).val().toLowerCase();
    $(".chat-list").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});
$('.remove-mask-img').on('click', function(){
    $('.show-more--content').removeClass('active')
})
