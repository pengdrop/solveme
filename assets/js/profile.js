$(function() {
	$('#edit-form').submit(function() {
		var email = $(this).find('#edit-email');
		var open_email = $(this).find('#edit-open-email');
		var current_password = $(this).find('#edit-current-password');
		var change_password = $(this).find('#edit-change-password');
		var new_password = $(this).find('#edit-new-password');
		var confirm_new_password = $(this).find('#edit-confirm-new-password');
		var comment = $(this).find('#edit-comment');
		if (!/^[\x00-\xFF]{6,320}$/.test(email.val()) || !/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test(email.val())) {
			email.focus();
			return false
		}
		if (!/^[\x00-\xFF]{6,50}$/.test(current_password.val())) {
			current_password.focus();
			return false
		}
		if(change_password.is(':checked')){
			if (!/^([\x00-\xFF]{6,50})$/.test(new_password.val())) {
				new_password.focus();
				return false
			}
			if (new_password.val() != confirm_new_password.val()) {
				confirm_new_password.focus();
				return false
			}
		}
		if (!/^[\x00-\xFF]{0,30}$/.test(comment.val())) {
			comment.focus();
			return false
		}
		$.post({
			url: '/profile/edit',
			data: {
				'email': email.val(),
				'open-email': open_email.is(':checked') ? '1' : '0',
				'password': current_password.val(),
				'new-password': change_password.is(':checked') ? new_password.val() : '',
				'comment': comment.val()
			},
			dataType: 'json',
			success: function(res) {
				switch (res.status) {
					case 'o':
						show_alert('success', '<b>Success!</b> Please wait while you are redirected.');
						location.replace('/profile');
						break;
					case 'l':
						show_alert('warning', '<b>Warning!</b> You\'re not logged in. Please login first.');
						break;
					case 'e':
						show_alert('warning', '<b>Warning!</b> This email is already exists.');
						$('#join-email').focus();
						break;
					case 'p':
						show_alert('danger', '<b>Failed!</b> This password id wrong.');
						$('#edit-current-password').focus();
						break;
					default:
						show_alert('danger', '<b>Error!</b> Please try again.')
				}
			},
			error: function() {
				show_alert('danger', '<b>Error!</b> Please try again.')
			}
		});
		return false
	});
});