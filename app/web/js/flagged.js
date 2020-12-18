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

	$('#message-container').on('click', '.flag-message', function() {
		let id = $(this).closest('.message-item').data('id');
		if (!id) {
			alert('Пожалуйста, обновите страницу и попробуйте еще раз.');
			return;
		}
		if(!confirm("Вернуть сообщение в чат?")) return;

		$.ajax({
			async: true,
			type: "POST",
			url: $('#message-container').data('flag-url'),
			dataType: 'json',
			data: {id: id, flagged: 0},
			cache: false,
			global: false,
			success: function () {
				$('.message-item').filter(`[data-id='${id}']`).remove();
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
	});

});
