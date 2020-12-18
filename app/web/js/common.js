function alterFlag() {
	let id = $(this).closest('.message-item').data('id');
	if(!id || !confirm(confirmMessage)) return;

	$.ajax({
		async: true,	// не ждем завершения
		type: "POST",
		url: $('#add-message-container').data('flag-url'),
		dataType: 'json',
		data: {id: id, flagged: flagParam},
		cache: false,
		global: false,
		success: function (res) {
			console.log('success', res);
			$('.message-item').filter(`[data-id='${id}']`).remove();
		},// success()
		error: function (jqXHR, textStatus, errorThrown) {
			// 	App.unblockUI();
			// 	App.alert({
			// 		container: '#form-save-alert',
			// 		place: 'append',
			// 		type: 'danger',
			// 		message: errorThrown.length ? errorThrown : 'Возникла ошибка, попробуйте повторить операцию позже',
			// 		close: true,
			// 		reset: true
			//	});
			console.log(jqXHR, textStatus, errorThrown)
			//console.log(Object.entries(jqXHR.responseJSON.errors).join('; '))
			var errorStr;
			if (jqXHR.responseJSON.errors) {
				errorStr = jqXHR.responseJSON.errors.join('; ');
			} else {
				errorStr = jqXHR.responseJSON.message;
			}
			toastr.error(errorStr);
		}// error()
	});// ajax()
}
$(function() {
	scrollDown();
	toastr.options = {
		"closeButton": true,
		"debug": false,
		"positionClass": "toast-top-right",
		"onclick": null,
		"showDuration": "1000",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	};

	function scrollDown() {
		document.getElementById('message-container').scrollTo(0, document.body.scrollHeight);
	}
	//var flaggedMessages = $('.flag-message');
	var isFLaggedList = $('#message-container').data('isflagged');
	var confirmMessage, flagParam;
	[confirmMessage, flagParam] = isFLaggedList ? ["Вернуть сообщение в чат?", false] : ["Помечать сообщение?", true];
	$('.flag-message').click(alterFlag);
	// 	function alterFlag() {
	// 	let id = $(this).closest('.message-item').data('id');
	// 	if(!id || !confirm(confirmMessage)) return;
	//
	// 	$.ajax({
	// 		async: true,	// не ждем завершения
	// 		type: "POST",
	// 		url: $('#add-message-container').data('flag-url'),
	// 		dataType: 'json',
	// 		data: {id: id, flagged: flagParam},
	// 		cache: false,
	// 		global: false,
	// 		success: function (res) {
	// 			console.log('success', res);
	// 			$('.message-item').filter(`[data-id='${id}']`).remove();
	// 		},// success()
	// 		error: function (jqXHR, textStatus, errorThrown) {
	// 			// 	App.unblockUI();
	// 			// 	App.alert({
	// 			// 		container: '#form-save-alert',
	// 			// 		place: 'append',
	// 			// 		type: 'danger',
	// 			// 		message: errorThrown.length ? errorThrown : 'Возникла ошибка, попробуйте повторить операцию позже',
	// 			// 		close: true,
	// 			// 		reset: true
	// 			//	});
	// 			console.log(jqXHR, textStatus, errorThrown)
	// 			//console.log(Object.entries(jqXHR.responseJSON.errors).join('; '))
	// 			var errorStr;
	// 			if (jqXHR.responseJSON.errors) {
	// 				errorStr = jqXHR.responseJSON.errors.join('; ');
	// 			} else {
	// 				errorStr = jqXHR.responseJSON.message;
	// 			}
	// 			toastr.error(errorStr);
	// 		}// error()
	// 	});// ajax()
	// }
});
