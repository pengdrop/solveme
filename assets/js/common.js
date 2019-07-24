function show_alert(type, contents) {
	let html='<div class="alert alert-' + type + ' alert-dismissible fade in alert-fixed" role="alert">' + 
		'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span class="sans-serif" aria-hidden="true">&times;</span></button>' +
		 '<span>' + contents+ '</span>' + 
		 '</div>';

	if($('.main-body .alert').length == 0){
		$('.main-body').prepend(html);
	}else{
		$('.main-body .alert:last').after(html);
	}

	let alert = $('.main-body').find('.alert');
	if(alert.length == 4){
		alert[0].remove();
	};
}
function localize_time(time){
	let t = new Date(time + ' UTC');
	let year = t.getFullYear();
	let month = t.getMonth() + 1;
		month = month < 10 ? '0' + month : month;
	let date = t.getDate();
		date = date < 10 ? '0' + date : date;
	let hour = t.getHours();
		hour = hour < 10 ? '0' + hour : hour;
	let min = t.getMinutes();
		min = min < 10 ? '0' + min : min;
	let sec = t.getSeconds();
		sec = sec < 10 ? '0' + sec : sec;
	return year + '-' + month + '-' + date + ' ' + hour + ':' + min + ':' + sec;
}
$(function() {
	$('[data-toggle="tooltip"]').tooltip();
	$('.admin-contact').attr('href', 'mailto:plzdonotsay@gmail.com'));
	$('.secret-contact').click(function() {
		show_alert('info', '<b>Secret!</b> This user\'s email is secret for privacy.')
	});
	$('.go-back').click(function() {
		let href = $(this).data('href'),
			link = location.protocol+'//'+location.host + href;
		if(document.referrer.indexOf(link) == 0){
			history.back();
		}else{
			location.href=href;
		}
	});
	$('time').each(function(){
		//$(this).html(localize_time($(this).html()))
	});
});
