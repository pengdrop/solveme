$(function() {
	$('#auth-form').submit(function() {
		var flag = $(this).find('#auth-flag');
		if (!flag.val()) {
			flag.focus();
			return false
		}
		$.post({
			url: '/chall/auth',
			data: {
				'flag': flag.val()
			},
			dataType: 'json',
			success: function(res) {
				switch (res.status) {
					case 'o':
						show_alert('success', '<b>Congrats!</b> You solved the <a href="/chall/' + res.href + '" class="alert-link">' + res.title + '</a> problem, so you got a ' + res.score + 'pt.');
						$('#auth-flag').val(null);
						$('.prob-title').each(function() {
							if ($(this).html() == res.title) {
								$(this).after('<span class="label label-success ml-10">Solved</span>')
							}
						});
						break;
					case 'a':
						show_alert('info', '<b>Correct!</b> But you\'re already solved the <a href="/chall/' + res.href + '" class="alert-link">' + res.title + '</a> problem.');
						$('#auth-flag').val(null);
						break;
					case 'l':
						show_alert('warning', '<b>Warning!</b> You\'re not logged in. Please login first.');
						break;
					case 'x':
						show_alert('danger', '<b>Failed!</b> This flag is wrong.');
						$('#auth-flag').focus();
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