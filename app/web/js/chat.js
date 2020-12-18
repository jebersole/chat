$(function() {
	$('#add-message-container').show();
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

	function scrollDown() {
		document.getElementById('message-container').scrollTo(0, document.body.scrollHeight);
	}

	$('#message-container').on('click', '.flag-message', function() {
		let id = $(this).closest('.message-item').data('id');
		if (!id) {
			alert('Пожалуйста, обновите страницу и попробуйте еще раз.');
			return;
		}
		if(!confirm("Помечать сообщение?")) return;

		$.ajax({
			async: true,
			type: "POST",
			url: $('#message-container').data('flag-url'),
			dataType: 'json',
			data: {id: id, flagged: 1},
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
			success: function () {
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
					<div class="message-item row">
						<div class="col-xs-2${isBold}">${username}</div>
						<div class="col-xs-5${isBold}">${text}</div>
						<div class="col-xs-3${isBold}">${dateStr}</div>
						${flagDiv}
					</div>
				`);
				scrollDown();
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

	sendButton.click(sendMessage);
	addMessage.keyup(function(event) {
		if ($(this).is(":focus") && event.key === "Enter") {
			sendMessage();
		}
	});

});
