$(function() {
	$('.show-login').click(function() {
		$('#join-form').hide(150, function() {
			$('#login-form').show(150);
		})
	});
	$('.show-join').click(function() {
		$('#login-form').hide(150, function() {
			$('#join-form').show(150);
		})
	});
	$('#login-form').submit(function() {
		var username = $(this).find('#login-username');
		var password = $(this).find('#login-password');
		if (!username.val()) {
			username.focus();
			return false
		}
		if (!password.val()) {
			password.focus();
			return false
		}
		$.ajax({
			method: $(this).attr('method'),
			url: $(this).attr('action'),
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				switch (res.status) {
					case 'o':
						show_alert('success', '<b>Success!</b> Hello, ' + res.username + '! Please wait while you are redirected.');
						if(location.pathname == '/login'){
							location.replace('/');
						}else{
							location.reload(0);
						}
						break;
					case 'a':
						show_alert('info', '<b>Info!</b> You\'re already logged in. Please wait while you are redirected.');
						if(location.pathname == '/login'){
							location.replace('/');
						}else{
							location.reload(0);
						}
						break;
					case 'x':
						show_alert('danger', '<b>Failure!</b> This username or password is wrong.');
						$('#login-password').focus();
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
	$('#join-form').submit(function() {
		var username = $(this).find('#join-username');
		var email = $(this).find('#join-email');
		var open_email = $(this).find('#join-open-email');
		var password = $(this).find('#join-password');
		var confirm_password = $(this).find('#join-confirm-password');
		var comment = $(this).find('#join-comment');
		if (!/^[a-zA-Z0-9_-]{5,20}$/.test(username.val())) {
			username.focus();
			return false
		}
		if (!/^[\x00-\xFF]{6,320}$/.test(email.val()) || !/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test(email.val())) {
			email.focus();
			return false
		}
		if (!/^[\x00-\xFF]{6,50}$/.test(password.val())) {
			password.focus();
			return false
		}
		if (password.val() != confirm_password.val()) {
			confirm_password.focus();
			return false
		}
		if (!/^[\x00-\xFF]{0,30}$/.test(comment.val())) {
			comment.focus();
			return false
		}
		$.ajax({
			method: $(this).attr('method'),
			url: $(this).attr('action'),
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				switch (res.status) {
					case 'o':
						show_alert('success', '<b>Success!</b> Welcome, ' + res.username + '! Please wait while you are redirected.');
						if(location.pathname == '/login'){
							location.replace('/');
						}else{
							location.reload(0);
						}
						break;
					case 'a':
						show_alert('info', '<b>Info!</b> You\'re already logged in. Please wait while you are redirected.');
						if(location.pathname == '/login'){
							location.replace('/');
						}else{
							location.reload(0);
						}
						break;
					case 'u':
						show_alert('warning', '<b>Warning!</b> This username is already exists.');
						$('#join-username').focus();
						break;
					case 'e':
						show_alert('warning', '<b>Warning!</b> This email is already exists.');
						$('#join-email').focus();
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