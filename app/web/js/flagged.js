$(function() {
    $('#message-container').on('click', '.flag-message', function() {
        let that = $(this);
        alterFlag(0, that);
    });
});
