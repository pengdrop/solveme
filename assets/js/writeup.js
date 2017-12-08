$(function() {
	$('#writeup-upload-form').submit(function() {
		var challenge = $(this).find('#writeup-upload-chall');
		var contents = $(this).find('#writeup-upload-contents');
		if (!challenge.val()) {
			challenge.focus();
			return false
		}
		if (!/^[\x00-\xFF]{1,10240}$/.test(contents.val())) {
			contents.focus();
			return false
		}
		$.post({
			url: '/writeup/upload',
			data: {
				'challenge': challenge.val(),
				'contents': contents.val()
			},
			dataType: 'json',
			success: function(res) {
				switch (res.status) {
					case 'o':
						show_alert('success', '<b>Success!</b> Please wait while you are redirected.');
						location.replace('/writeup/'+res.no);
						break;
					case 'l':
						show_alert('warning', '<b>Warning!</b> You\'re not logged in. Please login first.');
						break;
					case 'p':
						show_alert('warning', '<b>Failed!</b> You can\'t upload writeup about this challenge.');
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
	$('.delete-writeup').click(function() {
		var no = $(this).data('no');
		$.post({
			url: '/writeup/delete',
			data: {
				'no': no
			},
			dataType: 'json',
			success: function(res) {
				switch (res.status) {
					case 'o':
						show_alert('success', '<b>Success!</b> Please wait while you are redirected.');
						location.replace('/writeup');
						break;
					case 'l':
						show_alert('warning', '<b>Warning!</b> You\'re not logged in. Please login first.');
						break;
					case 'n':
						show_alert('warning', '<b>Failed!</b> This writeup isn\'t already exists.');
						break;
					case 'p':
						show_alert('warning', '<b>Failed!</b> You can\'t delete this writeup.');
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