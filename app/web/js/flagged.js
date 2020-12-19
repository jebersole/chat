$(function() {
	$('#message-container').on('click', '.flag-message', function() {
		let that = $(this);
		alterFlag("Вернуть сообщение в чат?", 'Нет помеченных сообщений.', 0, that);
	});
});
