$(document).ready(function (){
    'use strict'
    let getChattingNewNotificationCheckRoute = $('#getChattingNewNotificationCheckRoute').data('route');
    let chattingNewNotificationAlert = $('#chatting-new-notification-check');
    let chattingNewNotificationAlertMsg = $('#chatting-new-notification-check-message');
    setInterval(function () {
        $.get({
            url: getChattingNewNotificationCheckRoute,
            dataType: 'json',
            success: function (response) {
                if (response.newMessagesExist !== 0 && response.message) {
                    chattingNewNotificationAlertMsg.html(response.message)
                    chattingNewNotificationAlert.addClass('active');
                    playAudio();
                    setTimeout(function (){
                        chattingNewNotificationAlert.removeClass('active')
                    }, 5000);
                }
            },
        });

    }, 15000);
})
