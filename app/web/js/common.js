function scrollDown() {
	document.getElementById('message-container').scrollTo(0, document.body.scrollHeight);
}

function alterFlag(confirmMessage, noMessages, flagParam, scope) {
	let id = scope.closest('.message-item').data('id');
	if (!id) {
		alert('Пожалуйста, перезагрузите страницу.');
		return;
	}
	if (!confirm(confirmMessage)) return;

	$.ajax({
		async: true,
		type: "POST",
		url: $('#message-container').data('flag-url'),
		dataType: 'json',
		data: {id: id, flagged: flagParam},
		cache: false,
		global: false,
		success: function () {
			let messages = $('.message-item');
			messages.filter(`[data-id='${id}']`).remove();
			if (messages.length === 1) {
				$('#message-container').append(`
					<div class="row" id="no-messages">
						<div class="col-xs-12">${noMessages}</div>
					</div>
				`);
			}	
		},
		error: function (jqXHR) {
			var errorStr;
			if (jqXHR.responseJSON.errors) {
				errorStr = jqXHR.responseJSON.errors.join('; ');
			} else {
				errorStr = jqXHR.responseJSON.message;
			}
			toastr.error(errorStr);
		}
	});
}

$(function() {
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
});
