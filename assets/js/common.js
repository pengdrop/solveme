function show_alert(type, contents) {
	var html='<div class="alert alert-' + type + ' alert-dismissible fade in alert-fixed" role="alert">' + 
		'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span class="sans-serif" aria-hidden="true">&times;</span></button>' +
		 '<span>' + contents+ '</span>' + 
		 '</div>';

	if($('.main-body .alert').length == 0){
		$('.main-body').prepend(html);
	}else{
		$('.main-body .alert:last').after(html);
	}

	var alert = $('.main-body').find('.alert');
	if(alert.length == 4){
		alert[0].remove();
	};
}
$(function() {
	$('[data-toggle="tooltip"]').tooltip();
	$('.admin-contact').attr('href', 'mailto:' + decodeURIComponent('%66%75%7A%7A%65%72%40%6E%61%74%65%2E%63%6F%6D'));
	$('.secret-contact').click(function() {
		show_alert('info', '<b>Secret!</b> This user\'s email is secret for privacy.')
	});
	$('.go-back').click(function() {
		var href = $(this).data('href');
		var link = location.protocol+'//'+location.host + href;
		if(document.referrer.indexOf(link) == 0){
			history.back();
		}else{
			location.href=href;
		}
	});
});