$(function() {
    $('#add-message-container').show();
    scrollDown();

    var sendButton = $('#send-button');
    var loadingSvg = $('#loading-svg');
    var addMessage = $('#add-message');

    $(document)
        .ajaxStart(function () {
            sendButton.hide();
            loadingSvg.show();
        })
        .ajaxStop(function () {
            loadingSvg.hide();
            sendButton.show();
        });

    $(function() {
        $('#message-container').on('click', '.flag-message', function() {
            let that = $(this);
            alterFlag(1, that);
        });
    });

    function sendMessage() {
        var text = addMessage.val();
        if (!text) return;

        $.ajax({
            async: true,
            type: "POST",
            url: $('#add-message-container').data('url'),
            dataType: 'json',
            data: {text: text},
            cache: false,
            global: false,
            success: function (res) {
                if (!res.id) {
                    toastr.error('Невозможно сохранить сообщение');
                    return;
                }
                let id = res.id;
                $('#no-messages').remove();
                addMessage.val('');
                let data = $('#input-container').data();
                let username = data.username;
                let isAdmin = data.isadmin;
                let isBold, flagDiv;
                [isBold, flagDiv] = isAdmin ? [' bold', '<div class="flag-message col-xs-2"><i class="fas fa-flag"></i></div>'] :
                    ['', ''];
                let now = new Date();
                let dateStr = now.getUTCMonth() + 1 + '-' + now.getUTCDate() + ' ' + now.getUTCHours() + ':' +
                    now.getUTCMinutes();
                $('#message-container').append(`
                    <div class="message-item row" data-id="${id}">
                        <div class="col-xs-2${isBold}">${username}</div>
                        <div class="col-xs-5${isBold}">${text}</div>
                        <div class="col-xs-3${isBold}">${dateStr}</div>
                        ${flagDiv}
                    </div>
                `);
                scrollDown();
            },
            error: toastrError
        });
    }

    sendButton.click(sendMessage);
    addMessage.keyup(function(event) {
        if ($(this).is(":focus") && event.key === "Enter") {
            sendMessage();
        }
    });

});
