$(function() {
	$('#message-container').on('click', '.flag-message', function() {
		let that = $(this);
		alterFlag("Вернуть сообщение в чат?", 0, that);
	});
});
